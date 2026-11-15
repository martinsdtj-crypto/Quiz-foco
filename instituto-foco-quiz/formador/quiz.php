<?php
require '../apoio/formador.php';
/** @var mysqli $ligacao */
$ligacao = require '../apoio/ligacao.php';
$erro = '';
$aviso = '';
$edicao = null;

// Se ainda faltar a actualização SQL, explicar como preparar a base.
$resultado = mysqli_query($ligacao, 'SELECT * FROM quiz_perguntas ORDER BY id');
$preparado = $resultado !== false;

if ($preparado && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $acao = '';
    if (isset($_POST['acao'])) {
        $acao = $_POST['acao'];
    }
    $id = 0;
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
    }
    $id = (int) $id;

    // Uma transacção impede duas gravações simultâneas de ultrapassar o limite.
    mysqli_begin_transaction($ligacao);
    mysqli_query($ligacao, 'SELECT id FROM formador WHERE id = 1 FOR UPDATE');

    if ($acao == 'remover') {
        $pedido = mysqli_prepare($ligacao, 'DELETE FROM quiz_perguntas WHERE id = ?');
        mysqli_stmt_bind_param($pedido, 'i', $id);
    } elseif ($acao == 'guardar') {
        $pergunta = '';
        if (isset($_POST['pergunta'])) {
            $pergunta = $_POST['pergunta'];
        }
        $pergunta = trim($pergunta);
        $a = '';
        if (isset($_POST['opcao_a'])) {
            $a = $_POST['opcao_a'];
        }
        $a = trim($a);
        $b = '';
        if (isset($_POST['opcao_b'])) {
            $b = $_POST['opcao_b'];
        }
        $b = trim($b);
        $c = '';
        if (isset($_POST['opcao_c'])) {
            $c = $_POST['opcao_c'];
        }
        $c = trim($c);
        $correcta = '';
        if (isset($_POST['correcta'])) {
            $correcta = $_POST['correcta'];
        }
        $explicacao = '';
        if (isset($_POST['explicacao'])) {
            $explicacao = $_POST['explicacao'];
        }
        $explicacao = trim($explicacao);
        $activa = 0;
        if (isset($_POST['activa'])) {
            $activa = 1;
        }
        $edicao = ['id' => $id, 'pergunta' => $pergunta, 'opcao_a' => $a,
            'opcao_b' => $b, 'opcao_c' => $c, 'correcta' => $correcta,
            'explicacao' => $explicacao, 'activa' => $activa];

        if ($pergunta == '' || $a == '' || $b == '' || $c == '' || $explicacao == '') {
            $erro = 'Preenche a pergunta, as três opções e a explicação.';
        } elseif (!in_array($correcta, ['A', 'B', 'C'], true)) {
            $erro = 'Escolhe a resposta correcta: A, B ou C.';
        } elseif (mb_strlen($pergunta) > 240 || mb_strlen($a) > 160 || mb_strlen($b) > 160 || mb_strlen($c) > 160 || mb_strlen($explicacao) > 400) {
            $erro = 'Um dos textos ultrapassa o limite indicado no formulário.';
        } elseif ($a == $b || $a == $c || $b == $c) {
            $erro = 'Escreve três opções diferentes.';
        }

        $resultadoContagem = mysqli_query($ligacao, 'SELECT COUNT(*) FROM quiz_perguntas');
        $contagem = mysqli_fetch_row($resultadoContagem);
        $total = $contagem[0];
        if ($id == 0 && $total >= 10) {
            $erro = 'Já tens 10 perguntas. Edita ou remove uma antes de acrescentar outra.';
        }

        if ($erro == '' && $id > 0) {
            $pedido = mysqli_prepare($ligacao, 'UPDATE quiz_perguntas SET pergunta = ?, opcao_a = ?, opcao_b = ?, opcao_c = ?, correcta = ?, explicacao = ?, activa = ? WHERE id = ?');
            mysqli_stmt_bind_param($pedido, 'ssssssii', $pergunta, $a, $b, $c, $correcta, $explicacao, $activa, $id);
        } elseif ($erro == '') {
            $pedido = mysqli_prepare($ligacao, 'INSERT INTO quiz_perguntas (pergunta, opcao_a, opcao_b, opcao_c, correcta, explicacao, activa) VALUES (?, ?, ?, ?, ?, ?, ?)');
            mysqli_stmt_bind_param($pedido, 'ssssssi', $pergunta, $a, $b, $c, $correcta, $explicacao, $activa);
        }
    } else {
        $erro = 'Acção inválida.';
    }

    if ($erro == '' && mysqli_stmt_execute($pedido)) {
        mysqli_commit($ligacao);
        header('Location: quiz.php?guardado=1');
        exit;
    }
    mysqli_rollback($ligacao);
    if ($erro == '') {
        $erro = 'Não foi possível guardar. Tenta novamente.';
    }
}

if ($preparado && $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $pedido = mysqli_prepare($ligacao, 'SELECT * FROM quiz_perguntas WHERE id = ?');
    mysqli_stmt_bind_param($pedido, 'i', $id);
    mysqli_stmt_execute($pedido);
    $resultadoPedido = mysqli_stmt_get_result($pedido);
    $edicao = mysqli_fetch_assoc($resultadoPedido);
    if (!$edicao) {
        $aviso = 'Essa pergunta já não existe.';
    }
}

// Valores iniciais para criar uma pergunta ou mostrar a pergunta em edição.
$formulario = [
    'id' => 0,
    'pergunta' => '',
    'opcao_a' => '',
    'opcao_b' => '',
    'opcao_c' => '',
    'correcta' => '',
    'explicacao' => '',
    'activa' => 1
];
if ($edicao) {
    $formulario = $edicao;
}

$titulo = 'Gerir perguntas do quiz';
require '../apoio/topo.php';
?>
<div class="pagina-titulo">
    <div>
        <p class="etiqueta">PREPARAR O DESAFIO</p>
        <h1>As perguntas <em>são tuas.</em></h1>
        <p>Guarda até 10 perguntas. Só as perguntas activas aparecem à turma, pela ordem de criação.</p>
    </div>
    <a class="botao botao-secundario" href="index.php">Voltar ao painel</a>
</div>
<?php if (!$preparado): ?>
<p class="erro" role="alert">Importa base_de_dados.sql no phpMyAdmin e actualiza esta página. Os teus registos serão preservados.</p>
<?php else: ?>
<?php if (isset($_GET['guardado'])): ?>
<p class="sucesso" role="status">Alteração guardada. Abre ou recomeça o quiz para usar as novas perguntas.</p>
<?php endif; ?>
<p class="erro" role="alert"><?= htmlspecialchars($erro) ?></p>
<p class="nota"><?= htmlspecialchars($aviso) ?></p>
<div class="grelha">
    <section class="cartao formulario">
        <h2><?php
        if ($edicao) {
            echo 'Editar pergunta';
        } else {
            echo 'Nova pergunta';
        }
        ?></h2>
        <form method="post">
            <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
            <input type="hidden" name="acao" value="guardar">
            <input type="hidden" name="id" value="<?= (int) ($formulario['id']) ?>">
            <label>Pergunta (até 240 caracteres)
                <input name="pergunta" required maxlength="240" value="<?= htmlspecialchars($formulario['pergunta']) ?>">
            </label>
            <label>Opção A (até 160 caracteres)
                <input name="opcao_a" required maxlength="160" value="<?= htmlspecialchars($formulario['opcao_a']) ?>">
            </label>
            <label>Opção B (até 160 caracteres)
                <input name="opcao_b" required maxlength="160" value="<?= htmlspecialchars($formulario['opcao_b']) ?>">
            </label>
            <label>Opção C (até 160 caracteres)
                <input name="opcao_c" required maxlength="160" value="<?= htmlspecialchars($formulario['opcao_c']) ?>">
            </label>
            <label>Resposta correcta
                <select name="correcta" required>
                    <option value="">Escolher</option>
                    <option value="A" <?php
                    if (($formulario['correcta']) == 'A') {
                        echo 'selected';
                    }
                    ?>>A</option>
                    <option value="B" <?php
                    if (($formulario['correcta']) == 'B') {
                        echo 'selected';
                    }
                    ?>>B</option>
                    <option value="C" <?php
                    if (($formulario['correcta']) == 'C') {
                        echo 'selected';
                    }
                    ?>>C</option>
                </select>
            </label>
            <label>Explicação após responder (até 400 caracteres)
                <textarea name="explicacao" required maxlength="400" rows="3"><?= htmlspecialchars($formulario['explicacao']) ?></textarea>
            </label>
            <label class="quiz-checkbox">
                <input type="checkbox" name="activa" value="1" <?php
                if (($formulario['activa'])) {
                    echo 'checked';
                }
                ?>> Activa no quiz
            </label>
            <div class="acoes">
                <button class="botao botao-principal">Guardar pergunta</button>
                <a class="botao botao-secundario" href="quiz.php">Limpar / nova pergunta</a>
            </div>
        </form>
    </section>
    <section>
        <h2>Perguntas guardadas</h2>
        <p class="nota">Desactiva para guardar uma pergunta sem a apresentar. Remover elimina definitivamente a pergunta.</p>
        <?php $resultado = mysqli_query($ligacao, 'SELECT * FROM quiz_perguntas ORDER BY id'); ?>
        <?php if (mysqli_num_rows($resultado) == 0): ?>
        <p class="nota">Ainda não há perguntas. Cria a primeira no formulário.</p>
        <?php endif; ?>
        <?php while ($item = mysqli_fetch_assoc($resultado)): ?>
        <article class="cartao">
            <span class="estado <?php
            if ($item['activa']) {
                echo 'estado-sucesso';
            } else {
                echo 'estado-neutro';
            }
            ?>"><?php
            if ($item['activa']) {
                echo 'Activa';
            } else {
                echo 'Inactiva';
            }
            ?></span>
            <h3><?= htmlspecialchars($item['pergunta']) ?></h3>
            <p>Resposta correcta: <b><?= htmlspecialchars($item['correcta']) ?></b></p>
            <div class="acoes">
                <a class="botao botao-secundario" href="quiz.php?id=<?= (int) $item['id'] ?>">Editar</a>
                <form method="post" onsubmit="return confirm('Remover definitivamente esta pergunta?')">
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                    <input type="hidden" name="acao" value="remover">
                    <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                    <button class="botao botao-perigo">Remover</button>
                </form>
            </div>
        </article>
        <?php endwhile; ?>
        <a class="botao botao-principal" href="../quiz/index.php">Experimentar o quiz →</a>
    </section>
</div>
<?php endif; ?>
<?php require '../apoio/fim.php'; ?>

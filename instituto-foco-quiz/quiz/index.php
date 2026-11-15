<?php
/** @var mysqli $ligacao */
$ligacao = require '../apoio/ligacao.php';
// Carregar uma cópia das perguntas para esta partida. Não exige login.
$resultado = mysqli_query($ligacao, 'SELECT * FROM quiz_perguntas WHERE activa = 1 ORDER BY id LIMIT 10');
$total = 0;
if ($resultado) {
    $total = mysqli_num_rows($resultado);
}
$titulo = 'Quiz colectivo';
$tema = 'pagina-quiz';
require '../apoio/topo.php';
?>
<div class="pagina-titulo">
    <div>
        <p class="etiqueta">01 / PARTICIPAR</p>
        <h1>O próximo clique<br>é uma <em>escolha tua.</em></h1>
        <p>A turma responde em conjunto. O sistema verifica e explica.</p>
    </div>
    <div class="quiz-medalha" aria-hidden="true">?</div>
</div>
<?php if (!$resultado): ?>
<section class="cartao">
    <h2>O quiz ainda está a ser preparado.</h2>
    <p>O funcionário precisa de importar base_de_dados.sql no phpMyAdmin.</p>
    <a class="botao botao-secundario" href="../index.php">Voltar ao início</a>
</section>
<?php elseif ($total == 0): ?>
<section class="cartao">
    <h2>O próximo desafio está a caminho!</h2>
    <p>Ainda não há perguntas activas. O funcionário pode prepará-las no seu painel.</p>
    <a class="botao botao-secundario" href="../index.php">Voltar ao início</a>
</section>
<?php else: ?>
<div class="quiz-topo">
    <span>DESAFIO COLECTIVO <b id="etapa">1 / <?= $total ?></b></span>
    <span><b id="pontos">0</b> pontos</span>
</div>
<!-- O while cria um bloco por pergunta; hidden mostra só o primeiro. -->
<?php $numero = 0; ?>
<?php while ($pergunta = mysqli_fetch_assoc($resultado)): ?>
<?php $numero = $numero + 1; ?>
<section class="cartao quiz-cartao pergunta-quiz" id="pergunta<?= $numero ?>"
    data-correcta="<?= htmlspecialchars($pergunta['correcta']) ?>"
    data-explicacao="<?= htmlspecialchars($pergunta['explicacao']) ?>" <?php
    if ($numero > 1) {
        echo 'hidden';
    }
    ?>>
    <span class="numero-pergunta"><?= $numero ?></span>
    <h2 tabindex="-1"><?= htmlspecialchars($pergunta['pergunta']) ?></h2>
    <button class="opcao" onclick="responder('A')"><span>A</span> <?= htmlspecialchars($pergunta['opcao_a']) ?></button>
    <button class="opcao" onclick="responder('B')"><span>B</span> <?= htmlspecialchars($pergunta['opcao_b']) ?></button>
    <button class="opcao" onclick="responder('C')"><span>C</span> <?= htmlspecialchars($pergunta['opcao_c']) ?></button>
    <p class="feedback" role="status"></p>
    <button class="botao botao-principal seguinte" hidden onclick="avancar()">
        <?php
        if ($numero == $total) {
            echo 'Ver resultado final →';
        } else {
            echo 'Próxima pergunta →';
        }
        ?>
    </button>
</section>
<?php endwhile; ?>
<section class="cartao quiz-conclusao" id="conclusao" hidden aria-labelledby="titulo-final">
    <span class="quiz-trofeu" aria-hidden="true">★</span>
    <p class="etiqueta">DESAFIO CONCLUÍDO</p>
    <h2 id="titulo-final" tabindex="-1"></h2>
    <p id="mensagem-final"></p>
    <p class="quiz-pontuacao"><b id="percentagem-final">0</b>% de acertos</p>
    <p id="acertos-finais"></p>
    <p id="pontos-finais"></p>
    <p class="nota">Agora imagina criar o teu próprio quiz!</p>
    <div class="acoes">
        <button class="botao botao-principal" onclick="location.reload()">Tentar novamente ↻</button>
        <a class="botao botao-secundario" href="../index.php">Voltar ao início →</a>
    </div>
</section>
<div class="titulo-seccao" id="reiniciar">
    <button class="botao botao-secundario" onclick="location.reload()">Recomeçar o desafio</button>
    <span class="nota">Curiosidade conta mais do que a pontuação. Sem notas individuais.</span>
</div>
<script src="quiz.js"></script>
<?php endif; ?>
<?php require '../apoio/fim.php'; ?>

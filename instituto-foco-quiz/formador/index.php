<?php
require '../apoio/formador.php';
/** @var mysqli $ligacao */
$ligacao = require '../apoio/ligacao.php';
$resultadoContagem = mysqli_query($ligacao, 'SELECT COUNT(*) FROM quiz_perguntas');
$contagem = mysqli_fetch_row($resultadoContagem);
$total = $contagem[0];
$titulo = 'Painel do funcionário';
require '../apoio/topo.php';
?>
<section class="hero compacto">
    <div>
        <p class="etiqueta">ÁREA DO FUNCIONÁRIO</p>
        <h1>Quiz Colectivo</h1>
        <p>Gere os registos deste sistema.</p>
    </div>
    <div class="metricas"><div><b><?= (int) $total ?></b><span>perguntas guardadas</span></div></div>
</section>
<div class="grelha tres">
    <section class="cartao experiencia">
        <h2>Gerir perguntas</h2>
        <p>Cria, edita, activa ou remove até 10 perguntas.</p>
        <a href="quiz.php">Abrir →</a>
    </section>
    <section class="cartao experiencia">
        <h2>Experimentar o quiz</h2>
        <p>Joga sem login e consulta o resultado colectivo.</p>
        <a href="../quiz/index.php">Abrir →</a>
    </section>
</div>
<?php require '../apoio/fim.php'; ?>

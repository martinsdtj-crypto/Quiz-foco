<?php
require_once __DIR__ . '/sessao.php';
require_once __DIR__ . '/interface.php';

if (!isset($raiz)) {
    $raiz = '../';
}
if (!isset($tema)) {
    $tema = '';
}
// O nome da pasta identifica a área actual, sem depender do endereço de instalação.
$area = basename(dirname($_SERVER['SCRIPT_NAME']));
$ficheiro = basename($_SERVER['SCRIPT_NAME']);
$inicio = $raiz == '';

?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>
            <?= htmlspecialchars($titulo) ?> · Instituto Foco
        </title>
        <link rel="stylesheet" href="<?= $raiz ?>estilo.css">
    </head>
    <body class="<?= htmlspecialchars($tema) ?>">
        <header class="cabecalho">
            <a class="marca" href="<?= $raiz ?>index.php">
                <span class="logo-moldura">
                    <img src="<?= $raiz ?>assets/logo.png" alt="" width="70" height="57">
                </span>
                <span class="marca-texto">
                    <b>
                        Instituto Foco
                    </b>
                    <small>
                        Educação responsável
                    </small>
                </span>
            </a>
            <div class="acessos-conta">
                <?php if (isset($_SESSION['formador'])): ?>
                <a class="nav-conta" href="<?= $raiz ?>formador/index.php">Painel do funcionário</a>
                <form action="<?= $raiz ?>formador/sair.php" method="post">
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                    <button class="sair-menu">Sair</button>
                </form>
                <?php else: ?>
                <a class="nav-conta" href="<?= $raiz ?>formador/entrar.php">Acesso do funcionário</a>
                <?php endif; ?>
            </div>
        </header>
        <nav class="menu-principal" aria-label="Menu principal">
            <a href="<?= $raiz ?>quiz/index.php">Quiz colectivo</a>
        </nav>
        <main id="conteudo">
            <nav class="migalhas" aria-label="Localização">
                <a href="<?= $raiz ?>index.php">Quiz Colectivo</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page"><?= htmlspecialchars($titulo) ?></span>
            </nav>
            <?php if ($area == 'formador' && isset($_SESSION['formador'])): ?>
            <nav class="menu-area" aria-label="Áreas do funcionário">
                <a href="index.php" <?php
                if ($ficheiro == 'index.php') {
                    echo 'aria-current="page"';
                }
                ?>>Painel</a>
                <a href="quiz.php" <?php
                if ($ficheiro == 'quiz.php') {
                    echo 'aria-current="page"';
                }
                ?>>Perguntas do quiz</a>
            </nav>
            <?php endif; ?>

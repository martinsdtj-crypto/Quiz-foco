<?php
// Ler a configuração de exemplo ou a configuração pessoal, se existir.
$configuracao = require __DIR__ . '/configuracao.exemplo.php';
if (is_file(__DIR__ . '/configuracao.local.php')) {
    $configuracao = require __DIR__ . '/configuracao.local.php';
}

mysqli_report(MYSQLI_REPORT_OFF);
$ligacao = mysqli_connect(
    $configuracao['servidor'],
    $configuracao['utilizador'],
    $configuracao['senha'],
    $configuracao['base']
);

mysqli_set_charset($ligacao, 'utf8mb4');
return $ligacao;

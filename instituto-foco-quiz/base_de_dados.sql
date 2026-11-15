-- Instalação independente: Quiz Colectivo.
CREATE DATABASE IF NOT EXISTS cv5_quiz CHARACTER SET utf8mb4;
USE cv5_quiz;
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS formador (id INT PRIMARY KEY, senha VARCHAR(255) NOT NULL);

CREATE TABLE IF NOT EXISTS quiz_perguntas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pergunta VARCHAR(240) NOT NULL,
    opcao_a VARCHAR(160) NOT NULL,
    opcao_b VARCHAR(160) NOT NULL,
    opcao_c VARCHAR(160) NOT NULL,
    correcta CHAR(1) NOT NULL,
    explicacao VARCHAR(400) NOT NULL,
    activa TINYINT NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS quiz_actualizacoes (versao INT PRIMARY KEY);

START TRANSACTION;
INSERT IGNORE INTO quiz_actualizacoes (versao) VALUES (1);
SET @carregar = ROW_COUNT();
INSERT INTO quiz_perguntas (pergunta, opcao_a, opcao_b, opcao_c, correcta, explicacao)
SELECT 'Para que serve o HTML?', 'Organizar o conteúdo de uma página web', 'Alterar o volume do computador', 'Proteger o computador contra vírus', 'A', 'HTML organiza o conteúdo da página, como títulos, textos, imagens e ligações.' WHERE @carregar = 1;
INSERT INTO quiz_perguntas (pergunta, opcao_a, opcao_b, opcao_c, correcta, explicacao)
SELECT 'Qual destas tags cria um parágrafo?', '<img>', '<p>', '<br>', 'B', 'A tag <p> é usada para escrever um parágrafo.' WHERE @carregar = 1;
INSERT INTO quiz_perguntas (pergunta, opcao_a, opcao_b, opcao_c, correcta, explicacao)
SELECT 'Qual destas tags cria um título principal?', '<p>', '<img>', '<h1>', 'C', 'A tag <h1> representa um título principal da página.' WHERE @carregar = 1;
INSERT INTO quiz_perguntas (pergunta, opcao_a, opcao_b, opcao_c, correcta, explicacao)
SELECT 'Que tag usamos para colocar uma imagem na página?', '<img>', '<p>', '<h1>', 'A', 'A tag <img> permite inserir uma imagem na página.' WHERE @carregar = 1;
INSERT INTO quiz_perguntas (pergunta, opcao_a, opcao_b, opcao_c, correcta, explicacao)
SELECT 'Que tag usamos para criar uma ligação para outra página?', '<br>', '<a>', '<img>', 'B', 'A tag <a> cria uma hiperligação. O atributo href indica o destino.' WHERE @carregar = 1;
INSERT INTO quiz_perguntas (pergunta, opcao_a, opcao_b, opcao_c, correcta, explicacao)
SELECT 'Que tag permite passar o texto para a linha seguinte?', '<table>', '<h1>', '<br>', 'C', 'A tag <br> introduz uma quebra de linha.' WHERE @carregar = 1;
INSERT INTO quiz_perguntas (pergunta, opcao_a, opcao_b, opcao_c, correcta, explicacao)
SELECT 'Para que serve o CSS numa página web?', 'Definir cores, letras e outros estilos', 'Criar a ligação física à Internet', 'Guardar ficheiros numa pen USB', 'A', 'CSS define a apresentação visual da página, incluindo cores e tipos de letra.' WHERE @carregar = 1;
INSERT INTO quiz_perguntas (pergunta, opcao_a, opcao_b, opcao_c, correcta, explicacao)
SELECT 'Que propriedade CSS muda a cor do texto?', 'font-size', 'color', 'text-align', 'B', 'A propriedade color altera a cor do texto.' WHERE @carregar = 1;
INSERT INTO quiz_perguntas (pergunta, opcao_a, opcao_b, opcao_c, correcta, explicacao)
SELECT 'Que propriedade CSS muda o tamanho das letras?', 'color', 'text-align', 'font-size', 'C', 'A propriedade font-size permite aumentar ou diminuir o tamanho das letras.' WHERE @carregar = 1;
INSERT INTO quiz_perguntas (pergunta, opcao_a, opcao_b, opcao_c, correcta, explicacao)
SELECT 'Que tag usamos para criar uma tabela?', '<table>', '<img>', '<br>', 'A', 'A tag <table> cria uma tabela, que organiza informação em linhas e colunas.' WHERE @carregar = 1;
COMMIT;

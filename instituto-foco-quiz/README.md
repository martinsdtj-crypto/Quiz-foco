# Instituto Foco - Quiz Colectivo

Quiz colectivo sem login para a turma; gestão reservada ao funcionário. Inclui 10 perguntas fáceis sobre HTML e CSS, feedback, pontuação e mensagens por percentagem. O limite é de 10 perguntas guardadas, incluindo inactivas. A partida não guarda notas individuais.

Este é um projecto independente: tem código, base de dados e sessão próprios.
Pode ser aberto no VS Code e executado no XAMPP.

## Executar localmente

1. Coloca esta pasta em `C:\xampp\htdocs\nomedoprojecto`.
2. Inicia Apache e MySQL no XAMPP.
3. No phpMyAdmin, importa `base_de_dados.sql`, ou cola o seu conteúdo na aba SQL.
4. Abre `http://localhost/nomedoprojecto`.
5. Escolhe **Acesso do funcionário** e define a senha inicial antes de disponibilizar o sistema a outros utilizadores.

A base chama-se `cv5_quiz`. Não há senha de funcionário predefinida.
O acesso actual é único e partilhado; não há criação de várias contas individuais de funcionário.
Os nomes técnicos `formador/` e `$_SESSION['formador']` foram mantidos por compatibilidade com o código de origem.

## Configuração da base

`apoio/configuracao.exemplo.php` contém valores de desenvolvimento: 127.0.0.1, root e senha vazia.
Para usar outros valores, copia-o para `apoio/configuracao.local.php` e altera a cópia.
Essa configuração pessoal está no `.gitignore` e não deve ir para o GitHub.
Cada projecto usa uma base diferente; contas e acessos não são partilhados entre projectos.
Os dados da instalação integrada anterior não são transferidos automaticamente.

## Estrutura

- `index.php`: encaminha para a página inicial do sistema.
- `quiz/`: páginas e lógica do sistema.
- `formador/`: acesso e painel do funcionário.
- `apoio/`: sessão, ligação, permissões, cabeçalho e rodapé.
- `assets/` e `estilo.css`: identidade visual e imagens locais.
- `base_de_dados.sql`: estrutura e dados iniciais, sem contas reais.

O botão **Voltar à dashboard** fica depois do conteúdo e antes do rodapé, quando existe sessão iniciada.

## Colocar num repositório do GitHub

Cria um repositório para este sistema, por exemplo `instituto-foco-quiz`.
Coloca **o conteúdo desta pasta na raiz** do repositório: README.md, index.php, base_de_dados.sql e as subpastas.
Não envies apenas o ZIP; extrai primeiro e adiciona os ficheiros.
Inclui `.gitignore`, `.gitattributes` e `.htaccess`. Os ficheiros com ponto também fazem parte do projecto.
O GitHub guarda o código; a execução exige um ambiente com PHP e MySQL/MariaDB.

Exemplo de envio pelo terminal, depois de criar um repositório vazio:

```sh
git init
git add .
git commit -m "Versão inicial"
git branch -M main
git remote add origin URL_DO_TEU_REPOSITORIO
git push -u origin main
```

Substitui URL_DO_TEU_REPOSITORIO pelo endereço do repositório que criaste.
As senhas e os dados reais da base não devem ser incluídos no repositório.

## Utilização e limites

A turma responde sem conta. Cada acerto soma 10 pontos. O resultado usa a percentagem:
100%: Vocês são incríveis; 70% a menos de 100%: Foi por pouco;
40% a menos de 70%: Estão no bom caminho; abaixo de 40%: A descoberta começa aqui.
O funcionário cria, edita, activa/desactiva e remove perguntas. Alterações entram ao abrir ou recomeçar o quiz.
Não há contas de estudante, notas individuais, sorteio de perguntas ou histórico de tentativas.

Esta versão foi preparada para utilização local e apresentação pedagógica.
Usa senhas com hash, consultas preparadas e tokens nos formulários POST.


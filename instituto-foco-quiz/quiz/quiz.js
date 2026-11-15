// A lista vem dos blocos HTML que o PHP criou a partir da base de dados.
const perguntas = document.querySelectorAll('.pergunta-quiz');
const total = perguntas.length;
let actual = 0;
let pontos = 0;
let acertos = 0;
let respondeu = false;
let terminou = false;

function responder(opcao) {
    // Um clique repetido nunca soma pontos novamente.
    if (respondeu || terminou) {
        return;
    }
    respondeu = true;
    const pergunta = perguntas[actual];
    const feedback = pergunta.querySelector('.feedback');

    // data-correcta e data-explicacao foram preenchidos pelo PHP.
    if (opcao == pergunta.dataset.correcta) {
        acertos = acertos + 1;
        pontos = pontos + 10;
        feedback.className = 'feedback certa';
        feedback.textContent = 'Certo! ' + pergunta.dataset.explicacao;
    } else {
        feedback.className = 'feedback errada';
        feedback.textContent = 'A resposta correcta é ' + pergunta.dataset.correcta + '. ' + pergunta.dataset.explicacao;
    }

    const botoes = pergunta.querySelectorAll('.opcao');
    for (let i = 0; i < botoes.length; i++) {
        botoes[i].disabled = true;
    }
    document.getElementById('pontos').textContent = pontos;
    pergunta.querySelector('.seguinte').hidden = false;
}

function avancar() {
    if (!respondeu || terminou) {
        return;
    }
    perguntas[actual].hidden = true;
    actual = actual + 1;
    if (actual == total) {
        terminarQuiz();
    } else {
        respondeu = false;
        perguntas[actual].hidden = false;
        document.getElementById('etapa').textContent = (actual + 1) + ' / ' + total;
        perguntas[actual].querySelector('h2').focus();
    }
}

function terminarQuiz() {
    if (actual != total || terminou) {
        return;
    }
    terminou = true;
    const percentagem = (acertos / total) * 100;
    document.getElementById('etapa').textContent = 'CONCLUÍDO';
    document.getElementById('reiniciar').hidden = true;
    document.getElementById('percentagem-final').textContent = Math.round(percentagem);
    document.getElementById('acertos-finais').textContent = 'Acertaram ' + acertos + ' de ' + total + ' perguntas.';
    document.getElementById('pontos-finais').textContent = pontos + ' de ' + (total * 10) + ' pontos.';

    // A percentagem funciona com qualquer quantidade de 1 a 10 perguntas.
    // Arredondar apenas o número apresentado na tela.
    if (percentagem == 100) {
        document.getElementById('titulo-final').textContent = 'Vocês são incríveis!';
        document.getElementById('mensagem-final').textContent = 'Parabéns, turma! Acertaram todas. Que tal criar a vossa própria aplicação?';
    } else if (percentagem >= 70) {
        document.getElementById('titulo-final').textContent = 'Foi por pouco!';
        document.getElementById('mensagem-final').textContent = 'Muito bem! Estão perto de acertar todas. Continuem a explorar a programação web.';
    } else if (percentagem >= 40) {
        document.getElementById('titulo-final').textContent = 'Estão no bom caminho!';
        document.getElementById('mensagem-final').textContent = 'Já descobriram várias peças da programação. Cada tentativa ajuda a aprender.';
    } else {
        document.getElementById('titulo-final').textContent = 'A descoberta começa aqui!';
        document.getElementById('mensagem-final').textContent = 'Agora já conheceram as respostas. Experimentem outra vez: ninguém precisa de saber tudo para começar.';
    }
    document.getElementById('conclusao').hidden = false;
    document.getElementById('titulo-final').focus();
}
 
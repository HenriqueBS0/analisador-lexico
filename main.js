const onChangeEntrada = evento => {
    carregarTokens(evento.target.value.trim());
}

const carregarTokens = async entrada => {

    const tbody = document.getElementById('tbody-tokens');
    
    if(entrada == '') {
        tbody.innerHTML = '';
        return;
    }
    
    const resposta = await getTokens(entrada);

    if(!resposta.sucesso) {
        return;
    }

    tbody.innerHTML = '';

    resposta.conteudo.forEach(token => tbody.insertAdjacentElement('beforeend', criaLinhaTabelaTokens(token)));
}

const criaLinhaTabelaTokens = ({token, lexema, linha, posicaoInicial, posicaoFinal}) => {
    const tr = document.createElement('tr');

    tr.innerHTML = `<td>${token}</td><td>${lexema}</td><td>${linha}</td><td>${posicaoInicial}</td><td>${posicaoFinal}</td>`;

    return tr;
}

const getTokens = async entrada => {
    const formData = new FormData();

    formData.append('getTokens', true);
    formData.append('entrada',   entrada);

    const resposta = await fetch(document.URL, {
        method: 'POST',
        body: formData,
    });

    return await resposta.json();
}

const campoEntrada = document.getElementById('entrada'); 

campoEntrada.addEventListener('keyup', onChangeEntrada);
campoEntrada.addEventListener('change', onChangeEntrada);
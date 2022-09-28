function buscar_palavra() {
    var texto = document.getElementById('texto').textContent;
    texto = texto.split(' ');

    for (var i = 0; i < (texto.length-27); i++) {
        if(texto[i] == document.getElementById('buscar').value){
            document.getElementById('p'+(i-13)).classList.add('destaque');
        }
    }
}

function girar() {

    var transform = document.getElementById('card').style.transform;

    if(transform=="rotateY(180deg)") {
        document.getElementById('card').style.transform = "rotateY(0deg)";
    } else {
        document.getElementById('card').style.transform = "rotateY(180deg)";
    }
}

function traduzir(num) {
    document.getElementById('pal'+num).style.display = 'none';
    document.getElementById('tra'+num).style.display = 'inline';
}
function destraduzir(num) {
    document.getElementById('pal'+num).style.display = 'inline';
    document.getElementById('tra'+num).style.display = 'none';
}
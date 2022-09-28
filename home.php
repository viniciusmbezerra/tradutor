<?php

require 'vendor/autoload.php';
require_once "buscador.php";

// fazendo tradução
use Google\Cloud\Translate\V2\TranslateClient;

$translate = new TranslateClient([
    'key' => 'AIzaSyB5zSP4sicYHmGACUxe_HN5nyYeTtl6CZc'
]);

$result = $translate->translate($_POST['texto'], [
    'target' => 'pt'
]);

$b1 = new Buscador;
if(isset($_POST['texto'])) {
    $b1->__set('data', $_POST['texto']);
}

if(isset($_POST['palavra'])) {
    $b1->GerarSpan($_POST['palavra']);
} else {
    $b1->GerarSpan('');
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    
    <title>Buscador de palavras</title>
</head>

<body>

    <form action="" method="post" class="form_pesquisa">
        <input type="hidden" name="texto" value="<?=isset($_POST['texto'])?str_replace('"', "'", $_POST['texto']):''?>">
        <input type="text" name="palavra" class="buscar" id="buscar" placeholder="digite uma palavra" value="<?=isset($_POST['palavra'])?$_POST['palavra']:''?>">
        <button class="btn">Buscar</button>
    </form>

    <div class="brainstorming">
        <h2>Palavras que mais se repetem nesse texto</h2>
        <?php

        $b1->FazerBrainstorming();

        $vetor = $b1->__get('brainstorming');

        $maior = $vetor[0]['quant'];

        for ($i = 0; $i <= 20; $i++) {

            $fonts = [ 13, 16, 20, 25, 30, 40];
            $font = ($vetor[$i]['quant']/$maior)*100;

            if($font>=0 and $font<20) {
                $font = $fonts[0];
            } if($font>=20 and $font<40) {
                $font = $fonts[1];
            } if($font>=40 and $font<60) {
                $font = $fonts[2];
            } if($font>=60 and $font<80) {
                $font = $fonts[3];
            } if($font>=80 and $font<100) {
                $font = $fonts[4];
            } if($font>=100) {
                $font = $fonts[5];
            }

            if(isset($_POST['palavra']) and $_POST['palavra']==$vetor[$i]['palavra']) {
                $destaque = 'destaque';
            } else {
                $destaque = '';
            }

            $traducao = $translate->translate($vetor[$i]['palavra'], [
                'target' => 'pt'
            ]);

            echo "
                <span style='font-size: {$font}pt;' id='{$destaque}'>
                    <span class='palavra' id='pal{$i}' onmouseover='traduzir({$i})'>{$vetor[$i]['palavra']}</span>
                    <span class='traducao' id='tra{$i}' onmouseout='destraduzir({$i})'>{$traducao['text']}</span>
                    <span class='quantidade'>{$vetor[$i]['quant']}</span>
                </span>
                ";
        }

        ?>
    </div>
    
    <div class="card" id="card" ondblclick="girar()">

        <div class="pag pag-verso">
            <div class="dbclick">Clique duas vezes</div>
            <h2>Tradução</h2>
            <p id="texto">
                <?=$result['text']?>
            </p>
        </div>
            
        <div class="pag pag-frente">
            <div class="dbclick">Clique duas vezes</div>
            <h2>Texto</h2>
            <p id="texto">
                <?php 
                    foreach($b1->__get('data_span') as $palavra) {
                        echo ' '.$palavra;
                    }
                ?>
            </p>
        </div>
    </div>

    <script src="mov.js"></script>

</body>
</html>
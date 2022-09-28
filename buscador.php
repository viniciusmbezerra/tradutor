<?php

use LDAP\Result;

class Buscador {
    private $data;
    private $data_span;
    private $brainstorming;

    public function __construct() {}

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    public function GerarArray($atributo) {
        $this->__set($atributo, explode(' ', $this->__get('data')));
    }

    public function GerarSpan($palavra) {

        $this->GerarArray('data_span');

        $i = 0;
        foreach($this->__get('data_span') as $row) {

            $destaque = self::Refatoracao(strtolower($row)) == strtolower($palavra) ? "class='destaque'" : "";
            $data[$i] = "<span id='p".$i."' ".$destaque.">".$row."</span>";

            $i++;
        }
        $this->__set('data_span', $data);
    }

    public function FazerBrainstorming() {

        $this->GerarArray('data');

        $i = 0;
        $data = [];
        $aux = [];

        foreach( $this->__get('data') as $row ) {
            array_push($aux, self::Refatoracao(strtolower($row)));
        }

        foreach(array_unique($aux) as $row) {
            if(strlen($row)>3) {
                $quant = 0;
                foreach($this->__get('data') as $espelho) {

                    if( self::Refatoracao(strtolower($espelho)) == strtolower($row)) {
                        $quant++;
                    }
                }
                $data[$i]['palavra'] = $row;
                $data[$i]['quant'] = $quant;

                $i++;
            }
        }

        $quant = array();
        foreach ($data as $key => $row)
        {
            $quant[$key] = $row['quant'];
        }
        array_multisort($quant, SORT_DESC, $data);

        $this->__set('brainstorming', $data);

    }

    static public function Refatoracao($string) {

        $what = array( ' ','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º','.' );

        $by   = array( '','','','','','','','','','','','','','','','','','','','','','','','' );

        return str_replace($what, $by, $string);

    }

}

?>
<?php

abstract class MauCheiroColuna implements IMauCheiro
{
    var $coluna;
    
    function getColuna() {
        return $this->coluna;
    }

    function setColuna($coluna) {
        $this->coluna = $coluna;
    }
}

?>

<?php

abstract class MauCheiroIndice implements iMauCheiro
{
    var $indice;
    
    function getIndice() {
        return $this->indice;
    }

    function setIndice($indice) {
        $this->indice = $indice;
    }
}

?>

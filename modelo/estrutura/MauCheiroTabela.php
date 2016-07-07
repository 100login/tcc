<?php

abstract class MauCheiroTabela implements iMauCheiro
{
    var $tabela;
    
    function getTabela() {
        return $this->tabela;
    }

    function setTabela($tabela) {
        $this->tabela = $tabela;
    }
}

?>

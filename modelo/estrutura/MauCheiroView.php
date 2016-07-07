<?php

abstract class MauCheiroView implements MauCheiro
{
    var $view;
    
    function getView() {
        return $this->view;
    }

    function setView($view) {
        $this->view = $view;
    }
}

?>

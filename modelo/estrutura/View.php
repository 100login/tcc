<?php

class View {
    
    var $nome;
    var $definicao;
    var $dono;
    var $banco;
    var $mausCheirosView;
    var $viewDAO;
    
    function View($nome = "", $banco = null) {
        $this->nome = $nome;
        $this->banco = $banco;
    }
    
    function getNome() {
        return $this->nome;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function getDefinicao() {
        return $this->definicao;
    }

    function setDefinicao($definicao) {
        $this->definicao = $definicao;
    }
    
    function getDono() {
        return $this->dono;
    }

    function setDono($dono) {
        $this->dono = $dono;
    }

    function getBanco() {
        return banco;
    }

    function setBanco($banco) {
        $this->banco = $banco;
    }
 
    function getMauCheirosView() {
        return vetMauCheiroView;
    }

    function setMausCheirosView($mausCheirosView) {
        $this->mausCheirosView = $mausCheirosView;
    }

    public function getViewDAO() {
        return $this->viewDAO;
    }

    public function setViewDAO($viewDAO) {
        $this->viewDAO = $viewDAO;
    }
    
    public function lista(){
       return $this->viewDAO->lista();
    }
    
    function testa() {
        $resultado = "";

        foreach ($this->mausCheirosView as $mauCheiro) {
            $mauCheiro->setView($this);
            $resultado .= $mauCheiro->encontra();
        }

        return $resultado;
    }

    function refatora() {
        $resultado = "";

        foreach ($this->mausCheirosView as $mauCheiro) {
            $mauCheiro->setView($this);
            $resultado .= $mauCheiro->refatora();
        }

        return $resultado;
    }
}

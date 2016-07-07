<?php

class Indice {
    
    var $nome;
    var $tabela;
    var $colunas;
    var $ehUnico;
    var $mausCheirosIndice;
    
    private $indiceDAO;
    
    function Indice($nome = "", $tabela = null) {
        $this->nome = $nome;
        $this->tabela = $tabela;
    }

    function getNome() {
        return $this->nome;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function getTabela() {
        return $tabela;
    }

    function setTabela($tabela) {
        $this->tabela = $tabela;
    }
    
    function getColunas() {
        return $this->colunas;
    }

    function setColunas($colunas) {
        $this->colunas = $colunas;
    }
    
    function getEhUnico() {
        return $this->ehUnico;
    }

    function setEhUnico($ehUnico) {
        $this->ehUnico = $ehUnico;
    }
    
    function getMausCheirosIndice() {
        return $this->mausCheirosIndice;
    }
    
    function setMausCheirosIndice($mausCheirosIndice) {
        $this->mausCheirosIndice = $mausCheirosIndice;
    }
    
    public function getIndiceDAO() {
        return $this->indiceDAO;
    }

    public function setIndiceDAO($indiceDAO) {
        $this->indiceDAO = $indiceDAO;
    }
   
    public function lista(){
       return $this->indiceDAO->lista($this->tabela);
    }
    
    function testa() {
        $resultado = "";

        foreach ($this->mausCheirosIndice as $mauCheiro) {
            $mauCheiro->setIndice($this);
            $resultado .= $mauCheiro->encontra();
        }

        return $resultado;
    }

    function refatora() {
        $resultado = "";

        foreach ($this->mausCheirosIndice as $mauCheiro) {
            $mauCheiro->setIndice($this);
            $resultado .= $mauCheiro->refatora();
        }

        return $resultado;
    }
}

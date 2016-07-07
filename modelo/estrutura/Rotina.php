<?php

class Rotina {
    
    var $nome;
    var $definicao;
    var $retorno;
    var $argumentos;
    var $volatilidade;
    var $dono;
    var $linguagem;
    var $banco;
    
    function Rotina($nome = "", $banco = null) {
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
    
    function getRetorno() {
        return $this->retorno;
    }

    function setRetorno($retorno) {
        $this->retorno = $retorno;
    }
    
    function getArgumentos() {
        return $this->argumentos;
    }

    function setArgumentos($argumentos) {
        $this->argumentos = $argumentos;
    }
    
    function getVolatilidade() {
        return $this->volatilidade;
    }

    function setVolatilidade($volatilidade) {
        $this->volatilidade = $volatilidade;
    }
    
    function getDono() {
        return $this->dono;
    }

    function setDono($dono) {
        $this->dono = $dono;
    }
    
    function getLinguagem() {
        return $this->linguagem;
    }

    function setLinguagem($linguagem) {
        $this->linguagem = $linguagem;
    }

    function getBanco() {
        return banco;
    }

    function setBanco($banco) {
        $this->banco = $banco;
    }

    public function getRotinaDAO() {
        return $this->rotinaDAO;
    }

    public function setRotinaDAO($rotinaDAO) {
        $this->rotinaDAO = $rotinaDAO;
    }
    
    public function lista($trigger = ""){
       return $this->rotinaDAO->lista($trigger);
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

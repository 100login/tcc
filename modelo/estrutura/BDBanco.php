<?php

class BDBanco {
    
    private $idBanco;
    private $nome;
    private $nomeUsuario;
    private $senha;
    private $porta;
    private $host;
    private $padraoChavePrimaria;
    private $padraoChaveEstrangeira;
    private $quantidadeMemoria;
    private $usuario;
    private $bdBancoDAO;
    
    public function BDBanco($idBanco = null, $nome = "", $nomeUsuario = "", $senha = "", $porta = "", $host = "", $usuario = null) {
        $this->idBanco = $idBanco;
        $this->nome = $nome;
        $this->nomeUsuario = $nomeUsuario;
        $this->senha = $senha;
        $this->porta = $porta;
        $this->host = $host;
        $this->usuario = $usuario;
        $this->bdBancoDAO = new BDBancoDAO("tads_refactor", "postgres", "123456", "localhost", "5432");
    }
    
    public function getIdBanco() {
        return $this->idBanco;
    }

    public function setIdBanco($idBanco) {
        $this->idBanco = $idBanco;
    }
    
    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }
    
    public function getNomeUsuario() {
        return $this->nomeUsuario;
    }

    public function setNomeUsuario($nomeUsuario) {
        $this->nomeUsuario = $nomeUsuario;
    }
    
    public function getSenha() {
        return $this->senha;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }
    
    public function getPorta() {
        return $this->porta;
    }
    
    public function setPorta($porta) {
        $this->porta = $porta;
    }
    
    public function getHost() {
        return $this->host;
    }
    
    public function setHost($host) {
        $this->host = $host;
    }

    public function getPadraoChavePrimaria() {
        return $this->padraoChavePrimaria;
    }
    
    public function setPadraoChavePrimaria($padraoChavePrimaria) {
        $this->padraoChavePrimaria = $padraoChavePrimaria;
    }
    
    public function getPadraoChaveEstrangeira() {
        return $this->padraoChaveEstrangeira;
    }
    
    public function setPadraoChaveEstrangeira($padraoChaveEstrangeira) {
        $this->padraoChaveEstrangeira = $padraoChaveEstrangeira;
    }
    
    public function getQuantidadeMemoria() {
        return $this->quantidadeMemoria;
    }
    
    public function setQuantidadeMemoria($quantidadeMemoria) {
        $this->quantidadeMemoria = $quantidadeMemoria;
    }
    
    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }
    
    public function getBancoDAO() {
        return $this->bdBancoDAO;
    }

    public function setBancoDAO($bdBancoDAO) {
        $this->bdBancoDAO = $bdBancoDAO;
    }
    
    public function carregaBanco($idBanco) {
        return $this->bdBancoDAO->carregaBanco($idBanco,$this);
    }
    
    public function lista() {
        return $this->bdBancoDAO->lista($this);
    }
    
    public function salvar() {
        return $this->bdBancoDAO->salvar($this);
    }
    
    public function deletar() {
        return $this->bdBancoDAO->deletar($this);
    }
}

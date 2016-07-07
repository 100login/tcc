<?php

class BDMauCheiro {
    
    private $idMauCheiro;
    private $nomeClasse;
    private $descricao;
    private $tipo;
    private $usuario;
    private $bdMauCheiroDAO;
    
    public function BDMauCheiro($idMauCheiro = null, $nomeClasse = "", $descricao = "", $tipo = "", $usuario = null) {
        $this->idMauCheiro = $idMauCheiro;
        $this->usuario = $usuario;
        $this->nomeClasse = $nomeClasse;
        $this->descricao = $descricao;
        $this->tipo = $tipo;
        $this->bdMauCheiroDAO = new BDMauCheiroDAO("tads_refactor", "postgres", "123456", "localhost", "5432");
    }
    
    public function getIdMauCheiro() {
        return $this->idMauCheiro;
    }

    public function setIdMauCheiro($idMauCheiro) {
        $this->idMauCheiro = $idMauCheiro;
    }
    
    public function getNomeClasse() {
        return $this->nomeClasse;
    }

    public function setNomeClasse($nomeClasse) {
        $this->nomeClasse = $nomeClasse;
    }
    
    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }
    
    public function getTipo() {
        return $this->tipo;
    }
    
    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }
    
    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }
    
    public function lista() {
        return $this->bdMauCheiroDAO->lista($this->usuario);
    }
    
    public function listaWhere($where) {
        return $this->bdMauCheiroDAO->listaWhere($where);
    }
    
    public function carregaMauCheiro($nomeColuna,$valorColuna) {
        return $this->bdMauCheiroDAO->carregaMauCheiro($nomeColuna,$valorColuna,$this);
    }
    
    public function salvar() {
        return $this->bdMauCheiroDAO->salvar($this);
    }
 
    public function deletar() {
        if($this->bdMauCheiroDAO->deletar($this)){
            if(unlink(DIR_MAU_CHEIRO_USUARIO.$this->getNomeClasse().".php")) return true;
        }
        return false;
    }
}

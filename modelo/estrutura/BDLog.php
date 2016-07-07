<?php

class BDLog{
    
    private $idLog;
    private $log;
    private $erro;
    private $data;
    private $banco;
    private $mauCheiro;
    private $bdLogDAO;
    
    public function BDLog($idLog = null, $log = "", $erro = "", $data = "", $banco = null, $mauCheiro = null) {
        $this->idLog = $idLog;
        $this->log = $log;
        $this->erro = $erro;
        $this->data = $data;
        $this->banco = $banco;
        $this->mauCheiro = $mauCheiro;
        $this->bdLogDAO = new BDLogDAO("tads_refactor", "postgres", "123456", "localhost", "5432");
    }
    
    public function getIdLog() {
        return $this->idLog;
    }

    public function setIdLog($idLog) {
        $this->idLog = $idLog;
    }
    
    public function getLog() {
        return $this->log;
    }

    public function setLog($log) {
        $this->log = $log;
    }
    
    public function getErro() {
        return $this->erro;
    }

    public function setErro($erro) {
        $this->erro = $erro;
    }
    
    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
    }
    
    public function getBanco() {
        return $this->banco;
    }
    
    public function setBanco($banco) {
        $this->banco = $banco;
    }
    
    public function getMauCheiro() {
        return $this->mauCheiro;
    }
    
    public function setMauCheiro($getMauCheiro) {
        $this->mauCheiro = $getMauCheiro;
    }
    
    public function getLogDAO() {
        return $this->bdLogDAO;
    }

    public function setLogDAO($bdLogDAO) {
        $this->bdLogDAO = $bdLogDAO;
    }
    
    public function carregaLog($idLog) {
        return $this->bdLogDAO->carregaLog($idLog,$this);
    }
    
    public function lista() {
        return $this->bdLogDAO->lista($this);
    }
    
    public function salvar() {
        return $this->bdLogDAO->salvar($this);
    }
}

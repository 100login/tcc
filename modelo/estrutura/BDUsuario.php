<?php

class BDUsuario {
    
    private $idUsuario;
    private $login;
    private $senha;
    private $bancos;
    private $mausCheiros;
    private $bdUsuarioDAO;
    
    public function BDUsuario($idUsuario = null, $login = "", $senha = "") {
        $this->idUsuario = $idUsuario;
        $this->login = $login;
        $this->senha = $senha;
        $this->bdUsuarioDAO = new BDUsuarioDAO("tads_refactor", "postgres", "123456", "localhost", "5432");
    }
    
    public function getidUsuario() {
        return $this->idUsuario;
    }

    public function setidUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }
    
    public function getLogin() {
        return $this->login;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }
    
    public function getBancos() {
        return $this->bancos;
    }

    public function setBancos($bancos) {
        $this->bancos = $bancos;
    }

    public function getMausCheiros() {
        return $this->mausCheiros;
    }

    public function setMausCheiros($mausCheiros) {
        $this->mausCheiros = $mausCheiros;
    }
    
    public function getUsuarioDAO() {
        return $this->bdUsuarioDAO;
    }

    public function setUsuarioDAO($bdUsuarioDAO) {
        $this->bdUsuarioDAO = $bdUsuarioDAO;
    }
    
    public function carregaUsuario($nomeColuna,$valorColuna){
        $this->bdUsuarioDAO->carregaUsuario($nomeColuna,$valorColuna,$this);
    }    
    
    public function salvar() {
        return $this->bdUsuarioDAO->salvar($this);
    }

    public function logar() {
        if($this->bdUsuarioDAO->carregaUsuarioWhere("WHERE u.ds_login = '".$this->login."' AND u.ds_senha = '".md5($this->senha)."'",$this)){
            $_SESSION['idUsuario'] = $this->getidUsuario();
            return true;
        }
        return false;
    }
    
    public function logout() {
        return session_destroy();
    }
}

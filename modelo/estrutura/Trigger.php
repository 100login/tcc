<?php

class Trigger {
    
    var $nome;
    var $oid;
    var $rotinas;
    var $tabela;
    
    function Trigger($nome = "", $tabela = null) {
        $this->nome = $nome;
        $this->tabela = $tabela;
    }
    
    function getNome() {
        return $this->nome;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }
    
    function getOid() {
        return $this->oid;
    }

    function setOid($oid) {
        $this->oid = $oid;
    }

    function getRotinas() {
        return $this->rotinas;
    }

    function setRotinas($rotinas) {
        $this->rotinas = $rotinas;
    }
    
    function getTabela() {
        return $this->tabela;
    }

    function setTabela($tabela) {
        $this->tabela = $tabela;
    }

    public function getTriggerDAO() {
        return $this->triggerDAO;
    }

    public function setTriggerDAO($triggerDAO) {
        $this->triggerDAO = $triggerDAO;
    }
    
    public function lista(){
       return $this->triggerDAO->lista($this->tabela);
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

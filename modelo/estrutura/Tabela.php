<?php

class Tabela {
    
    private $nome;
    private $schema;
    private $tamanho;
    private $banco;
    private $colunas;
    private $indices;
    private $triggers;
    private $viewsDependentes;
    private $dados;
    private $mausCheirosTabela;
    private $tabelaDAO;
    
    public function Tabela($nome = "", $banco = null) {
        $this->nome = $nome;
        $this->banco = $banco;
    }

    public function getSchema() {
        return $this->schema;
    }

    public function setSchema($schema) {
        $this->schema = $schema;
    }
    
    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }
    public function getTamanho() {
        return $this->tamanho;
    }
    public function setTamanho($tamanho) {
        $this->tamanho = $tamanho;
    }
    public function getBanco() {
        return $this->banco;
    }

    public function setBanco($banco) {
        $this->banco = $banco;
    }

    public function getColunas() {
        return $this->colunas;
    }

    public function setColunas($colunas) {
        $this->colunas = $colunas;
    }
    
    public function getIndices() {
        return $this->indices;
    }

    public function setIndices($indices) {
        $this->indices = $indices;
    }
    
    public function getTriggers() {
        return $this->triggers;
    }

    public function setTriggers($triggers) {
        $this->triggers = $triggers;
    }

    public function getViewsDependentes() {
        return $this->vewsDepdendentes;
    }

    public function setViewsDependentes($viewsDependentes) {
        $this->vewsDepdendentes = $viewsDependentes;
    }
    
    public function getDados() {
        return $this->dados;
    }

    public function setDados($dados) {
        $this->dados = $dados;
    }
    
    public function getMauCheirosTabela() {
        return $this->mausCheirosTabela;
    }

    public function setMausCheirosTabela($mausCheirosTabela) {
        $this->mausCheirosTabela = $mausCheirosTabela;
    }
    
   public function getTabelaDAO() {
        return $this->TabelaDAO;
    }

    public function setTabelaDAO($tabelaDAO) {
        $this->tabelaDAO = $tabelaDAO;
    }
    
    public function lista(){
       return $this->tabelaDAO->lista($this->banco);
    }
    
    public function getColunaPorNome($nome){
        foreach ($this->getColunas() as $coluna) {
            if($nome == $coluna->getNome()){
                return $coluna;
            }
        }
    }
    
    public function obtemValoresMaisFrequnetes($coluna){
        $dados = array();
        foreach ($this->getDados() as $linha) {
            if($linha[$coluna->getNome()]) $dados[$linha[$coluna->getNome()]]++;
        }
        arsort($dados);
        return $dados;
    }
    
    public function verificaValorExisteColuna($coluna,$valor){
        foreach ($this->getDados() as $linha) {
            if($linha[$coluna->getNome()] == $valor) return true;
        }
        return false;
    }
    
    public function testa() {
        $resultado = "";

        foreach ($this->mausCheirosTabela as $mauCheiro) {
            $mauCheiro->setTabela($this);
            $resultado .= $mauCheiro->encontra();
        }

        return $resultado;
    }

    public function refatora() {
        $resultado = "";

        foreach ($this->mausCheirosTabela as $mauCheiro) {
            $mauCheiro->setTabela($this);
            $resultado .= $mauCheiro->refatora();
        }

        return $resultado;
    }
}

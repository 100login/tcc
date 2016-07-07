<?php

class Coluna {
    
    var $nome;
    var $tabela;
    var $ehSerial;
    var $natureza;
    var $tipo;
    var $ehNumerico;
    var $ehNulo;
    var $posicao;
    var $chavePrimaria;
    var $nomeChaveEstrangeira;
    var $definicaoChaveEstrangeira;
    var $nomeTabelaReferencia;
    var $nomeColunaReferencia;
    var $mausCheirosColuna;
    private $colunaDAO;
    
    function Coluna($nome = "", $tabela = null) {
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
        return $this->tabela;
    }

    function setTabela($tabela) {
        $this->tabela = $tabela;
    }
    
    function getEhSerial() {
        return $this->ehSerial;
    }

    function setEhSerial($ehSerial) {
        $this->ehSerial = $ehSerial;
    }
 
    function getNatureza() {
        return $this->natureza;
    }
    
    function setNatureza($natureza) {
        $this->natureza = $natureza;
    }
    
    function getTipo() {
        return $this->tipo;
    }
    
    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function getEhNumerico() {
        return $this->ehNumerico;
    }
    
    function setEhNumerico($ehNumerico) {
        $this->ehNumerico = $ehNumerico;
    }
    
    function getEhNulo() {
        return $this->ehNulo;
    }
    
    function setEhNulo($ehNulo) {
        $this->ehNulo = $ehNulo;
    }
    
    function getPosicao() {
        return $this->posicao;
    }
    
    function setPosicao($posicao) {
        $this->posicao = $posicao;
    }
    
    function getChavePrimaria() {
        return $this->chavePrimaria;
    }
    
    function setChavePrimaria($chavePrimaria) {
        $this->chavePrimaria = $chavePrimaria;
    }
    
    function getNomeChaveEstrangeira() {
        return $this->nomeChaveEstrangeira;
    }
    
    function setNomeChaveEstrangeira($nomeChaveEstrangeira) {
        $this->nomeChaveEstrangeira = $nomeChaveEstrangeira;
    }
    
    function getDefinicaoChaveEstrangeira() {
        return $this->definicaoChaveEstrangeira;
    }
    
    function setDefinicaoChaveEstrangeira($definicaoChaveEstrangeira) {
        $this->definicaoChaveEstrangeira = $definicaoChaveEstrangeira;
    }
    
    function getNomeTabelaReferencia() {
        return $this->nomeTabelaReferencia;
    }
    
    function setNomeTabelaReferencia($nomeTabelaReferencia) {
        $this->nomeTabelaReferencia = $nomeTabelaReferencia;
    }
    
    function getNomeColunaReferencia() {
        return $this->nomeColunaReferencia;
    }
    
    function setNomeColunaReferencia($nomeColunaReferencia) {
        $this->nomeColunaReferencia = $nomeColunaReferencia;
    }
    
    function getMausCheirosColuna() {
        return $this->mausCheirosColuna;
    }
    
    function setMausCheirosColuna($mausCheirosColuna) {
        $this->mausCheirosColuna = $mausCheirosColuna;
    }
    
   public function getColunaDAO() {
        return $this->colunaDAO;
    }

    public function setColunaDAO($colunaDAO) {
        $this->colunaDAO = $colunaDAO;
    }
    
    public function lista(){
       return $this->colunaDAO->lista($this->tabela);
    }
    
    public function getValorTratado($valor){
       return $this->getEhNumerico()?$valor:"'".$valor."'";
    }
    
    public function getPrimeiroValorEncontrado(){
        foreach ($this->getTabela()->getDados() as $linha) {
            if($linha[$this->getNome()]) return $linha[$this->getNome()];
        }
        return null;
    }
    
    public function getPorcentagemCorrespondente($coluna){
        $qtdeNaoCorresponde = 0;
        foreach ($this->getTabela()->getDados() as $linhaCorrente) {
            $vetDadosColunaCorrente[] = $linhaCorrente[$this->getNome()];
        }
        
        $vetDadosColunaCorrente = array_unique($vetDadosColunaCorrente);
        
        foreach ($vetDadosColunaCorrente as $indice => $valor) {
            $temCorrespondente = false;
            foreach ($coluna->getTabela()->getDados() as $linhaParametro) {
                if($valor == $linhaParametro[$coluna->getNome()]) {
                    $temCorrespondente = true;
                    break;
                }
            }
            if(!$temCorrespondente) $qtdeNaoCorresponde++;
        }

        return (number_format((100-((100*$qtdeNaoCorresponde)/count($vetDadosColunaCorrente)))));
       
    }
    
    function testa() {
        $resultado = "";

        foreach ($this->mausCheirosColuna as $mauCheiro) {
            $mauCheiro->setColuna($this);
            $resultado .= $mauCheiro->encontra();
        }

        return $resultado;
    }

    function refatora() {
        $resultado = "";

        foreach ($this->mausCheirosColuna as $mauCheiro) {
            $mauCheiro->setColuna($this);
            $resultado .= $mauCheiro->refatora();
        }

        return $resultado;
    }
}

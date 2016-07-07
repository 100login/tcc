<?php

class Banco {
    private $nome;
    private $tabelas;
    private $views;
    private $rotinas;
    private $bancoDAO;
    
    public function Banco($nome, $usuario, $senha, $porta, $host) {
        $this->nome = $nome;
        $this->bancoDAO = new BancoDAO($nome, $usuario, $senha, $porta,$host);
        $this->carregaBanco($nome, $usuario, $senha, $porta, $host);
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }
    
    public function getTabelas() {
        return $this->tabelas;
    }

    public function setTabelas($tabelas) {
        $this->tabelas = $tabelas;
    }
    
    public function getViews() {
        return $this->views;
    }

    public function setViews($views) {
        $this->views = $views;
    }
  
    public function getRotinas() {
        return $this->rotinas;
    }

    public function setRotinas($rotinas) {
        $this->rotinas = $rotinas;
    }
    
    public function getBancoDAO() {
        return $this->bancoDAO;
    }

    public function setBancoDAO($bancoDAO) {
        $this->bancoDAO = $bancoDAO;
    }
    
    private function carregaBanco($nome, $usuario, $senha, $host, $porta) {
        $view = new View();
        $view->setViewDAO(new ViewDAO($nome, $usuario, $senha, $host, $porta));
        $this->views = $view->lista();
        $tabela = new Tabela('',$this);
        $tabela->setTabelaDAO(new TabelaDAO($nome, $usuario, $senha, $host, $porta));
        $this->tabelas = $tabela->lista();
        $rotina = new Rotina();
        $rotina->setRotinaDAO(new RotinaDAO($nome, $usuario, $senha, $host, $porta));
        $this->rotinas = $rotina->lista();
    }
    
    public function getTabelaPorNome($nome){
        foreach ($this->gettabelas() as $tabela) {
            if($nome == $tabela->getNome()){
                return $tabela;
            }
        }
    }
    
    public function encontra($id_mau_cheiro) {
        /*
        $mausCheiros[] = new IndiceComColunaNula();
        $mausCheiros[] = new TabelaTodosCamposAnulaveis();
        $mausCheiros[] = new ChaveEstrangeiraDiferentePrimaria();
        $mausCheiros[] = new ColunaSemChaveEstrangeira();
        $mausCheiros[] = new NomeTabelaPalavraReservada();
        
        $mausCheiros[] = new ChavePrimariaOrdenacao();
        $mausCheiros[] = new TabelaSemChavePrimaria();
        $mausCheiros[] = new TabelaMultiUso();
        
        $mausCheiros[] = new TabelaSemRegistro();
        $mausCheiros[] = new TabelaMuitasLinhas();;*/
     
        $mausCheiros[] = new ChaveEstrangeiraSemIndice();
        
        foreach ($mausCheiros as $mauCheiro) {
            echo $mauCheiro->encontra($this);
        }
    }
    
    public function refatora($sql, $bdLog) {
        return $this->bancoDAO->refatora($sql, $bdLog);
    }
}

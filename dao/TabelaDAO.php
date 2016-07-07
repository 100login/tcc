<?php

class TabelaDAO extends Database{
    
    public function lista($banco){
       $sql = "SELECT 
                    tablename,schemaname, pg_table_size(schemaname||'.\"'||tablename||'\"')  
               FROM 
                    pg_catalog.pg_tables
               WHERE 
                    schemaname = 'public'  
               ORDER BY 
                    tablename;";
       
       $recurso =  pg_query($this->dbc, $sql);
       $tabelas = array();
       while ($regTabela = pg_fetch_assoc($recurso)) {
           $tabela = new Tabela($regTabela['tablename'],$banco);
           $tabela->setSchema($regTabela['schemaname']);
           $tabela->setTamanho($regTabela['pg_table_size']);
           $coluna = new Coluna("",$tabela);
           $coluna->setColunaDAO(new ColunaDAO($this->nome, $this->usuario, $this->senha, $this->host, $this->porta));
           $tabela->setColunas($coluna->lista());
           $indice = new Indice("",$tabela);
           $indice->setIndiceDAO(new IndiceDAO($this->nome, $this->usuario, $this->senha, $this->host, $this->porta));
           $tabela->setIndices($indice->lista());
           $trigger = New Trigger("",$tabela);
           $trigger->setTriggerDAO(new TriggerDAO($this->nome, $this->usuario, $this->senha, $this->host, $this->porta));
           $tabela->setTriggers($trigger->lista());
           $tabela->setViewsDependentes($this->carregaViewsDependentes($tabela));
           $tabela->setDados($this->carregaInformacoes($tabela));
           $tabelas[] = $tabela;
       }
       
       
       
       return $tabelas;
    }
    
    public function carregaInformacoes($tabela){
        $sql = "SELECT * FROM \"".$tabela->getNome()."\"";
        $recurso =  pg_query($this->dbc, $sql);
         $dados = array();
        while ($regDados = pg_fetch_assoc($recurso)) {
            $dados[] = $regDados;
        }
 
        return $dados;
        
    }
    
    public function carregaViewsDependentes($tabela){
        
        $sql = "SELECT distinct dependente.relname 
                FROM pg_depend d
                JOIN pg_rewrite r ON d.objid = r.oid 
                JOIN pg_class as dependente ON r.ev_class = dependente.oid 
                JOIN pg_class as origem ON d.refobjid = origem.oid 
                JOIN pg_attribute a ON d.refobjid = a.attrelid 
                    AND d.refobjsubid = a.attnum 
                WHERE a.attnum > 0 AND origem.relname = '".$tabela->getNome()."'";

        $recurso =  pg_query($this->dbc, $sql);
        $dependencias = array();
        while ($regDependencia = pg_fetch_assoc($recurso)) {
            foreach ($tabela->getBanco()->getViews() as $view) {
                if($view->getNome() == $regDependencia['relname']){
                    $dependencias[] = $view;
                }
            }
        }
 
        return $dependencias;
        
    }
}

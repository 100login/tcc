<?php

class TriggerDAO extends Database{
    
    public function lista($tabela){
       $sql = "SELECT 
                        tg.tgname, tg.tgfoid
                FROM 
                        pg_catalog.pg_trigger tg 
                INNER JOIN pg_catalog.pg_class c ON (tg.tgrelid=c.oid)
                INNER JOIN pg_catalog.pg_tables t ON (concat('public.','\"',c.relname,'\"')::regclass = concat('public.','\"',t.tablename,'\"')::regclass)
                WHERE t.schemaname = 'public' AND tg.tgisinternal = 'f' AND t.tablename = '".$tabela->getNome()."' AND c.relname = '".$tabela->getNome()."'";
       
       $recurso =  pg_query($this->dbc, $sql);
       $triggers = array();
       while ($regTrigger = pg_fetch_assoc($recurso)) {
           $trigger = new Trigger($regTrigger['tgname']);
           $trigger->setOid($regTrigger['tgfoid']);
           $rotina = new Rotina();
           $rotina->setRotinaDAO(new RotinaDAO($this->nome, $this->usuario, $this->senha, $this->host, $this->porta));
           $trigger->setRotinas($rotina->lista($trigger));
           $triggers[] = $trigger;
       }
       
       return $triggers;
    }
}

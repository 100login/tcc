<?php

class RotinaDAO extends Database{
    
    public function lista($trigger = ""){
  
            $sql = "SELECT 
                        p.proname as routine_name,
                        pg_catalog.pg_get_function_result(p.oid) as return,
                        pg_catalog.pg_get_function_arguments(p.oid) as arguments,
                        CASE
                        WHEN p.proisagg THEN 'agg'
                        WHEN p.proiswindow THEN 'window'
                        WHEN p.prorettype = 'pg_catalog.trigger'::pg_catalog.regtype THEN 'trigger'
                        ELSE 'normal'
                        END as type,
                        CASE
                        WHEN p.provolatile = 'i' THEN 'immutable'
                        WHEN p.provolatile = 's' THEN 'stable'
                        WHEN p.provolatile = 'v' THEN 'volatile'
                        END as volatility,
                        pg_catalog.pg_get_userbyid(p.proowner) as owner,
                        l.lanname as external_language,
                        p.prosrc as routine_definition
                FROM pg_catalog.pg_proc p
                        LEFT JOIN pg_catalog.pg_namespace n ON n.oid = p.pronamespace
                        LEFT JOIN pg_catalog.pg_language l ON l.oid = p.prolang
                WHERE pg_catalog.pg_function_is_visible(p.oid)
                        AND n.nspname <> 'pg_catalog'
                        AND n.nspname <> 'information_schema'
                        ".($trigger?" AND p.oid = ".$trigger->getOid():"");
            //AND p.prorettype <> 'pg_catalog.trigger'::pg_catalog.regtype
        
       $recurso =  pg_query($this->dbc, $sql);
       $rotinas = array();
       while ($regRotina = pg_fetch_assoc($recurso)) {
           $rotina = new Rotina($regRotina['routine_name']);
           $rotina->setDefinicao($regRotina['routine_definition']);
           $rotina->setRetorno($regRotina['return']);
           $rotina->setArgumentos($regRotina['arguments']);
           $rotina->setVolatilidade($regRotina['volatility']);
           $rotina->setDono($regRotina['owner']);
           $rotina->setLinguagem($regRotina['external_language']);
           $rotinas[] = $rotina;
       }
       
       return $rotinas;
    }
}

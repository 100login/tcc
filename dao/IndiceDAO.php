<?php

class IndiceDAO extends Database{
    
    public function lista($tabela){
       $sql = "SELECT
                    i.relname AS nome_indice,
                    a.attname AS nome_coluna,
                    CASE WHEN ix.indisunique = 't' THEN 1 ELSE 0 END as unico
               FROM
                    pg_class t,
                    pg_class i,
                    pg_index ix,
                    pg_attribute a
               WHERE
                    t.oid = ix.indrelid
                    AND i.oid = ix.indexrelid
                    AND a.attrelid = t.oid
                    AND a.attnum = ANY(ix.indkey)
                    AND t.relkind = 'r' AND t.relname = '".$tabela->getNome()."'
               ORDER BY
                    t.relname,
                    i.relname;";
     
       $recurso =  pg_query($this->dbc, $sql);
       $regIndiceAnt = null;
       $indice = null;
       $indices = array();
       $i=0;
       while ($regIndice = pg_fetch_assoc($recurso)) {
           if($regIndice['nome_indice'] != $regIndiceAnt['nome_indice']){
               if($indice) $indices[] = $indice;
               
               $indice = new Indice($regIndice['nome_indice'],$tabela);
               $indice->setEhUnico($regIndice['unico']);
               
               $colunas = array();
           }
           
           foreach ($tabela->getColunas() as $coluna) {
               if($coluna->getNome() == $regIndice['nome_coluna']){
                   $colunas = $indice->getColunas();
                   $colunas[] = $coluna;
                   $indice->setColunas($colunas);
               }
           }
           
           $regIndiceAnt = $regIndice;
       }
  
       $indices[] = $indice;

       return $indices;
    }
}
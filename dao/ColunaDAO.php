<?php

class ColunaDAO extends Database{
    
    public function lista($tabela){
       $sql = "SELECT 
                    c.column_name,POSITION ('nextval' IN c.column_default) as serial,CASE WHEN c.is_nullable='YES' THEN 1 ELSE 0 END as is_nullable,c.ordinal_position,
                    CASE WHEN c.numeric_precision IS NOT NULL THEN 1 
                         WHEN c.character_octet_length IS NOT NULL THEN 2 
                         WHEN c.datetime_precision IS NOT NULL THEN 3 
                         ELSE 0 END natureza, 
                    pg_catalog.format_type(a.atttypid, a.atttypmod) as tipo, CASE WHEN c.numeric_precision IS NOT NULL THEN 1 ELSE 0 END is_numeric,
                    (
                        SELECT               
                            pa.attrelid 
                        FROM 
                            pg_index pi, pg_class pc, pg_attribute pa 
                        WHERE 
                            pc.oid = concat('\"','".$tabela->getNome()."','\"')::regclass AND
                            indrelid = pc.oid AND
                            pa.attrelid = pc.oid AND 
                            pa.attnum = any(pi.indkey)
                            AND pi.indisprimary and pa.attname = c.column_name) as chave_primaria,
                            clf.relname AS tabela_ref, af.attname AS atributo_ref,
                            ct.conname, pg_catalog.pg_get_constraintdef(ct.oid, true)
                FROM 
                    information_schema.columns c
                LEFT JOIN pg_catalog.pg_attribute a ON (a.attname = c.column_name AND 
                concat('\"',c.table_name,'\"')::regclass = a.attrelid)
                LEFT JOIN pg_catalog.pg_class cl ON (a.attrelid = cl.oid AND cl.relkind = 'r')
                LEFT JOIN pg_catalog.pg_namespace n ON (n.oid = cl.relnamespace)   
                LEFT JOIN pg_catalog.pg_constraint ct ON (a.attrelid = ct.conrelid AND   
                     ct.confrelid != 0 AND ct.conkey[1] = a.attnum)   
                LEFT JOIN pg_catalog.pg_class clf ON (ct.confrelid = clf.oid AND clf.relkind = 'r')
                LEFT JOIN pg_catalog.pg_namespace nf ON (nf.oid = clf.relnamespace)   
                LEFT JOIN pg_catalog.pg_attribute af ON (af.attrelid = ct.confrelid AND   
                     af.attnum = ct.confkey[1])
                WHERE 
                    c.table_name = '".$tabela->getNome()."'
                ORDER BY
                    c.ordinal_position";

       $recurso =  pg_query($this->dbc, $sql);
       $colunas = array();
       while ($regColuna = pg_fetch_assoc($recurso)) {
           $coluna = new Coluna($regColuna['column_name'],$tabela);
           $coluna->setEhSerial($regColuna['serial']);
           $coluna->setNatureza($regColuna['natureza']);
           $coluna->setTipo($regColuna['tipo']);
           $coluna->setEhNumerico($regColuna['is_numeric']);
           $coluna->setEhNulo($regColuna['is_nullable']);
           $coluna->setPosicao($regColuna['ordinal_position']);
           $coluna->setChavePrimaria($regColuna['chave_primaria']);
           $coluna->setNomeChaveEstrangeira($regColuna['conname']);
           $coluna->setDefinicaoChaveEstrangeira($regColuna['pg_get_constraintdef']);
           $coluna->setNomeTabelaReferencia($regColuna['tabela_ref']);
           $coluna->setNomeColunaReferencia($regColuna['atributo_ref']);
           $colunas[] = $coluna;
       }
       return $colunas;
    }
}

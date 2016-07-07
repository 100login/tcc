<?php

class NomeTabelaPalavraReservada extends MauCheiroTabela {

    public function encontra($banco) {
        $retorno = "";
        $palavrasReservadas = array('ALL','ANALYSE','ANALYZE','AND','ANY','ARRAY','AS','ASC','ASYMMETRIC','BETWEEN',
                                    'BINARY','BOTH','CASE','CAST','CHECK','COLLATE','COLUMN','CONSTRAINT','CREATE',
                                    'CROSS','CURRENT_DATE','CURRENT_ROLE','CURRENT_TIME','CURRENT_TIMESTAMP',
                                    'CURRENT_USER','DEFAULT','DEFERRABLE','DESC','DISTINCT','DO','ELSE','END',
                                    'EXCEPT','FALSE','FOR','FOREIGN','FREEZE','FROM','FULL','GRANT','GROUP','HAVING',
                                    'IN','INITIALLY','INNER','INTERSECT','INTO','IS','ISNULL','JOIN','LEADING','LEFT',
                                    'LIKE','LIMIT','LOCALTIME','LOCALTIMESTAMP','NATURAL','NEW','NOT','NOTNULL','NULL',
                                    'OR','ORDER','OUTER','OVERLAPS','PLACING','PRIMARY','REFERENCES','RIGHT','SELECT',
                                    'SESSION_USER','SIMILAR','SOME','SYMMETRIC','TABLE','THEN','TO','TRAILING','TRUE',
                                    'UNION','UNIQUE','USER','USING','VERBOSE','WHEN','WHERE');

        foreach ($banco->getTabelas() as $tabela) {
            if (strpos($tabela->getNome(), ' ') !== false) {
                $vetTabelasProblema[] = $tabela;
            } else {
                foreach ($palavrasReservadas as $palavra) {
                    if (strtoupper(trim($palavra)) == strtoupper(trim($tabela->getNome()))) {
                        $vetTabelasProblema[] = $tabela;
                    }
                }
            }
        }

        return $this->refatora($banco, $vetTabelasProblema);
    }

    public function refatora($banco, $vetTabelasProblema) {
        foreach ($vetTabelasProblema as $tabelaProblema) {
            $novo = "novo_nome";
            $retorno .= "/*Tabela: ".$tabelaProblema->getNome() . "\n";
            $retorno .= "Refatoração:*/\n\n";

            $retorno .= $this->refatoraChavesEstrangeiras($banco, $tabelaProblema, $novo);
            
            $retorno .= "ALTER TABLE \"" . $tabelaProblema->getNome() . "\" RENAME TO \"" . $novo . "\";\n\n";

            $retorno .= $this->refatoraViews($tabelaProblema, $novo);

            $retorno .= $this->refatoraRotinas($banco, $tabelaProblema, $novo);

            $retorno .= "\n\n";
        }
        $retorno = $retorno?"/*Tabelas com nomes que são palavras reservadas do postgreSQL:*/\n\n".$retorno:"O banco de dados não econtrou nenhuma ocorrência da refatoração selecionada";
        return " \n".$retorno;
    }

    function substituiTexto($expressao, $textoOriginal, $palavraOriginal, $palavraSubstituir) {
        return preg_replace_callback(
                $expressao, function ($vetor) use ($palavraSubstituir) {
            return str_replace($vetor[1], $palavraSubstituir, $vetor[0]);
        }, $textoOriginal);
    }

    function refatoraChavesEstrangeiras($banco, $tabelaProblema, $novo) {
        $retornoChavesEstrangeiras = "";
        foreach ($tabelaProblema->getColunas() as $coluna) {
            if ($coluna->getNomeChaveEstrangeira()) {
                if (strpos($coluna->getNomeChaveEstrangeira(), $tabelaProblema->getNome()) !== false) {
                    $nomeChaveEstrangeiraSubstituido = str_replace($tabelaProblema->getNome(), $novo, $coluna->getNomeChaveEstrangeira());
                    $retornoChavesEstrangeiras .= "ALTER TABLE \"" . $tabelaProblema->getNome() . "\" RENAME CONSTRAINT \"" . $coluna->getNomeChaveEstrangeira() . "\" TO \"" . $nomeChaveEstrangeiraSubstituido . "\"; \n\n";
                }
            }
        }
        foreach ($banco->getTabelas() as $tabela) {
            foreach ($tabela->getColunas() as $coluna) {
                if ($tabela->getNome() == $tabelaProblema->getNome())
                    break;
                if ($coluna->getNomeTabelaReferencia() == $tabelaProblema->getNome()) {
                    if (strpos($coluna->getNomeChaveEstrangeira(), $tabelaProblema->getNome()) !== false) {
                        $nomeChaveEstrangeiraSubstituido = str_replace($tabelaProblema->getNome(), $novo, $coluna->getNomeChaveEstrangeira());
                        $retornoChavesEstrangeiras .= "ALTER TABLE \"" . $tabela->getNome() . "\" RENAME CONSTRAINT \"" . $coluna->getNomeChaveEstrangeira() . "\" TO \"" . $nomeChaveEstrangeiraSubstituido . "\"; \n\n";
                    }
                }
            }
        }

        return $retornoChavesEstrangeiras;
    }

    function refatoraViews($tabelaProblema, $novo) {
        $retornoView = "";
        foreach ($tabelaProblema->getViewsDependentes() as $view) {
            $encontrou = array();
            preg_match("/[^a-zA-Z0-9_]{$tabelaProblema->getNome()}[^a-zA-Z0-9_]/", $view->getDefinicao(), $encontrou);
            if ($encontrou) {
                $sqlSubstituida = $this->substituiTexto("/[^a-zA-Z0-9_]({$tabelaProblema->getNome()})[^a-zA-Z0-9_]/", $view->getDefinicao(), $tabelaProblema->getNome(), $novo);
                $retornoView = "CREATE OR REPLACE VIEW " . $view->getNome() . " AS " . $sqlSubstituida .
                        "ALTER TABLE " . $view->getNome() . " OWNER TO " . $view->getDono() . "; \n\n";
            }
            if (strpos($view->getNome(), $tabelaProblema->getNome()) !== false) {
                $nomeViewSubstituido = str_replace($tabelaProblema->getNome(), $novo, $view->getNome());
                $retornoView .= "ALTER VIEW \"" . $view->getNome() ."\" RENAME TO \"" . $nomeViewSubstituido . "\";\n\n";
            }
        }

        return $retornoView;
    }

    function refatoraRotinas($banco, $tabelaProblema, $novo) {
        $retornoRotinas = "";
        $retornoTriggers = "";
        if (strpos($tabelaProblema->getNome(), ' ') !== false) {
            $expressao = "/\"({$tabelaProblema->getNome()})\"/";
        } else {
            $expressao = "/public.({$tabelaProblema->getNome()})[^a-zA-Z0-9_]/";
        }
        foreach ($banco->getRotinas() as $rotina) {
            $encontrou = array();
            preg_match($expressao, $rotina->getDefinicao(), $encontrou);
            if ($encontrou) {
                $sqlSubstituida = $this->substituiTexto($expressao, $rotina->getDefinicao(), $tabelaProblema->getNome(), $novo);
                $retornoRotinas .= "CREATE OR REPLACE FUNCTION \"" . $rotina->getNome() . "\"(" . $rotina->getArgumentos() . ") RETURNS " . $rotina->getRetorno() . " AS \n\$BODY$" . $sqlSubstituida . "\n\$BODY$ \nLANGUAGE " . $rotina->getLinguagem() . " ". $rotina->getVolatilidade() . ";\nALTER FUNCTION \"" . $rotina->getNome() . "\"(" . $rotina->getArgumentos() . ") \nOWNER TO \"" . $rotina->getDono() . "\";\n\n";
            }
            if (strpos($rotina->getNome(), $tabelaProblema->getNome()) !== false) {
                $nomeRotinaSubstituido = str_replace($tabelaProblema->getNome(), $novo, $rotina->getNome());
                $retornoRotinas .= "ALTER FUNCTION \"" . $rotina->getNome() . "\"(" . $rotina->getArgumentos() . ") RENAME TO \"" . $nomeRotinaSubstituido . "\";\n\n";
            }

            if ($rotina->getRetorno() == 'trigger') {
                $retornoRotinas .= $this->refatoraTriggers($banco, $rotina, $tabelaProblema, $novo);
            }
        }

        return $retornoRotinas . $retornoTriggers;
    }

    public function refatoraTriggers($banco, $rotina, $tabelaProblema, $novo) {
        $retornoTriggers = "";
        foreach ($banco->getTabelas() as $tabela) {
            foreach ($tabela->getTriggers() as $trigger) {
                foreach ($trigger->getRotinas() as $rotinaTrigger) {
                    if ($rotinaTrigger->getNome() == $rotina->getNome()) {
                        if (strpos($trigger->getNome(), $tabelaProblema->getNome())) {
                            $nomeTriggerSubstituido = str_replace($tabelaProblema->getNome(), $novo, $trigger->getNome());
                            $retornoTriggers .= "ALTER TRIGGER \"" . $trigger->getNome() . "\" ON \"" . $tabela->getNome() . "\" RENAME TO \"" . $nomeTriggerSubstituido . "\";\n\n";
                        }
                    }
                }
            }
        }
        return $retornoTriggers;
    }

}

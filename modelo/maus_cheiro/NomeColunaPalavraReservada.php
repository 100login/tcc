<?php

class NomeColunaPalavraReservada extends MauCheiroColuna {

    public function encontra($banco) {
        $retorno = "";
        $palavrasReservadas = array('USER', 'OWNER');
        $vetColunasProblema = array();
        foreach ($banco->getTabelas() as $tabela) {
            $nomesColunas = "";
            foreach ($tabela->getColunas() as $coluna) {
                if (strpos($coluna->getNome(), ' ')) {
                    $nomesColunas .= $coluna->getNome() . "<br/>";
                    $vetColunasProblema[] = $coluna;
                } else {
                    foreach ($palavrasReservadas as $palavra) {
                        if (strtoupper(trim($palavra)) == strtoupper(trim($coluna->getNome()))) {
                            $nomesColunas .= $coluna->getNome() . "<br/>";
                            $vetColunasProblema[] = $coluna;
                        }
                    }
                }
            }
            if ($nomesColunas)
                $retorno .= "Tabela: " . $tabela->getNome() . "<br/>Campos: <br/>" . $nomesColunas;
        }
        if ($retorno)
            $retorno = "O nome das colunas abaixo é uma palavra reservada:<br/>" . $retorno . "<br/><br/>";

        $this->refatora($banco, $vetColunasProblema);

        return $retorno;
    }

    public function refatora($banco, $vetColunasProblema) {
        $retornoColuna = "";
        $retornoFk = "";
        $retornoTriggers = "";
        $retornoRotinas = "";
        $retornoViews = "";

        $vetColunasProblemaPk = $vetColunasProblema;
        foreach ($vetColunasProblema as $chave => $colunaProblema) {
            foreach ($vetColunasProblemaPk as $chavePk => $colunaProblemaPk) {
                if ($colunaProblema->getNomeTabelaReferencia() == $colunaProblemaPk->getTabela()->getNome() && $colunaProblema->getNomeColunaReferencia() == $colunaProblemaPk->getNome()) {
                    unset($vetColunasProblema[$chave]);
                }
            }
        }
        foreach ($vetColunasProblema as $colunaProblema) {
            $novo = 'novo_nome';
            $retornoColuna = "ALTER TABLE " . $colunaProblema->getTabela()->getNome() . " RENAME COLUMN " . $colunaProblema->getNome() . " TO " . $novo . ";<br/><br/>";
            
            foreach ($banco->getTabelas() as $tabela) {
                foreach ($tabela->getColunas() as $coluna) {
                    if ($coluna->getNomeTabelaReferencia() == $colunaProblema->getTabela()->getNome() && $coluna->getNomeColunaReferencia() == $colunaProblema->getNome()) {
                        $retornoFk .= "ALTER TABLE " . $tabela->getNome() . " RENAME COLUMN " . $coluna->getNome() . " TO " . $novo . ";<br/><br/>";
                    }
                }
                
                foreach ($tabela->getTriggers() as $trigger) {
                    foreach ($trigger->getRotinas() as $rotina) {
                        $encontrou = array();
                        preg_match("/[^a-zA-Z0-9_]({$colunaProblema->getNome()})[^a-zA-Z0-9_]/", $rotina->getDefinicao(), $encontrou);
                        if ($encontrou) {
                            $sqlSubstituida = $this->substituiTexto($rotina->getDefinicao(), $colunaProblema->getNome(),$novo);
                            $retornoTriggers .= 'CREATE OR REPLACE FUNCTION ' . $rotina->getNome() . '(' . $rotina->getArgumentos() . ')
                                         RETURNS ' . $rotina->getRetorno() . ' AS
                                         $BODY$' . $sqlSubstituida . '$BODY$
                                         LANGUAGE ' . $rotina->getLinguagem() . ' ' . $rotina->getVolatilidade() . ';
                                         ALTER FUNCTION ' . $rotina->getNome() . '(' . $rotina->getArgumentos() . ')
                                         OWNER TO ' . $rotina->getDono() . ';<br/><br/>';
                        }
                    }
                }
            }

            foreach ($banco->getRotinas() as $rotina) {
                $encontrou = array();
                preg_match("/[^a-zA-Z0-9_]{$colunaProblema->getNome()}[^a-zA-Z0-9_]/", $rotina->getDefinicao(), $encontrou);
                if ($encontrou) {
                    $sqlSubstituida = $this->substituiTexto($rotina->getDefinicao(), $colunaProblema->getNome(),$novo);
                    $retornoRotinas .= 'CREATE OR REPLACE FUNCTION ' . $rotina->getNome() . '(' . $rotina->getArgumentos() . ')
                                         RETURNS ' . $rotina->getRetorno() . ' AS
                                         $BODY$' . $sqlSubstituida . '$BODY$
                                         LANGUAGE ' . $rotina->getLinguagem() . ' ' . $rotina->getVolatilidade() . ';
                                         ALTER FUNCTION ' . $rotina->getNome() . '(' . $rotina->getArgumentos() . ')
                                         OWNER TO ' . $rotina->getDono() . ';<br/><br/>';
                }
            }

            foreach ($banco->getViews() as $view) {
                $encontrou = array();
                preg_match("/[^a-zA-Z0-9_]{$colunaProblema->getNome()}[^a-zA-Z0-9_]/", $view->getDefinicao(), $encontrou);
                if ($encontrou) {
                    $sqlSubstituida = $this->substituiTexto($view->getDefinicao(), $colunaProblema->getNome(),$novo);
                    $retornoView = 'CREATE OR REPLACE VIEW ' . $view->getNome() . ' AS 
                                    ' . $view->getDefinicao() .
                            'ALTER TABLE ' . $view->getNome() . ' OWNER TO ' . $view->getDono() . '; <br/><br/>';
                }
            }
            echo $colunaProblema->getNome() . '<br/><br/><br/>';
            print_r('Coluna Original: '.$retornoColuna);
            print_r('Chaves Estrangeiras: '.$retornoFk);
            print_r('Rotinas de Triggers: '.$retornoTriggers);
            print_r('Rotinas :' .$retornoRotinas);
            print_r('Views : '.$retornoView);
        }
    }

    function substituiTexto($textoOriginal,$palavraOriginal, $palavraSubstituir) {
        return preg_replace_callback(
                "/[^a-zA-Z0-9_]({$palavraOriginal})[^a-zA-Z0-9_]/", 
                function ($vetor) use ($palavraSubstituir) {
                return str_replace($vetor[1], $palavraSubstituir, $vetor[0]);}, 
                $textoOriginal);
    }

}

<?php

class ColunaSemChaveEstrangeira extends MauCheiroColuna {

    public function encontra($banco) {
        $bdBanco = new BDBanco();
        $bdBanco->carregaBanco($_SESSION['id_banco']);
        $fk = str_replace('x', '', $bdBanco->getPadraoChaveEstrangeira()) ;
        $pk = str_replace('x', '', $bdBanco->getPadraoChavePrimaria());
        foreach ($banco->getTabelas() as $tabela) {
            foreach ($tabela->getColunas() as $coluna) {
                preg_match("/.*{$fk}.*/", $coluna->getNome(), $retFk);
                if (!empty($retFk)) {
                    if (!$coluna->getnomeColunaReferencia() && !$coluna->getEhSerial()) {
                        $nomeTabela = str_replace($fk, '', $coluna->getNome());
                        $maiorSimilaridadeTabela = 0;
                        $vetColunasTabelaMaiorSimilaridade = array();
                        foreach ($banco->getTabelas() as $tabelaPk) {
                            similar_text($nomeTabela, $tabelaPk->getNome(), $porcentagemSimilaridade);
                            if ($porcentagemSimilaridade > $maiorSimilaridadeTabela) {
                                $maiorSimilaridadeTabela = $porcentagemSimilaridade;
                                $tabelaMaiorSimilaridade = $tabelaPk;
                                $vetColunasTabelaMaiorSimilaridade = $tabelaPk->getColunas();
                            }
                        }
                        if ($maiorSimilaridadeTabela > 80) {
                            foreach ($vetColunasTabelaMaiorSimilaridade as $colunaPk) {
                                preg_match("/.*{$pk}.*/", $colunaPk->getNome(), $retPk);
                                if (!empty($retPk)) {
                                    if ($colunaPk->getChavePrimaria() || $colunaPk->getEhSerial()) {
                                        if($coluna->getPorcentagemCorrespondente($colunaPk)>= 80){
                                            if($coluna->getNatureza() == $colunaPk->getNatureza() || $coluna->getTipo() == $colunaPk->getTipo()){
                                                $vetColunasProblema[] = array($tabela, $coluna, $tabelaMaiorSimilaridade, $colunaPk);
                                                break;
                                            }
                                        }
                                        
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return $this->refatora($banco, $vetColunasProblema);
    }

    function refatora($banco, $vetColunasProblema) {
        foreach ($vetColunasProblema as $colunas) {
            $tabelaFk = $colunas[0];
            $colunaFk = $colunas[1];
            $tabelaPk = $colunas[2];
            $colunaPk = $colunas[3];

            $retorno .= "/*Possível chave estrangeira -> Tabela: " . $tabelaFk->getNome() . " Coluna: " . $colunaFk->getNome() . "\n";
            $retorno .= "Possível chave primária -> Tabela: " . $tabelaPk->getNome() . " Coluna: " . $colunaPk->getNome() . "\n\n";
            $retorno .= "Refatoração:*/\n\n";
            
            $valorMaisRecenteContemPk = '';

            $valoresMaisFrequentes = $tabelaFk->obtemValoresMaisFrequnetes($colunaFk);
            foreach ($valoresMaisFrequentes as $valorMaisFrequente => $qtde) {
                if ($tabelaPk->verificaValorExisteColuna($colunaPk, $valorMaisFrequente)) {
                    $valorMaisRecenteContemPk = $valorMaisFrequente;
                    break;
                }
            }
            
            $valor = $valorMaisRecenteContemPk?$colunaFk->getValorTratado($valorMaisRecenteContemPk):$colunaFk->getValorTratado($colunaPk->getPrimeiroValorEncontrado());
            $valor = !$valor?"Insira um valo válido":$valor;
            
            $nomeFk = $tabelaPk->getNome() . "_" . $colunaPk->getNome() . "_" . $tabelaFk->getNome() . "_" . $colunaFk->getNome() . "_FK";
           // $retorno .= "UPDATE \"" . $tabelaFk->getNome() . "\" SET \"" . $colunaFk->getNome() . "\" = " . $valor . " WHERE \"" . $colunaFk->getNome() . "\" NOT IN ( SELECT \"" . $colunaPk->getNome() . "\" FROM \"" . $tabelaPk->getNome() . "\" GROUP BY \"" . $colunaPk->getNome() . "\");\n";
            $retorno .= "ALTER TABLE \"" . $tabelaFk->getNome() . "\" ADD CONSTRAINT \"" . $nomeFk . "\" FOREIGN KEY (\"" . $colunaFk->getNome() . "\") REFERENCES \"" . $tabelaPk->getNome() . "\" (\"" . $colunaPk->getNome() . "\") MATCH FULL;\n\n";
        }
        $retorno = $retorno?"/*Colunas que representam possíceis chaves estrangeiras sem a devida restrição:*/\n\n".$retorno:"O banco de dados não econtrou nenhuma ocorrência da refatoração selecionada";
        return " \n".$retorno;
    }

}

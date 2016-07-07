<?php

class TabelaTodosCamposAnulaveisPlus extends MauCheiroTabela {

    public function encontra($banco) {
        foreach ($banco->getTabelas() as $tabela) {
            $contaCamposNaoNulos = 0;
            $temCampoNaoChave = false;
            foreach ($tabela->getColunas() as $coluna) {
                if (!$coluna->getChavePrimaria()) {
                    $temCampoNaoChave = true;
                    if (!$coluna->getEhNulo()) {
                        $contaCamposNaoNulos++;
                    }
                }
            }
            if (!$contaCamposNaoNulos && $temCampoNaoChave)
                $vetTabelasProblema[] = $tabela;
        }

        return $this->refatora($banco, $vetTabelasProblema);
    }

    function refatora($banco, $vetTabelasProblema) {
        foreach ($vetTabelasProblema as $tabelaProblema) {
            $retorno .= "/*Tabela: " . $tabelaProblema->getNome() . " \n \n";
            $retorno .= "Refatoração:*/\n\n";
            $numeroVezesNaoNulo = array();
            $valorMaisUsado = array();
            $numeroRegistros = count($tabelaProblema->getDados());
            foreach ($tabelaProblema->getDados() as $linha) {
                foreach ($linha as $chave => $valor) {
                    if ($valor) {
                        $numeroVezesNaoNulo[$chave] ++;
                        $valorMaisUsado[$chave][$valor] ++;
                    }
                }
            }
            foreach ($numeroVezesNaoNulo as $nomeColunaNaoNula => $qtdeNaoNulo) {
                $coluna = $tabelaProblema->getColunaPorNome($nomeColunaNaoNula);
                if ((((100 * $qtdeNaoNulo) / $numeroRegistros) >= 80) && !$coluna->getChavePrimaria()) {

                    arsort($valorMaisUsado[$nomeColunaNaoNula]);

                    if ($coluna->getNomeColunaReferencia()) {
                        $tabelaPk = $banco->getTabelaPorNome($coluna->getNomeTabelaReferencia());
                        $colunaPk = $tabelaPk->getColunaPorNome($coluna->getNomeColunaReferencia());
                        foreach ($valorMaisUsado[$nomeColunaNaoNula] as $valorMaisFrequente => $qtde) {
                            if ($tabelaPk->verificaValorExisteColuna($colunaPk, $valorMaisFrequente)) {
                                $valor = $coluna->getValorTratado($valorMaisFrequente);
                                break;
                            }
                        }
                    } else {
                        list($valorMaisFrequente, $qtde) = each($valorMaisUsado[$nomeColunaNaoNula]);
                        $valor = $coluna->getValorTratado($valorMaisFrequente);
                    }
                    
                    $valor = !$valor?"Insira um valor válido":$valor;
                    
                    $retorno .= "UPDATE \"" . $tabelaProblema->getNome() . "\" SET \"" . $coluna->getNome() . "\" = " . $valor . " WHERE \"" . $coluna->getNome() . "\" IS NULL;\n";
                    $retorno .= "ALTER TABLE \"" . $tabelaProblema->getNome() . "\" ALTER COLUMN \"" . $coluna->getNome() . "\" SET NOT NULL;\n\n";
                }
            }
        }
        $retorno = $retorno?"/*Tabelas com todos campos nulos:*/\n\n".$retorno:"O banco de dados não econtrou nenhuma ocorrência da refatoração selecionada";
        return " \n".$retorno;
    }

}

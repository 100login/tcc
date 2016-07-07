<?php

class ChaveEstrangeiraDiferentePrimaria extends MauCheiroColuna
{    
    public function encontra($banco){
        $vetColunasProblema = array();
        foreach ($banco->getTabelas() as $tabela) {
            foreach ($tabela->getColunas() as $coluna) { 
                if($coluna->getChavePrimaria()){
                    foreach ($banco->getTabelas() as $tabelaProcura) {
                        foreach ($tabelaProcura->getColunas() as $colunaProcura) { 
                            if(($colunaProcura->getNomeTabelaReferencia() == $tabela->getNome()) and ($colunaProcura->getNomeColunaReferencia() == $coluna->getNome())){
                                if($coluna->getTipo() != $colunaProcura->getTipo()){
                                     $vetColunasProblema[] = array($coluna, $colunaProcura);
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
            $colunaPk = $colunas[0];
            $colunaFk = $colunas[1];
            $retorno .= "/*Chave estrangeira -> Tabela: ".$colunaFk->getTabela()->getNome()." Coluna : ".$colunaFk->getNome()." Tipo : ".$colunaFk->getTipo()."\n";
            $retorno .= "Chave primária -> Tabela: ".$colunaPk->getTabela()->getNome()." Coluna : ".$colunaPk->getNome()." Tipo : ".$colunaPk->getTipo()."\n";
            $retorno .= "Refatoração:*/\n\n";
            
            $retorno .= "ALTER TABLE \"".$colunaFk->getTabela()->getNome()."\" ALTER COLUMN \"".$colunaFk->getNome()."\" TYPE ".$colunaPk->getTipo()."; \n\n";
            $colunaPkAnterior = $colunaPk;
        }
        $retorno = $retorno?"/*Colunas que são chaves estrangeiras, porém o tipo de dado é diferente da chave primária:*/\n\n".$retorno:"O banco de dados não econtrou nenhuma ocorrência da refatoração selecionada";
        return " \n".$retorno;
    }
    
}

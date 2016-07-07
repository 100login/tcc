<?php

class ChaveEstrangeiraSemIndice extends MauCheiroTabela
{    
    public function encontra($banco){
        foreach ($banco->getTabelas() as $tabela) {
            foreach ($tabela->getColunas() as $coluna) {
                if($coluna->getNomeColunaReferencia()) {
                    $temIndice = false;
                    foreach ($tabela->getIndices() as $indice) {
                        if($temIndice) break;
                        if($indice){
                            foreach ($indice->getColunas() as $colunaIndice) {
                                if($coluna->getNome() == $colunaIndice->getNome()) {
                                    $temIndice = true; 
                                    break;
                                }
                            }
                        }
                    }
                    
                    if(!$temIndice) $vetColunasProblema[] = $coluna;
                }
            }
        }
        
        return $this->refatora($banco,$vetColunasProblema);
    }
    
    function refatora($banco,$vetColunasProblema) {
        foreach ($vetColunasProblema as $colunaProblema) {
            $retorno .= "/*Coluna: ".$colunaProblema->getNome()." Tabela: ".$colunaProblema->getTabela()->getNome()."\n";
            $retorno .= "Refatoração:*/\n\n";
            if($colunaProblema->getEhNulo()){
                $valoresMaisFrequentes = $colunaProblema->getTabela()->obtemValoresMaisFrequnetes($colunaProblema);
                if($valoresMaisFrequentes){
                    list($valorMaisFrequente,$qtde) = each($valoresMaisFrequentes);
                    $valor = $colunaProblema->getValorTratado($valorMaisFrequente);
                }else{
                    $tabelaPk = $banco->getTabelaPorNome($colunaProblema->getNomeTabelaReferencia());
                    $colunaPk = $tabelaPk->getColunaPorNome($colunaProblema->getNomeColunaReferencia());
                    $valor = $colunaProblema->getValorTratado($colunaPk->getPrimeiroValorEncontrado());
                }
                
                $valor = !$valor?"Insira um valo válido":$valor;
                
               // $retorno .= "UPDATE \"".$colunaProblema->getTabela()->getNome()."\" SET \"".$colunaProblema->getNome()."\" = ".$valor." WHERE \"".$colunaProblema->getNome()."\" IS NULL;\n";
               // $retorno .= "ALTER TABLE \"".$colunaProblema->getTabela()->getNome()."\" ALTER COLUMN \"".$colunaProblema->getNome()."\" SET NOT NULL;\n";
            }
            
            $retorno .= "CREATE INDEX \"".$colunaProblema->getTabela()->getNome()."_".$colunaProblema->getNome()."_idx\" ON \"".$colunaProblema->getTabela()->getNome()."\" (\"".$colunaProblema->getNome()."\");\n\n";
            
        }
        $retorno = $retorno?"/*Colunas que são chaves estrangeiras mas não estão presentes em nem um índice da tabela:*/\n\n".$retorno:"O banco de dados não econtrou nenhuma ocorrência da refatoração selecionada";
        return " \n".$retorno;
    }
    
}

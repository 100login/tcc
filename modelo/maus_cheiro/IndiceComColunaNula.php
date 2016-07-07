<?php

class IndiceComColunaNula extends MauCheiroIndice{
    
    public function encontra($banco){
        foreach ($banco->getTabelas() as $tabela) {
            foreach ($tabela->getIndices() as $indice) {
                if($indice && $indice->getEhUnico()){
                    foreach ($indice->getColunas() as  $coluna) {
                        if($coluna->getEhNulo()) {
                            $vetColunasProblema[] = $coluna;
                        }
                    }
                }
            }
        }
       
        return $this->refatora($banco, $vetColunasProblema);
    }
    
    function refatora($banco, $vetColunasProblema){
        foreach ($vetColunasProblema as $colunaProblema) {
            $retorno .=  "/*Tabela: ".$colunaProblema->getTabela()->getNome()." Coluna: ".$colunaProblema->getNome()." \n \n";
            $retorno .= "Refatoração:*/\n\n";
            $valoresMaisFrequentes = $colunaProblema->getTabela()->obtemValoresMaisFrequnetes($colunaProblema);
            
            if($colunaProblema->getNomeColunaReferencia()){
                $tabelaPk = $banco->getTabelaPorNome($colunaProblema->getNomeTabelaReferencia());
                $colunaPk = $tabelaPk->getColunaPorNome($colunaProblema->getNomeColunaReferencia());
                foreach ($valoresMaisFrequentes as $valorMaisFrequente => $qtde) {
                    if ($tabelaPk->verificaValorExisteColuna($colunaPk, $valorMaisFrequente)) {
                        $valor = $colunaProblema->getValorTratado($valorMaisFrequente);
                        break;
                    }
                }
            }else{
                list($valorMaisFrequente,$qtde) = each($valoresMaisFrequentes);
                $valor = $colunaProblema->getValorTratado($valorMaisFrequente);
            }
            
            $valor = !$valor?"Insira um valo válido":$valor;
            
            //$retorno .= "UPDATE \"".$colunaProblema->getTabela()->getNome()."\" SET \"".$colunaProblema->getNome()."\" = ".$valor." WHERE \"".$colunaProblema->getNome()."\" IS NULL;\n";
            $retorno .= "ALTER TABLE \"".$colunaProblema->getTabela()->getNome()."\" ALTER COLUMN \"".$colunaProblema->getNome()."\" SET NOT NULL;\n\n";
           
        }
        $retorno = $retorno?"/*As colunas abaixo fazem parte de um índice, porém são anulaveis:*/\n\n".$retorno:"O banco de dados não econtrou nenhuma ocorrência da refatoração selecionada";
        return " \n".$retorno;
    }
    
}

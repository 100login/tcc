<?php

class ChavePrimariaOrdenacao extends MauCheiroTabela
{    
    public function encontra($banco){
        foreach ($banco->getTabelas() as $tabela) {
            $primeiraColunaNaoChave = false;
            foreach ($tabela->getColunas() as $coluna) {
                if(!$coluna->getChavePrimaria())$primeiraColunaNaoChave = true;
                
                if($coluna->getChavePrimaria() && $primeiraColunaNaoChave){
                    $vetColunasProblema[] = $coluna;
                }
            }
        }
        
        return $this->refatora($banco, $vetColunasProblema);
    }
    
    public function refatora($banco, $vetColunasProblema){
        foreach ($vetColunasProblema as $ColunaProblema) {
            $retorno .= "Tabela: ".$ColunaProblema->getTabela()->getNome()." Coluna: ".$ColunaProblema->getNome()."\n";
        }
        $retorno = $retorno?"/*As colunas abaixo são chaves primárias que não estão entre os primeiros campos:*/\n\n".$retorno:"O banco de dados não econtrou nenhuma ocorrência da refatoração selecionada";
        return " \n".$retorno;
    }
}

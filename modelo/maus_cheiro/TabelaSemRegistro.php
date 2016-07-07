<?php

class TabelaSemRegistro extends MauCheiroTabela
{    
    public function encontra($banco){
        $retorno = "";
        
        foreach ($banco->getTabelas() as $tabela) {
           if(!count($tabela->getDados())){
               $vetTabelasProblema[] = $tabela;
            }
        }
        return $this->refatora($banco, $vetTabelasProblema);
    }
   
    public function refatora($banco, $vetTabelasProblema){
        foreach ($vetTabelasProblema as $tabelaProblema) {
            $retorno .= "Tabela: ".$tabelaProblema->getNome()."\n";
        }
        $retorno = $retorno?"/*As tabelas abaixo estão vazias:*/\n\n".$retorno:"O banco de dados não econtrou nenhuma ocorrência da refatoração selecionada";
        return " \n".$retorno;
    }
    
}

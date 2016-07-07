<?php

class TabelaSemChavePrimaria extends MauCheiroTabela
{    
    public function encontra($banco){
        foreach ($banco->getTabelas() as $tabela) {
            $temChavePrimaria = false;
            foreach ($tabela->getColunas() as $coluna) {
                if($coluna->getChavePrimaria()) {
                    $temChavePrimaria = true;
                    break;
                }
            }
            if (!$temChavePrimaria) {
                $vetTabelasProblema[] = $tabela;
            }
        }
        return $this->refatora($banco, $vetTabelasProblema);
    }
    
    public function refatora($banco, $vetTabelasProblema){
        foreach ($vetTabelasProblema as $tabelaProblema) {
            $retorno .= "Tabela: ".$tabelaProblema->getNome()." \n";
        }
        $retorno = $retorno?"/*As tabelas abaixo não tem chave primária:*/\n\n".$retorno:"O banco de dados não econtrou nenhuma ocorrência da refatoração selecionada";
        return " \n".$retorno;
    }
    
}

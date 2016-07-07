<?php

class TabelaMuitasLinhas extends MauCheiroTabela
{    
    public function encontra($banco){
           //1073741824
        $bdBanco = new BDBanco();
        $bdBanco->carregaBanco($_SESSION['id_banco']);

        foreach ($banco->getTabelas() as $tabela) {
            if($tabela->getTamanho() > $bdBanco->getQuantidadeMemoria()){
                $vetTabelasProblema[] = $tabela;
            }
        }
        return $this->refatora($banco, $vetTabelasProblema);
    }
    
    public function refatora($banco, $vetTabelasProblema){
        foreach ($vetTabelasProblema as $tabelaProblema) {
            $retorno .= "Tabela: ".$tabelaProblema->getNome()." Quantidade de Registros: ".  count($tabelaProblema->getDados())." Tamanho da Tabela: ".$tabelaProblema->getTamanho()." kb \n";
        }
        $retorno = $retorno?"/*As tabelas abaixo tem muitas linhas:*/\n\n".$retorno:"O banco de dados não econtrou nenhuma ocorrência da refatoração selecionada";
        return " \n".$retorno;
    }
    
}

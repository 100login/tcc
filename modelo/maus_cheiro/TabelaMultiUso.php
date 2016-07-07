<?php

class TabelaMultiUso extends MauCheiroTabela
{    
    
    private function similaridadeVetores($vet1, $vet2){
        $cont = 0;
        foreach ($vet1 as $valor1) {
            foreach ($vet2 as $valor2) {
                if($valor1 == $valor2){
                    $cont++;
                    break;
                }
            }
        }
       return (100 * $cont)/count($vet1);       
    }
    
    public function encontra($banco){
        $vetContPadroes = array();
        
        foreach ($banco->getTabelas() as $tabela) {
            $vetContPadroes = array();
            $vetContPadroesAux = array();
            //Conta o número de vezes que um conjunto de campos nulo aparece
            foreach ($tabela->getDados() as $reg) {
                $stringCamposNulo = "";
                foreach ($reg as $nomeCampo => $info) {
                    if(!$info) $stringCamposNulo .= $nomeCampo."|";
                }
                if($stringCamposNulo) $vetContPadroes[$stringCamposNulo] += 1;
            }
            arsort($vetContPadroes);
           //Retira os conjuntos de campos que representam menos de 20% dos registros
            $totalRegistros = count($tabela->getDados());
            foreach ($vetContPadroes as $nomesCampos => $qtde) {
                if(((100 * $qtde)/$totalRegistros) < 20){
                   unset($vetContPadroes[$nomesCampos]);
                }
            }
            //Monta um vetor com a diferença entre os conjunto de campos para validar o quanto os cmpos nulos são diferentes
            $vetComparacoes = array();
            foreach ($vetContPadroes as $nomesCampos => $qtde) {
                $vetContPadroesAux = $vetContPadroes;
                $campos = explode('|',substr($nomesCampos,0,-1));
                foreach ($vetContPadroesAux as $nomesCamposAux => $qtdeAux) {
                    if($nomesCampos != $nomesCamposAux){
                        $camposAux = explode('|',substr($nomesCamposAux,0,-1));
                        if (array_key_exists($nomesCampos."+".$nomesCamposAux, $vetComparacoes)) {
                           $vetComparacoes[$nomesCampos."+".$nomesCamposAux] += $this->similaridadeVetores($campos, $camposAux);
                        }else if(array_key_exists($nomesCamposAux."+".$nomesCampos, $vetComparacoes)) {
                            $vetComparacoes[$nomesCamposAux."+".$nomesCampos] += $this->similaridadeVetores($campos, $camposAux);
                        }else{
                            $vetComparacoes[$nomesCampos."+".$nomesCamposAux] += $this->similaridadeVetores($campos, $camposAux);
                        }
                    }
                }

            }
            asort($vetComparacoes);

            $totalContComparacoes = count($vetComparacoes);
        
            if(count($vetContPadroes) > 0 && count($vetComparacoes) > 0){
                if((array_sum($vetComparacoes)/($totalContComparacoes*2)) < 40){
                    $vetTabelasProblema[] = $tabela;
                }   
            }
            
        }
        
        return $this->refatora($banco, $vetTabelasProblema);
    }
    
    public function refatora($banco, $vetTabelasProblema){
        foreach ($vetTabelasProblema as $tabelaProblema) {
            $retorno .= "Tabela: ".$tabelaProblema->getNome()."\n";
        }
        $retorno = $retorno?"/*É provável que as tabelas abaixo estão sendo usadas para armazenar mais de um objetivo:*/\n\n".$retorno:"O banco de dados não econtrou nenhuma ocorrência da refatoração selecionada";
        return " \n".$retorno;
    }
    
}

<?php

class BDBancoDAO extends Database{
    
    public function carregaBanco($idBanco,$bdBanco){
        $sql = "SELECT 
                     *  
                FROM 
                     bancos b
                WHERE 
                       b.id_banco = ".$idBanco.";";

        $recurso =  pg_query($this->dbc, $sql);
       
        $regBanco = pg_fetch_assoc($recurso);
        
        if($regBanco){
            $bdBanco->setIdBanco($regBanco['id_banco']);
            $bdBanco->setNome($regBanco['nm_banco']);
            $bdBanco->setNomeUsuario($regBanco['nm_usuario']);
            $bdBanco->setSenha($regBanco['ds_senha']);
            $bdBanco->setPorta($regBanco['ds_porta']);
            $bdBanco->setHost($regBanco['ds_host']);  
            $bdBanco->setPadraoChavePrimaria($regBanco['ds_padrao_chave_primaria']);  
            $bdBanco->setPadraoChaveEstrangeira($regBanco['ds_padrao_chave_estrangeira']);  
            $bdBanco->setQuantidadeMemoria($regBanco['qt_memoria']);
            return true;
        }
        return false;
    }
    
    public function lista($bdBanco){

        $sql = "SELECT 
                     *  
                FROM 
                     bancos b
                WHERE 
                       b.id_usuario = ".$bdBanco->getUsuario()->getIdUsuario().";";

        $recurso =  pg_query($this->dbc, $sql);
        
        $bdBancos = array();
        
        while ($regBanco = pg_fetch_assoc($recurso)) {
            $bdBanco = new BDBanco();
            $bdBanco->setIdBanco($regBanco['id_banco']);
            $bdBanco->setNome($regBanco['nm_banco']);
            $bdBanco->setNomeUsuario($regBanco['nm_usuario']);
            $bdBanco->setSenha($regBanco['ds_senha']);
            $bdBanco->setPorta($regBanco['ds_porta']);
            $bdBanco->setHost($regBanco['ds_host']);  

            $bdBancos[] = $bdBanco;
        }
        return $bdBancos;
    }
    
    public function salvar($bdBanco){
        if($bdBanco->getIdBanco()){
            $sql = "UPDATE 
                        bancos SET (nm_banco, nm_usuario, ds_senha, ds_porta, ds_host, ds_padrao_chave_primaria, ds_padrao_chave_estrangeira, qt_memoria) = 
                        ('".$bdBanco->getNome()."', '".$bdBanco->getNomeUsuario()."', '".$bdBanco->getSenha()."', '".$bdBanco->getPorta()."', '".$bdBanco->getHost()."', '".$bdBanco->getPadraoChavePrimaria()."', '".$bdBanco->getPadraoChaveEstrangeira()."', '".$bdBanco->getQuantidadeMemoria()."')
                    WHERE 
                        id_banco = ".$bdBanco->getIdBanco().";";
        }else{
              $sql = "INSERT INTO 
                             bancos (id_usuario, nm_banco, nm_usuario, ds_senha, ds_porta, ds_host, ds_padrao_chave_primaria, ds_padrao_chave_estrangeira, qt_memoria) 
                         VALUES
                              (".$bdBanco->getUsuario()->getIdUsuario().", '".$bdBanco->getNome()."', '".$bdBanco->getNomeUsuario()."', '".$bdBanco->getSenha()."', '".$bdBanco->getPorta()."', '".$bdBanco->getHost()."', '".$bdBanco->getPadraoChavePrimaria()."', '".$bdBanco->getPadraoChaveEstrangeira()."', '".$bdBanco->getQuantidadeMemoria()."');";

        }
        if(pg_query($this->dbc, $sql)){
            return true;
        }else{
            return false;
        }
    }
    
    public function deletar($bdBanco){
        if($bdBanco->getIdBanco()){
            $sql = "DELETE FROM 
                        bancos 
                    WHERE 
                        id_banco = ".$bdBanco->getIdBanco().";";
        }
        if(pg_query($this->dbc, $sql)){
            return true;
        }else{
            return false;
        }
    }
}

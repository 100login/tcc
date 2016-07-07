<?php

class BDUsuarioDAO extends Database{
    
    public function carregaUsuario($nomeColuna,$valorColuna,$bdUsuario){
        return $this->carregaUsuarioWhere("WHERE $nomeColuna = $valorColuna",$bdUsuario);
    }
    
    public function carregaUsuarioWhere($where,$bdUsuario){

        $sql = "SELECT 
                     *  
                FROM 
                     usuarios u
                     
                $where;";

        $recurso =  pg_query($this->dbc, $sql);
       
        $regUsuario = pg_fetch_assoc($recurso);
        
        if($regUsuario){
            $bdUsuario->setIdUsuario($regUsuario['id_usuario']);
            $bdUsuario->setLogin($regUsuario['ds_login']);
            $bdUsuario->setSenha($regUsuario['ds_senha']);
            $bdBanco = new BDBanco();
            $bdBanco->setUsuario($bdUsuario);
            $bdUsuario->setBancos($bdBanco->lista());
            $bdMauCheiro = new BDMauCheiro();
            $bdMauCheiro->setUsuario($bdUsuario);
            $bdUsuario->setMausCheiros($bdMauCheiro->listaWhere("WHERE id_usuario = ".$bdUsuario->getIdUsuario()));
            
            return true;
        }
        return false;
    }
    
    public function salvar($bdUsuario){
        if($bdUsuario->getIdUsuario()){
            $sql = "UPDATE 
                        usuarios SET (ds_senha) =  
                        ('".  md5($bdUsuario->getSenha())."')
                        WHERE id_usuario = ".$bdUsuario->getIdUsuario().";";
        }else{
            if($this->carregaUsuario("ds_login", "'".$bdUsuario->getLogin()."'", $bdUsuario)) return false;
            $sql = "INSERT INTO 
                        usuarios (ds_login, ds_senha) 
                    VALUES
                         ('".$bdUsuario->getLogin()."', '".  md5($bdUsuario->getSenha())."');";
        }
 
        if(pg_query($this->dbc, $sql)){
            $this->carregaUsuario("ds_login","'".$bdUsuario->getLogin()."'",$bdUsuario);
            return true;
        }else{
            return true;
        }
    }
}

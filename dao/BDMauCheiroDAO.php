<?php

class BDMauCheiroDAO extends Database {

    public function lista($usuario = null) {
        return $this->listaWhere("WHERE mc.id_usuario IN (1" . ($usuario ? "," . $usuario->getIdUsuario() : "") . ")");

    }
    
    public function listaWhere($where = "") {
                $sql = "SELECT 
                    *  
               FROM 
                    maus_cheiros mc
                    ".$where."
               ORDER BY 
                    mc.id_usuario, mc.cd_tipo DESC;";

        $recurso = pg_query($this->dbc, $sql);
        $bdMausCheiros = array();
        while ($regMauCheiro = pg_fetch_assoc($recurso)) {
            $bdMauCheiro = new BDMauCheiro();
            $bdMauCheiro->setIdMauCheiro($regMauCheiro['id_mau_cheiro']);
            $bdMauCheiro->setNomeClasse($regMauCheiro['nm_classe']);
            $bdMauCheiro->setDescricao($regMauCheiro['ds_mau_cheiro']);
            $bdMauCheiro->setTipo($regMauCheiro['cd_tipo']);
            $bdMauCheiro->setUsuario($regMauCheiro['id_usuario']==1?null:$usuario);

            $bdMausCheiros[] = $bdMauCheiro;
        }

        return $bdMausCheiros;
    }
    
    public function carregaMauCheiro($nomeColuna,$valorColuna,$bdMauCheiro){
        return $this->carregaMauCheiroWhere("WHERE $nomeColuna = $valorColuna",$bdMauCheiro);
    }

    public function carregaMauCheiroWhere($where,$bdMauCheiro) {
        $sql = "SELECT 
                    *  
               FROM 
                    maus_cheiros mc
               ".$where. ";";

        $recurso = pg_query($this->dbc, $sql);

        $regMauCheiro = pg_fetch_assoc($recurso);

        if ($regMauCheiro) {
            $bdMauCheiro->setIdMauCheiro($regMauCheiro['id_mau_cheiro']);
            $bdMauCheiro->setNomeClasse($regMauCheiro['nm_classe']);
            $bdMauCheiro->setDescricao($regMauCheiro['ds_mau_cheiro']);
            $bdMauCheiro->setTipo($regMauCheiro['cd_tipo']);
            return true;
        }
        return false;
    }
    
    public function salvar($bdMauCheiro){
        $bdMauCheiroExistente = new BDMauCheiro();
        $this->carregaMauCheiro("id_mau_cheiro",$bdMauCheiro->getIdMauCheiro(), $bdMauCheiroExistente);
        
        if($bdMauCheiro->getIdMauCheiro()){
            if($bdMauCheiro->getNomeClasse()){
                if($bdMauCheiro->getNomeClasse() != $bdMauCheiroExistente->getNomeClasse()){
                    if($this->carregaMauCheiroWhere("WHERE nm_classe = '".$bdMauCheiro->getNomeClasse()."' AND id_mau_cheiro != ".$bdMauCheiro->getIdMauCheiro(), $bdMauCheiroExistente)) return false;
                }
            }else{
               $bdMauCheiro->setNomeClasse($bdMauCheiroExistente->getNomeClasse()); 
            }
            $sql = "UPDATE 
                        maus_cheiros SET (nm_classe, ds_mau_cheiro, cd_tipo) = 
                        ('".$bdMauCheiro->getNomeClasse()."', '".$bdMauCheiro->getDescricao()."', '".$bdMauCheiro->getTipo()."')
                    WHERE 
                        id_mau_cheiro = ".$bdMauCheiro->getIdMauCheiro().";";

        }else{
              if($this->carregaMauCheiro("nm_classe",$bdMauCheiro->getNomeClasse(), $bdMauCheiroExistente)) return false;
              $sql = "INSERT INTO 
                             maus_cheiros (id_usuario, nm_classe, ds_mau_cheiro, cd_tipo) 
                         VALUES
                              (".$bdMauCheiro->getUsuario()->getIdUsuario().", '".$bdMauCheiro->getNomeClasse()."', '".$bdMauCheiro->getDescricao()."', '".$bdMauCheiro->getTipo()."');";
        }
        
        if(pg_query($this->dbc, $sql)){
            return true;
        }else{
            return false;
        }
    }
    
    public function deletar($bdMauCheiro){
        if($bdMauCheiro->getIdMauCheiro()){
            $sql = "DELETE FROM 
                        maus_cheiros 
                    WHERE 
                        id_mau_cheiro = ".$bdMauCheiro->getIdMauCheiro().";";
        }
        if(pg_query($this->dbc, $sql)){
            return true;
        }else{
            return false;
        }
    }

}

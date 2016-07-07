<?php

class BDLogDAO extends Database{
    
    public function carregaLog($idLog,$bdLog){
        $sql = "SELECT 
                     *  
                FROM 
                     logs l
                WHERE 
                       l.id_log = ".$idLog.";";

        $recurso =  pg_query($this->dbc, $sql);
       
        $regLog = pg_fetch_assoc($recurso);
        
        if($regLog){
            $bdLog->setIdLog($regLog['id_log']);
            $bdLog->setLog($regLog['txt_log']);
            $bdLog->setErro($regLog['txt_erro']);
            $bdLog->setData($regLog['dt_log']);
            
            $bdBanco = new BDBanco();
            $bdBanco->carregaBanco($regLog['id_banco']);
            $bdLog->setBanco($bdBanco);
            
            $bdMauCheiro = new BDMauCheiroDAO();
            $bdMauCheiro->carregaMauCheiro('id_mau_cheiro',$regLog['id_mau_cheiro']);
            $bdLog->setMauCheiro($bdMauCheiro);
            return true;
        }
        return false;
    }
    
    public function lista($bdLog){
        error_reporting(E_ERROR);
        $sql = "SELECT 
                     *  
                FROM 
                     logs l
                WHERE 
                       l.id_banco = ".$bdLog->getBanco()->getIdBanco()."
                ORDER BY
                    l.dt_log DESC;";

        $recurso =  pg_query($this->dbc, $sql);
       
        $bdLogs = array();

        while ($regLog = pg_fetch_assoc($recurso)) {
            $bdLog = new BDLog();
            $bdLog->setIdLog($regLog['id_log']);
            $bdLog->setLog($regLog['txt_log']);
            $bdLog->setErro($regLog['txt_erro']);
            $bdLog->setData($regLog['dt_log']);

            /*$bdBanco = new BDBanco();
            $bdBanco->carregaBanco($regBanco['id_banco']);
            $bdLog->setBanco($bdBanco);
            
            $bdMauCheiro = new BDMauCheiroDAO();
            $bdMauCheiro->carregaMauCheiro($regBanco['id_mau_cheiro']);
            $bdLog->setMauCheiro($bdMauCheiro);*/
    
            $bdLogs[] = $bdLog;
        }
        
        return $bdLogs;
    }
    
    public function salvar($bdLog){
       
        $sql = "INSERT INTO 
                            logs (id_banco, id_mau_cheiro, txt_log, txt_erro, dt_log) 
                        VALUES
                             (".$bdLog->getBanco()->getIdBanco().", ".$bdLog->getMauCheiro()->getIdMauCheiro()." ,'".str_replace("'", "''", $bdLog->getLog())."', ".($bdLog->getErro()?"'".str_replace("'", "''", $bdLog->getErro())."'":"null").", '".$bdLog->getData()."');";

        if(pg_query($this->dbc, $sql)){
            return true;
        }else{
            return false;
        }
    }
}

<?php

class BancoDAO extends Database{
    
    public function refatora($sql,$bdLog){
        pg_query($this->dbc, "BEGIN;".$sql);
        $erro = pg_last_error($this->dbc);
        if($erro){
            pg_query($this->dbc, "BOLLBACK;");
            $bdLog->setErro($erro);
        }else{
            pg_query($this->dbc, "COMMIT;");
        }
        $bdLog->setLog($sql);
        $bdLog->setData(date("Y-m-d H:i:s"));
        $bdLog->salvar();
        return $erro;
    }
}

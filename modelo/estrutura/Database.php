<?php

class Database {

    var $host = "";
    var $porta = "";
    var $usuario = "";
    var $senha= "";
    var $nome = "";
    var $CONST_ERRO = "Erro ao conectar no banco de dados!";
    var $dbc;
    var $last_id;

    function Database($nome, $usuario, $senha, $host, $porta) {
        $this->host = $host;
        $this->porta = $porta;
        $this->usuario = $usuario;
        $this->senha= $senha;
        $this->nome = $nome;
        $this->conecta_banco();
    }

    function conecta_banco() {
        $conecta_string = "host=" . $this->host . " port=" . $this->porta . " user=" . $this->usuario . " password=" . $this->senha. " dbname=" . $this->nome;
        $this->dbc = pg_connect($conecta_string);
        return $this->dbc;
    }

    function delete_banco($table, $id) {
        $Campo_id = $this->getPrimaryKey($table);
        $tmp = "delete from $table where $Campo_id='$id'";
        $sts = pg_query($this->dbc, $tmp) or die($this->CONST_ERRO . pg_last_error());
        return $sts;
    }

    function close_banco() {
        pg_close($this->dbc);
    }

    function insere_banco($campos, $valores, $tab) {
        $inicio = "INSERT INTO $tab(";
        $meio = ") VALUES (";
        $fim = ")";
        $valor = sizeof($campos);
        $strc = "";
        for ($i = 0; $i <= ($valor - 1); $i++) {
            $strc.="$campos[$i]";
            if ($i != ($valor - 1)) {
                $strc.=",";
            }
        }
        $strv = "";
        for ($k = 0; $k <= ($valor - 1); $k++) {
            $strv.="'$valores[$k]'";
            if ($k != ($valor - 1)) {
                $strv.=",";
            }
        }
        $insere = "$inicio$strc$meio$strv$fim";

        $this->query($insere);

        $this->setLastID($tab);
    }

    function query($sql) {
        return pg_query($this->dbc, $sql) or die($this->CONST_ERRO . pg_last_error());
    }

    function reg_banco($table) {
        $tmp = "select * from $table";
        $sts = pg_query($this->dbc, $tmp) or die($this->CONST_ERRO . pg_last_error());
        $num = pg_num_rows($sts);
        return($num);
    }

    function getPrimaryKey($table) {
        $sql = "select indexdef from pg_indexes where tablename = '$table' and indexname LIKE '%pkey';";
        $res = pg_query($this->dbc, $sql) or die($this->CONST_ERRO . pg_last_error());
        $resultado = pg_fetch_result($res, 'indexdef');
        $arr_temp = explode("(", $resultado);
        $arr_temp2 = $arr_temp[1];
        $arr_temp = explode(")", $arr_temp2);
        return $arr_temp[0];
    }

    function setLastID($table) {
        $sequence_name = $table . "_" . $this->getPrimaryKey($table) . "_seq";
        $sql = "select currval('$sequence_name') AS lastid";
        $res = pg_query($this->dbc, $sql) or die($this->CONST_ERRO . pg_last_error());
        $this->last_id = pg_fetch_result($res, 'lastid');
    }

    function id_banco() {
        return $this->last_id;
    }

}

;
?>

<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/tcc/config.php'); 
    //$palavra = 'user';
    //$texto = ' .user:l wdwd  wddw user ';
    //preg_match("/[^a-zA-Z0-9_]{$palavra}[^a-zA-Z0-9_]/", $texto,$vet);
    //print_r($vet);
   // print_r(preg_replace("/[^a-zA-Z0-9_]{$palavra}[^a-zA-Z0-9_]/",'novo',$texto));
    /*$texto = 'CREATE OR REPLACE FUNCTION fn_insert_tb_b()
  RETURNS trigger AS
$BODY$begin 
  insert into public.pessoas (numero) values (new.user); return new; end; $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION fn_insert_tb_b()
  OWNER TO postgres;
';
    $p = 'pessoas';
preg_match("/public.({$p})[^a-zA-Z0-9_]/", $texto, $encontrou);
print_r($encontrou);
die();*/

    //$banco = new Banco('tcc','postgres','123456','localhost','5432');
    //$banco->encontra();
    //print_r($banco->getTabelas()[0]->getColunas()[0]);
    header("Location: ".URL_ROOT."/controlador/usuario_controlador.php?acao=login");
    
?>
<?php
require_once(DIR_ROOT."/lib/raelgc/view/Template.php");
use raelgc\view\Template;

$tpl = new Template(DIR_TEMPLATE."/mau_cheiro/novo.html");

if($_SESSION["mensagem_erro"]){
    $tpl->RESULTADO_CLASSE = "alert alert-danger";
    $tpl->RESULTADO = $_SESSION["mensagem_erro"];
    $_SESSION["mensagem_erro"] = "";
}

$tpl->ACAO = "Novo";

$tipos = array('A'=>'Arquitetural','E'=>'Estrutural','I'=>'Integridade Referencial','Q'=>'Qualidade de dados');

foreach ($tipos as $codigo => $tipo) {
    $tpl->TIPO_CODIGO = $codigo;
    $tpl->TIPO_VALOR = $tipo;
    $tpl->block('BLOCO_TIPOS');
}

$tpl->show();
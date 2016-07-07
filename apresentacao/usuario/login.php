<?php
require_once(DIR_ROOT."/lib/raelgc/view/Template.php");
use raelgc\view\Template;

$tpl = new Template(DIR_TEMPLATE."/usuario/login.html");
 if($_SESSION["mensagem_sucesso"]){
    $tpl->RESULTADO_CLASSE = "alert alert-success";
    $tpl->RESULTADO = $_SESSION["mensagem_sucesso"];
    $_SESSION["mensagem_sucesso"] = "";
}
if($_SESSION["mensagem_erro"]){
    $tpl->RESULTADO_CLASSE = "alert alert-danger";
    $tpl->RESULTADO = $_SESSION["mensagem_erro"];
    $_SESSION["mensagem_erro"] = "";
}
$tpl->show();
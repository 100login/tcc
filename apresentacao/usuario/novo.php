<?php
require_once(DIR_ROOT."/lib/raelgc/view/Template.php");
use raelgc\view\Template;

$tpl = new Template(DIR_TEMPLATE."/usuario/novo.html");
$tpl->TITULO_PAGINA = "Novo";
if($bdUsuario){
    $tpl->TITULO_PAGINA = "Editar";
    $tpl->USUARIO_LOGADO = $bdUsuario->getLogin();
    $tpl->LINK_LOGOUT = URL_ROOT.'/controlador/usuario_controlador.php?acao=logout';
    $tpl->block('BLOCO_LOGADO');
    $tpl->ID_USUARIO = $bdUsuario->getIdUsuario();
    $tpl->LOGIN = $bdUsuario->getLogin();
    $tpl->DESATIVADO = "disabled";
}

if($_SESSION["mensagem_erro"]){
    $tpl->RESULTADO_CLASSE = "alert alert-danger";
    $tpl->RESULTADO = $_SESSION["mensagem_erro"];
    $_SESSION["mensagem_erro"] = "";
}

$tpl->show();

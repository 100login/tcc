<?php
require_once(DIR_ROOT."/lib/raelgc/view/Template.php");
use raelgc\view\Template;

$tpl = new Template(DIR_TEMPLATE."/Banco/lista.html");
$tpl->USUARIO_LOGADO = $bdUsuario->getLogin();
$tpl->LINK_LOGOUT = URL_ROOT.'/controlador/usuario_controlador.php?acao=logout';
$tpl->block('BLOCO_LOGADO');
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
$tpl->LINK_ADICIONAR = URL_ROOT.'/controlador/banco_controlador.php?acao=novo';
foreach ($bdUsuario->getBancos() as $bdBanco) {
    $tpl->HOST = $bdBanco->getHost();
    $tpl->BANCO = $bdBanco->getNome();
    $tpl->USUARIO = $bdBanco->getNomeUsuario();
    $tpl->PORTA = $bdBanco->getPorta();
    $tpl->LINK_EDITAR = URL_ROOT.'/controlador/banco_controlador.php?acao=edita&id_banco='.$bdBanco->getidBanco();
    $tpl->LINK_DELETAR = URL_ROOT.'/controlador/banco_controlador.php?acao=deletar&id_banco='.$bdBanco->getidBanco();
    $tpl->LINK_MAUS_CHEIROS = URL_ROOT.'/controlador/mau_cheiro_controlador.php?acao=exibe&id_banco='.$bdBanco->getidBanco();
    $tpl->LINK_LOGS = URL_ROOT.'/controlador/log_controlador.php?acao=lista&id_banco='.$bdBanco->getidBanco();
    $tpl->block('BLOCO_BANCOS');
}
$tpl->show();

<?php
require_once(DIR_ROOT."/lib/raelgc/view/Template.php");
use raelgc\view\Template;

$tpl = new Template(DIR_TEMPLATE."/mau_cheiro/lista.html");
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

$tipos = array('A'=>'Arquitetural','E'=>'Estrutural','I'=>'Integridade Referencial','Q'=>'Qualidade de dados');

$tpl->LINK_ADICIONAR = URL_ROOT.'/controlador/mau_cheiro_controlador.php?acao=novo';
foreach ($bdUsuario->getMausCheiros() as $bdMauCheiro) {
    $tpl->CLASSE = $bdMauCheiro->getNomeClasse();
    $tpl->DESCRICAO = $bdMauCheiro->getDescricao();
    $tpl->TIPO =$tipos[$bdMauCheiro->getTipo()];
    $tpl->LINK_EDITAR = URL_ROOT.'/controlador/mau_cheiro_controlador.php?acao=edita&id_mau_cheiro='.$bdMauCheiro->getidMauCheiro();
    $tpl->LINK_DELETAR = URL_ROOT.'/controlador/mau_cheiro_controlador.php?acao=deletar&id_mau_cheiro='.$bdMauCheiro->getidMauCheiro();
    $tpl->block('BLOCO_MAUS_CHEIROS');
}
$tpl->show();

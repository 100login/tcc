<?php
require_once(DIR_ROOT."/lib/raelgc/view/Template.php");
use raelgc\view\Template;

$tpl = new Template(DIR_TEMPLATE."/mau_cheiro/exibe.html");
$tpl->USUARIO_LOGADO = $bdUsuario->getLogin();
$tpl->LINK_LOGOUT = URL_ROOT.'/controlador/usuario_controlador.php?acao=logout';
$tpl->block('BLOCO_LOGADO');
$tpl->NOME_BANCO = $bdBanco->getNome();
$tpl->HOST_BANCO = $bdBanco->getHost();;
$tpl->CLASSE_NAO_SELECIONADO = 'hidden';
$vetIconesTipos = array("A"=>"fa-university","E"=>"fa-building","I"=>"fa-key","Q"=>"fa-database");
foreach ($vetMausCheiros as $bdMauCheiro) {
    $tpl->ICONE = $vetIconesTipos[$bdMauCheiro->getTipo()];
    $tpl->DESCRICAO = $bdMauCheiro->getDescricao();
    $tpl->LINK = URL_ROOT.'/controlador/mau_cheiro_controlador.php?acao=encontra&id_banco='.$_GET['id_banco'].'&id_mau_cheiro='.$bdMauCheiro->getidMauCheiro();
    $tpl->CLASSE_SELECIONADO = 'btn-default'; 
    $tpl->block('BLOCO_MAUS_CHEIROS');
}

$tpl->show();
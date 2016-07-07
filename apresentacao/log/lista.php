<?php
require_once(DIR_ROOT."/lib/raelgc/view/Template.php");
use raelgc\view\Template;

$bdBanco = new BDBanco();
$bdBanco->carregaBanco($_GET['id_banco']);
$bdLog = new BDLog();
$bdLog->setBanco($bdBanco);
$vetLog = $bdLog->lista();

$tpl = new Template(DIR_TEMPLATE."/log/lista.html");
$tpl->USUARIO_LOGADO = $bdUsuario->getLogin();
$tpl->LINK_LOGOUT = URL_ROOT.'/controlador/usuario_controlador.php?acao=logout';
$tpl->block('BLOCO_LOGADO');
$tpl->NOME_BANCO = $bdBanco->getNome();
foreach ($vetLog as $bdLog) {
    $tpl->DATA = date('d/m/Y H:i:s', strtotime($bdLog->getData()));
    $tpl->LOG = substr($bdLog->getLog(), 0, 100);
    $tpl->LINK_VISUALIZAR = URL_ROOT.'/controlador/log_controlador.php?acao=visualiza&id_log='.$bdLog->getIdLog();
    $tpl->block('BLOCO_LOGS');
}
$tpl->show();

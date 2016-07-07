<?php
require_once(DIR_ROOT."/lib/raelgc/view/Template.php");
use raelgc\view\Template;

$bdLog = new BDLog();
$bdLog->carregaLog($_GET["id_log"]);
$tpl = new Template(DIR_TEMPLATE."/log/visualiza.html");
$tpl->DATA_LOG = $bdLog->getData();
$tpl->LOG = nl2br($bdLog->getLog());
if($bdLog->getErro()){
  $tpl->ERRO = nl2br($bdLog->getErro());
  $tpl->block('BLOCO_ERRO');
}
$tpl->show();

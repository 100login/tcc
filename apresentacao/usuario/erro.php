<?php
require_once(DIR_ROOT."/lib/raelgc/view/Template.php");
use raelgc\view\Template;

$tpl = new Template(DIR_TEMPLATE."/usuario/erro.html");
$tpl->MENSAGEM = $_SESSION["mensagem_pagina_erro"];
$tpl->show();

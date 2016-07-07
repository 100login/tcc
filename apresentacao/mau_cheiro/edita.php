<?php
require_once(DIR_ROOT."/lib/raelgc/view/Template.php");
use raelgc\view\Template;

$bdMauCheiro = new BDMauCheiro();
$bdMauCheiro->carregaMauCheiro("id_mau_cheiro",$_GET["id_mau_cheiro"]);

$tpl = new Template(DIR_TEMPLATE."/mau_cheiro/novo.html");
if($_SESSION["mensagem_erro"]){
    $tpl->RESULTADO_CLASSE = "alert alert-danger";
    $tpl->RESULTADO = $_SESSION["mensagem_erro"];
    $_SESSION["mensagem_erro"] = "";
}

$tpl->USUARIO_LOGADO = $bdUsuario->getLogin();
$tpl->LINK_LOGOUT = URL_ROOT.'/controlador/usuario_controlador.php?acao=logout';
$tpl->block('BLOCO_LOGADO');
$tpl->ACAO = "Editar";
$tpl->ID_MAU_CHEIRO = $bdMauCheiro->getidMauCheiro();
$tpl->CLASSE = $bdMauCheiro->getNomeClasse().".php";
$tpl->DESCRICAO = $bdMauCheiro->getDescricao();

$tipos = array('A'=>'Arquitetural','E'=>'Estrutural','I'=>'Integridade Referencial','Q'=>'Qualidade de dados');

foreach ($tipos as $codigo => $tipo) {
    $tpl->TIPO_CODIGO = $codigo;
    $tpl->TIPO_VALOR = $tipo;
    $tpl->TIPO_SELECIONADO = $codigo == $bdMauCheiro->getTipo()?'selected':'';
    $tpl->block('BLOCO_TIPOS');
}

$tpl->show();
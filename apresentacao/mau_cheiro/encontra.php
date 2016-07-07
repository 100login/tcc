<?php
require_once(DIR_ROOT."/lib/raelgc/view/Template.php");
use raelgc\view\Template;

$tpl = new Template(DIR_TEMPLATE."/mau_cheiro/exibe.html");
$tpl->USUARIO_LOGADO = $bdUsuario->getLogin();
$tpl->LINK_LOGOUT = URL_ROOT.'/controlador/usuario_controlador.php?acao=logout';
$tpl->block('BLOCO_LOGADO');
$vetIconesTipos = array("A"=>"fa-university","E"=>"fa-building","I"=>"fa-key","Q"=>"fa-database");
$tpl->NOME_BANCO = $bdBanco->getNome();
$tpl->HOST_BANCO = $bdBanco->getHost();

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

$banco = new Banco($bdBanco->getNome(),$bdBanco->getNomeUsuario(),$bdBanco->getSenha(),$bdBanco->getHost(),$bdBanco->getPorta());
$mauCheiroReflexao = new ReflectionClass($bdMauCheiro->getNomeClasse());
$mauCheiro = $mauCheiroReflexao->newInstance();
$retorno = $mauCheiro->encontra($banco);
$temRefatoracao = strpos($retorno,"Refatoração:"); 
$tpl->ICONE_SELECIONADO = $vetIconesTipos[$bdMauCheiro->getTipo()];

foreach ($vetMausCheiros as $bdMauCheiro) {
    if($bdMauCheiro->getIdMauCheiro() == $_GET['id_mau_cheiro']){
        $tpl->CLASSE_SELECIONADO = 'btn-primary';
        $tpl->CLASSE_NAO_SELECIONADO = $temRefatoracao?'':'hidden';
    }else{
        $tpl->CLASSE_SELECIONADO = 'btn-default'; 
        $tpl->CLASSE_NAO_SELECIONADO = 'hidden';
    }
    $tpl->ICONE = $vetIconesTipos[$bdMauCheiro->getTipo()];
    $tpl->DESCRICAO = $bdMauCheiro->getDescricao();
    $tpl->LINK = URL_ROOT.'/controlador/mau_cheiro_controlador.php?acao=encontra&id_banco='.$_GET['id_banco'].'&id_mau_cheiro='.$bdMauCheiro->getidMauCheiro();
    $tpl->block('BLOCO_MAUS_CHEIROS');
}          

$tpl->ID_BANCO = $_GET['id_banco'];
$tpl->ID_MAU_CHEIRO = $_GET['id_mau_cheiro'];
$tpl->TITULO_RESULTADO = 'Resultado da busca do mau cheiro selecionado';

if($_SESSION['sql']){
    $tpl->SQL = $_SESSION['sql'];
    $_SESSION['sql'] = "";
}else{
    $tpl->SQL = $retorno;
}
$tpl->show();
<?php
require_once(DIR_ROOT."/lib/raelgc/view/Template.php");
use raelgc\view\Template;

$bdBanco = new BDBanco();
$bdBanco->carregaBanco($_GET["id_banco"]);
$tpl = new Template(DIR_TEMPLATE."/Banco/novo.html");
$tpl->USUARIO_LOGADO = $bdUsuario->getLogin();
$tpl->LINK_LOGOUT = URL_ROOT.'/controlador/usuario_controlador.php?acao=logout';
$tpl->block('BLOCO_LOGADO');
$tpl->ACAO = "Editar";
$tpl->ID_BANCO = $bdBanco->getidBanco();
$tpl->HOST = $bdBanco->getHost();
$tpl->BANCO = $bdBanco->getNome();
$tpl->USUARIO = $bdBanco->getNomeUsuario();
$tpl->SENHA = $bdBanco->getSenha();
$tpl->PORTA = $bdBanco->getPorta();    
$tpl->PADRAO_CHAVE_PRIMARIA = $bdBanco->getPadraoChavePrimaria(); 
$tpl->PADRAO_CHAVE_ESTRANGEIRA = $bdBanco->getPadraoChaveEstrangeira(); 
$tpl->QUANTIDADE_MEMORIA = $bdBanco->getQuantidadeMemoria(); 
$tpl->show();
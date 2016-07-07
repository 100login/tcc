<?php

error_reporting(E_ERROR);
session_start();

define('DIR_ROOT',$_SERVER['DOCUMENT_ROOT'].'/tcc');
define('DIR_MODELO',DIR_ROOT.'/modelo');
define('DIR_MAUS_CHEIRO',DIR_MODELO.'/maus_cheiro');
define('DIR_ESTRUTURA',DIR_MODELO.'/estrutura');
define('DIR_DAO',DIR_ROOT.'/dao');
define('DIR_TEMPLATE',DIR_ROOT.'/template');
define('DIR_CONTROLADOR',DIR_ROOT.'/controlador');
define('DIR_APRESENTACAO',DIR_ROOT.'/apresentacao');
define('URL_ROOT',$_SERVER['REQUEST_SCHEME']."://".$_SERVER["HTTP_HOST"]."/tcc");
define('DIR_MAU_CHEIRO_USUARIO',DIR_MODELO."/mau_cheiro_usuario/");

require_once(DIR_ESTRUTURA.'/BDBanco.php');
require_once(DIR_ESTRUTURA.'/BDMauCheiro.php');
require_once(DIR_ESTRUTURA.'/BDUsuario.php');
require_once(DIR_ESTRUTURA.'/BDLog.php');
require_once(DIR_ESTRUTURA.'/Database.php');
require_once(DIR_ESTRUTURA.'/Banco.php');
require_once(DIR_ESTRUTURA.'/Tabela.php');
require_once(DIR_ESTRUTURA.'/View.php');
require_once(DIR_ESTRUTURA.'/Trigger.php');
require_once(DIR_ESTRUTURA.'/Rotina.php');
require_once(DIR_ESTRUTURA.'/Coluna.php');
require_once(DIR_ESTRUTURA.'/Indice.php');
require_once(DIR_ESTRUTURA.'/iMauCheiro.php'); 
require_once(DIR_ESTRUTURA.'/MauCheiroTabela.php'); 
require_once(DIR_ESTRUTURA.'/MauCheiroColuna.php'); 
require_once(DIR_ESTRUTURA.'/MauCheiroIndice.php'); 
require_once(DIR_MAUS_CHEIRO.'/ChavePrimariaOrdenacao.php');
require_once(DIR_MAUS_CHEIRO.'/IndiceComColunaNula.php'); 
require_once(DIR_MAUS_CHEIRO.'/ChaveEstrangeiraDiferentePrimaria.php');
require_once(DIR_MAUS_CHEIRO.'/NomeTabelaPalavraReservada.php');
require_once(DIR_MAUS_CHEIRO.'/NomeColunaPalavraReservada.php');
require_once(DIR_MAUS_CHEIRO.'/TabelaSemChavePrimaria.php');
require_once(DIR_MAUS_CHEIRO.'/TabelaTodosCamposAnulaveis.php');
require_once(DIR_MAUS_CHEIRO.'/TabelaMultiUso.php');
require_once(DIR_MAUS_CHEIRO.'/TabelaSemRegistro.php');
require_once(DIR_MAUS_CHEIRO.'/TabelaMuitasLinhas.php');
require_once(DIR_MAUS_CHEIRO.'/ColunaSemChaveEstrangeira.php');
require_once(DIR_MAUS_CHEIRO.'/ChaveEstrangeiraSemIndice.php');
require_once(DIR_DAO.'/TabelaDAO.php');
require_once(DIR_DAO.'/ViewDAO.php');
require_once(DIR_DAO.'/TriggerDAO.php');
require_once(DIR_DAO.'/RotinaDAO.php');
require_once(DIR_DAO.'/ColunaDAO.php'); 
require_once(DIR_DAO.'/IndiceDAO.php');
require_once(DIR_DAO.'/BancoDAO.php');
require_once(DIR_DAO.'/BDUsuarioDAO.php');
require_once(DIR_DAO.'/BDBancoDAO.php'); 
require_once(DIR_DAO.'/BDMauCheiroDAO.php'); 
require_once(DIR_DAO.'/BDLogDAO.php'); 

die(md5(md5(md5('desenvolvedor'))));

function verificaAutenticacao() {
    global $bdUsuario;
    if (!carregaUsuarioSessao()){
        $_SESSION["mensagem_pagina_erro"] = "É necessário estar logado para acessar este conteúdo.";
        header("Location: ".URL_ROOT."/controlador/usuario_controlador.php?acao=erro");
    }else{
        if($_GET['id_banco']){
            $temBanco = false;
            foreach ($bdUsuario->getBancos() as $bdBanco) {
                if($bdBanco->getIdBanco() == $_GET['id_banco']) {
                    $temBanco = true; 
                    break;
                }
            }
            if(!$temBanco){
                $_SESSION["mensagem_pagina_erro"] = "Você não pode acessar o conteúdo deste banco";
                header("Location: ".URL_ROOT."/controlador/usuario_controlador.php?acao=erro");
            }
        }
    }
}

function carregaUsuarioSessao() {
    global $bdUsuario;
    if ($_SESSION['idUsuario']) {
        $bdUsuario = new BDUsuario();
        $bdUsuario->carregaUsuario('u.id_usuario', $_SESSION['idUsuario']);
    }else{
        $bdUsuario = null;
    }
    return $bdUsuario;
}

?>
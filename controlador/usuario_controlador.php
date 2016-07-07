<?php
require_once($_SERVER['DOCUMENT_ROOT']."/tcc/config.php");

carregaUsuarioSessao();

switch ($_GET['acao']) {
    case 'novo':
        require_once(DIR_APRESENTACAO."/usuario/novo.php");
    break;
    case 'salvar':
        $bdUsuario = new BDUsuario($_POST["id_usuario"],$_POST["login"],$_POST["senha"]);
        if($bdUsuario->salvar()){
            $_SESSION["mensagem_sucesso"] = "Usuário ".($_POST["id_usuario"]?"atualizado":"cadastrado")." com sucesso.";
            header("Location: ".URL_ROOT."/controlador/".($_POST["id_usuario"]?"banco_controlador.php?acao=lista":"usuario_controlador.php?acao=login"));
        }else{
            $_SESSION["mensagem_erro"] = "Login já está em uso.";
            header("Location: ".$_SERVER["HTTP_REFERER"]);
            
        }
    break;
    case 'login':
        require_once(DIR_APRESENTACAO."/usuario/login.php");
    break;
    case 'logar':
        $bdUsuario = new BDUsuario(null,$_POST["login"],$_POST["senha"]);
        if($bdUsuario->logar()){
            header("Location: ".URL_ROOT."/controlador/banco_controlador.php?acao=lista");
        }else{
            $_SESSION["mensagem_erro"] = "Não foi possível realizar o login";
            header("Location: ".URL_ROOT."/controlador/usuario_controlador.php?acao=login");
        }
    break;
    case 'logout':
        if($bdUsuario->logout()){
            header("Location: ".URL_ROOT."/controlador/usuario_controlador.php?acao=login");
        }else{
            $_SESSION["mensagem_erro"] = "Não foi possível realizar o logout";
            header("Location: ".$_SERVER["HTTP_REFERER"]);
        }
    break;
    case 'erro':
        require_once(DIR_APRESENTACAO."/usuario/erro.php");
    break;
    default:
        break;
}

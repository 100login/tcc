<?php
require_once($_SERVER['DOCUMENT_ROOT']."/tcc/config.php");

verificaAutenticacao();

switch ($_GET["acao"]) {
    case 'lista':
        require_once(DIR_APRESENTACAO."/banco/lista.php");
    break;
    case "novo":
        require_once(DIR_APRESENTACAO."/banco/novo.php");
    break;
    case "salvar":
        $bdBanco = new BDBanco($_POST["id_banco"],$_POST["nome"],$_POST["nome_usuario"],$_POST["senha"],$_POST["porta"],$_POST["host"],$bdUsuario);
        $bdBanco->setPadraoChavePrimaria($_POST["padrao_chave_primaria"]);
        $bdBanco->setPadraoChaveEstrangeira($_POST["padrao_chave_estrangeira"]);
        $bdBanco->setQuantidadeMemoria($_POST["quantidade_memoria"]);
        if($bdBanco->salvar()){
            $_SESSION["mensagem_sucesso"] = "Banco ".($_POST["id_banco"]?"atualizado":"cadastrado")." com sucesso.";
            header("Location: http://localhost:8080/tcc/controlador/banco_controlador.php?acao=lista");
        }else{
            $_SESSION["mensagem_erro"] = "Falha ao ".($_POST["id_banco"]?"atualizar":"cadastrar")." o banco.";
            header("Location: ".$_SERVER["HTTP_REFERER"]);
            
        }
    break;
    case "edita":
        require_once(DIR_APRESENTACAO."/banco/edita.php");
    break;
    case "deletar":
        $bdBanco = new BDBanco();
        $bdBanco->carregaBanco($_GET["id_banco"]);
        if($bdBanco->deletar()){
            $_SESSION["mensagem_sucesso"] = "Banco ".$bdBanco->getNome()." em ".$bdBanco->getHost()." deletado com sucesso.";
        }else{
            $_SESSION["mensagem_erro"] = "Falha ao deletar o banco ".$bdBanco->getNome()." em ".$bdBanco->getHost().".";
        }
        header("Location: ".$_SERVER["HTTP_REFERER"]);
    break;
    default:
        break;
}

<?php
require_once($_SERVER['DOCUMENT_ROOT']."/tcc/config.php");

verificaAutenticacao();

$bdBanco = new BDBanco();
$bdBanco->carregaBanco($_GET['id_banco']);
$_SESSION['id_banco'] = $_GET['id_banco']; 
$bdMauCheiro = new BDMauCheiro();
$bdMauCheiro->carregaMauCheiro('id_mau_cheiro',$_GET['id_mau_cheiro']);
$bdMauCheiro->setUsuario($bdUsuario);
$vetMausCheiros = $bdMauCheiro->lista();

foreach ($bdUsuario->getMausCheiros() as $bdMauCheiroInclude) {
    require_once(DIR_MAU_CHEIRO_USUARIO.'/'.$bdMauCheiroInclude->getNomeClasse().".php");
    
}

switch ($_GET['acao']) {
    case 'lista':
        require_once(DIR_APRESENTACAO."/mau_cheiro/lista.php");
    break;
    case 'novo':
        require_once(DIR_APRESENTACAO."/mau_cheiro/novo.php");
    break;
    case 'edita':
        require_once(DIR_APRESENTACAO."/mau_cheiro/edita.php");
    break;
    case 'salvar':
        $vetNomeArquivo = explode('.', $_FILES['arquivo']['name']);
        $nomeClasse = $vetNomeArquivo[0];
        $extensaoArquivo = $vetNomeArquivo[1];
        $nomeArquivo = $_FILES['arquivo']['name'];
        
        $bdMauCheiro = new BDMauCheiro($_POST['id_mau_cheiro'],$nomeClasse,$_POST['descricao'],$_POST['tipo'],$bdUsuario);
        $bdMauCheiroExistente = new BDMauCheiro();
        $bdMauCheiroExistente->carregaMauCheiro("id_mau_cheiro", $_POST['id_mau_cheiro']);
        if($bdMauCheiro->salvar()){
            if($_FILES['arquivo']['name']){
                if($_POST['id_mau_cheiro']) unlink(DIR_MAU_CHEIRO_USUARIO.$bdMauCheiroExistente->getNomeClasse().".php");
                
                $uploadArquivo = DIR_MAU_CHEIRO_USUARIO . $nomeArquivo;
                if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadArquivo)) {
                    $_SESSION["mensagem_sucesso"] = "Mau cheiro ".($_POST["id_mau_cheiro"]?"atualizado":"cadastrado")." com sucesso.";
                    header("Location: http://localhost:8080/tcc/controlador/mau_cheiro_controlador.php?acao=lista");
                    die();
                }
            }else{
                 $_SESSION["mensagem_sucesso"] = "Mau cheiro ".($_POST["id_mau_cheiro"]?"atualizado":"cadastrado")." com sucesso.";
                 header("Location: http://localhost:8080/tcc/controlador/mau_cheiro_controlador.php?acao=lista");
                 die();
            }
        }
        $_SESSION["mensagem_erro"] = "Falha ao ".($_POST["id_mau_cheiro"]?"atualizar":"cadastrar")." o mau cheiro.";
        header("Location: ".$_SERVER["HTTP_REFERER"]);
    break;
    case 'deletar':
        $bdMauCheiro = new BDMauCheiro();
        $bdMauCheiro->carregaMauCheiro('id_mau_cheiro',$_GET["id_mau_cheiro"]);
        if($bdMauCheiro->deletar()){
            $_SESSION["mensagem_sucesso"] = "Mau cheiro deletado com sucesso.";
        }else{
            $_SESSION["mensagem_erro"] = "Falha ao deletar o mau cheiro.";
        }
        header("Location: ".$_SERVER["HTTP_REFERER"]);
    break;
    case 'exibe':
        require_once(DIR_APRESENTACAO."/mau_cheiro/exibe.php");
    break;
    case 'encontra':
        require_once(DIR_APRESENTACAO."/mau_cheiro/encontra.php");
    break;
    case 'refatora':
        $banco = new Banco($bdBanco->getNome(),$bdBanco->getNomeUsuario(),$bdBanco->getSenha(),$bdBanco->getHost(),$bdBanco->getPorta());
        $bdLog = new BDLog();
        $bdLog->setBanco($bdBanco);
        $bdLog->setMauCheiro($bdMauCheiro);
        $erro = $banco->refatora($_POST['sql'],$bdLog);
        
         if($erro){
            $_SESSION['sql'] = $_POST['sql'];
            $_SESSION["mensagem_erro"] = $erro;
        }else{
            $_SESSION["mensagem_sucesso"] = "Refatoração realizada com sucesso.";
            
        }
        
        header("Location: ".URL_ROOT."/controlador/mau_cheiro_controlador.php?acao=encontra&id_banco=".$_GET['id_banco']."&id_mau_cheiro=".$_GET['id_mau_cheiro']);
    break;
    default:
        break;
}

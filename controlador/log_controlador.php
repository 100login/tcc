<?php
require_once($_SERVER['DOCUMENT_ROOT']."/tcc/config.php");

verificaAutenticacao();

switch ($_GET["acao"]) {
    case 'lista':
        require_once(DIR_APRESENTACAO."/log/lista.php");
    break;
      case 'visualiza':
        require_once(DIR_APRESENTACAO."/log/visualiza.php");
    break;
    default:
        break;
}

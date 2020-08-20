<?php
require_once 'app/Controller/AddController.php';
require_once 'app/Core/Core.php';
require_once 'Lib/Database/Connection.php';
require_once 'app/Controller/ErroController.php';
require_once 'app/Controller/HomeController.php';
require_once 'app/Controller/RevisaoController.php';
require_once 'app/Model/Palavra.php';
require_once 'app/Model/PalavrasEsquecidas.php';
require_once 'app/Model/Revisao.php';
require_once 'app/Controller/TreinarController.php';
require_once 'vendor/autoload.php';

date_default_timezone_set('Brazil/East');//pra evitar um bug de fusohorario

session_start();

if (isset($_SESSION['horario_session_aberta']) && $_SESSION['horario_session_aberta'] + 2 * 60 < time()) { //reseta a data a cada 2 horas
    /*
     * Usado para fechar a session pois,
     * em navegadores que guardam a session (abrir na página que você parou) a session não é destruida,
     * isso causa um bug, se a aplicação for aberta dias depois a data vai permanecer a mesma pois a session não foi fechada.
     */
     session_unset();
     session_destroy();
}


if(!isset($_SESSION['data'])){ 
    $_SESSION['data'] = date('Y-m-d'); //adiciona a data para a session se ela não esta setada, ver HomeController::setData()
    $_SESSION['horario_session_aberta'] = time();
}



$template = file_get_contents('app/Template/estrutura.html');
ob_start();
    $core = new core;
    $core->start($_GET);
    $saida = ob_get_contents();
    
ob_end_clean();
$tplPronto = str_replace('{{area_dinamica}}', $saida, $template);
echo $tplPronto;
//Esta aplicação PHP foi criada usando o template e conceitos ensinados por Rafael Capoani https://github.com/RafaelCapo https://www.youtube.com/user/Suportedeweb
//https://youtu.be/KGFaT0A7iTI Apresentação da ferramenta
//Marcelo Fonseca https://github.com/MarceloScarpinFonseca 18/08/2020 Word Reminder1.2
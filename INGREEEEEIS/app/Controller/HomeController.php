<?php

class HomeController {
    public function index() {
        try {
            $palavras = Palavra::selecionaTodos();
            $loader = new \Twig\Loader\FilesystemLoader('app/View');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('home.html');
            $parametros = array();
            $parametros['palavras'] = $palavras;
            $conteudo = $template->render($parametros);
            echo $conteudo;
        
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        
        
    }
    public function escolherData() { //aciona a view controlada pelo twig para inserir a data
        
        $dataAtual = $_SESSION['data'];
        $loader = new \Twig\Loader\FilesystemLoader('app/View');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('data.html');
        $parametros = array();
        $parametros['data'] = $dataAtual;
        $conteudo = $template->render($parametros);
        echo $conteudo;
    }
    public static function setData() {
        /*
         * Esta função foi adicionada para modificar a data que o programa ira operar,
         * por exemplo, caso o usuario queira usar o programa após a meia noite mas com a data do dia anterior;
         * Como a data esta gravada na session se o usuario esta usando o programa entre 23:59 e 00:00 a data do programa não muda automaticamente, apenas se ele fechar e abrir a janela
         * neste caso caso o usuario adicione a palavra após a meia noite automaticamente a data vai continuar sendo a mesma.
         */
        
        $data = $_POST['data'];
        $_SESSION['data'] = $data;
        $_SESSION['horario_session_aberta'] = time();
        
        $dataAtual = $_SESSION['data'];
        $loader = new \Twig\Loader\FilesystemLoader('app/View');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('data.html');
        $parametros = array();
        $parametros['data'] = $dataAtual;
        $conteudo = $template->render($parametros);
        echo $conteudo;
    }
}

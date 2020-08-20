<?php

class AddController {
    public function index() {
        try {
            //echo 'add';
            
            
            //$palavras = Palavra::selecionaTodos();
            $loader = new \Twig\Loader\FilesystemLoader('app/View');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('add.html');
            $parametros = array();
            //$parametros['palavras'] = $palavras;
            $conteudo = $template->render($parametros);
            echo $conteudo;
        
        
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        
        
    }
    public function add() {
        try{
            $novaPalavra = $_POST;
            $novaPalavra['DataEsquecida'] = '0000-00-00';
            $novaPalavra['Acertos'] = 0;
            $novaPalavra['Erros'] = 0;
            $novaPalavra['UltimaRev'] = '0000-00-00';
            $novaPalavra['DataAdd'] = $_SESSION['data'];
            
            $palavra = $novaPalavra['Palavra'];
            $idNovo = Palavra::getIID();//coleta o "próximo id" para adicionar à lista de revisões
            $existe = Palavra::selecionaPorPalavra($palavra); //verifica se a palavra existe no banco de dados
            
            if ($existe != true && $palavra != null){
                RevisaoController::addRev($idNovo);
                Palavra::add($novaPalavra);
                echo 'Palavra inserida normalmente<br>';
            }else{
                echo 'Palavra não inserida!<br>';
            }
            
            //var_dump($novaPalavra);
            $this->index();
        } catch (Exception $ex) {

        }
        
    }
    public function modificar() {
        try {
            $id = $_GET['id'];
            $palavra = Palavra::selecionaPorId($id);
            $loader = new \Twig\Loader\FilesystemLoader('app/View');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('modificar.html');
            $parametros = array();
            $parametros['palavra'] = $palavra;
            $conteudo = $template->render($parametros);
            echo $conteudo;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        
    }
    public function update() {
        try{
            $palavra = $_POST;
            Palavra::update($palavra);
            $palavra = Palavra::selecionaPorId($palavra['id']);
            $loader = new \Twig\Loader\FilesystemLoader('app/View');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('palavra.html');
            $parametros = array();
            $parametros['palavra'] = $palavra;
            $conteudo = $template->render($parametros);
            echo $conteudo;
        } catch (Exception $ex) {

        }
    }
    
}

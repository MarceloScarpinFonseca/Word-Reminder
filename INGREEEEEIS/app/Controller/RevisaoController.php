<?php


class RevisaoController {
    public function index() {
        $rev = Revisao::selecionarTodos();
        $revArray = array();
        $palavras = array();
        for ($index = 0; $index < count($rev); $index++) {
            $revArray[] = (array) $rev[$index];
            $revId = $revArray[$index]["IdPalavra"];
            $palavra = Palavra::selecionaPorId($revId);
            $palavra = (array) $palavra;
            $palavra['Ordem'] = $revArray[$index]["Ordem"];
            $palavra['DataRev'] = $revArray[$index]["Data"];
            $palavra['idRev'] = $revArray[$index]["id"];
            $palavras[] = $palavra;
        }
        $loader = new \Twig\Loader\FilesystemLoader('app/View');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('revisaoLista.html');
        $parametros = array();
        $parametros['palavras'] = $palavras;
        $parametros['revisao'] = $rev;
        $conteudo = $template->render($parametros);
        echo $conteudo;
    }
    public function addRev($id) {//id da palavra
        /*
         * Esta função adiciona uma determinada quantidade de revisões seguindo a sequência de Fibonacci;
         * Ex: para 10 fases de revisão data + 1 2 3 5 8 13 21 34 55 89 dias;
         */
        $data = $_SESSION['data'];
        $revisao = array();
        $revisao['id'] = $id; //informa o id da palavra ligada a essa revisão
        Revisao::deletarPorId($id);//deleta todas as revisões de uma palavra (id palavra)
        if(!isset($fases)){
            $fases = 10;
        }
        for ($index = 0; $index < $fases; $index++) {
            if(!isset($b)){
                $a = 0;
                $b = 1;
            }
            $fi = $a + $b;
            $a = $b;
            $b = $fi;
            $dataRev = date('Y-m-d', strtotime($data. ' + ' . $fi .' days')); 
            $revisao['Data'] = $dataRev;
            $ordem = $index + 1;
            $revisao['Ordem'] = $ordem; //grava qual é a ordem/fase de revisão
            Revisao::add($revisao);
        }
    }
    public function editar() {
        $id = $_GET['id']; // este é o id da revisao
        $rev = Revisao::selecionaPorId($id);
        $rev = (array) $rev;
        $idPalavra = $rev['IdPalavra'];
        
        $palavra = Palavra::selecionaPorId($idPalavra);
        $palavra = (array) $palavra;
        
        $palavra['Ordem'] = $rev["Ordem"];
        $palavra['IdPalavra'] = $rev['IdPalavra'];;
        $palavra['DataRev'] = $rev["Data"];
        $palavra['idRev'] = $rev["id"]; //ou $id
        
        $loader = new \Twig\Loader\FilesystemLoader('app/View');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('revisaoEditada.html');
        $parametros = array();
        $parametros['palavra'] = $palavra;
        $parametros['revisao'] = $rev; //useless
        $conteudo = $template->render($parametros);
        echo $conteudo;
    }
    public function editarGravar() {
        $rev = $_POST;
        $rev['Data'] = $rev['DataRev'];
        Revisao::update($rev);
        echo 'Alterações gravadas com sucesso!';
    }
    public function editarApagar() {
        $id = $_GET['id'];
        Revisao::deletarPorIdRev($id);
        echo 'Revisão apagada!';
    }
    public function addAvulsa() {
        $id = $_GET['id']; // este é o id da palavra
        $rev = Revisao::selecionaPorId($id);
        $rev = (array) $rev;
                
        $palavra = Palavra::selecionaPorId($id);
        $palavra = (array) $palavra;
        $palavra['IdPalavra'] = $id;
        
        $loader = new \Twig\Loader\FilesystemLoader('app/View');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('revisaoAdicionar.html');
        $parametros = array();
        $parametros['palavra'] = $palavra;
        $parametros['revisao'] = $rev; //useless
        $conteudo = $template->render($parametros);
        echo $conteudo;
    }
    public function addAvulsaGravar() {
        $rev = $_POST;
        Revisao::add($rev);
        echo 'Revisão avulsa agendada com sucesso!';
    }
    public function addConjunto() {//adiciona conjunto de revisões 
        $id = $_GET['id']; // este é o id da palavra
        $rev = Revisao::selecionaPorId($id);
        $rev = (array) $rev;
                
        $palavra = Palavra::selecionaPorId($id);
        $palavra = (array) $palavra;
        $palavra['IdPalavra'] = $id;
        
        $loader = new \Twig\Loader\FilesystemLoader('app/View');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('revisaoConjunto.html');
        $parametros = array();
        $parametros['palavra'] = $palavra;
        $conteudo = $template->render($parametros);
        echo $conteudo;
    }
    public function addConjuntoGravar() { //apaga todas as revisões agendadas e escreve uma quantidade personalizada de de risões (respeitado a sequência de Fibonacci)
        $id = $_POST['id'];
        $fases = $_POST['qtd'];
        $data = $_SESSION['data'];
        $revisao = array();
        $revisao['id'] = $id; //informa o id da palavra ligada a essa revisão
        Revisao::deletarPorId($id);//deleta todas as revisões de uma palavra (id palavra)
        if(!isset($fases)){
            $fases = 10;
        }
        for ($index = 0; $index < $fases; $index++) {
            if(!isset($b)){
                $a = 0;
                $b = 1;
            }
            $fi = $a + $b;
            $a = $b;
            $b = $fi;
            $dataRev = date('Y-m-d', strtotime($data. ' + ' . $fi .' days')); 
            $revisao['Data'] = $dataRev;
            $ordem = $index + 1;
            $revisao['Ordem'] = $ordem; //grava qual é a ordem/fase de revisão
            Revisao::add($revisao);
        }
        echo 'Revisões agendadas com sucesso!';
    }
    public function apagarRevs() { //recebe o id da palavra e apaga todas as revisões
        $id = $_GET['id'];
        Revisao::deletarPorId($id);
        echo 'Revisões apagadas com sucesso!';        
    }
}

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
    public function addRev($id) { //adiciona todas as 10 fases de revisão
        $revisao = array();
        $revisao['id'] = $id;
        Revisao::deletarPorId($id);
        for ($index = 0; $index < 11; $index++) { //1 2 3 5 8 13 21 34 55 89
            switch ($index) {
                case 1:
                    $data = $_SESSION['data'];
                    $dataM = date('Y-m-d', strtotime($data. ' + 1 days'));
                    $revisao['Data'] = $dataM;
                    $revisao['Ordem'] = 1;
                    Revisao::add($revisao);


                    break;
                case 2:
                    $data = $_SESSION['data'];
                    $dataM = date('Y-m-d', strtotime($data. ' + 2 days'));
                    $revisao['Data'] = $dataM;
                    $revisao['Ordem'] = 2;
                    Revisao::add($revisao);


                    break;
                case 3:
                    $data = $_SESSION['data'];
                    $dataM = date('Y-m-d', strtotime($data. ' + 3 days'));
                    $revisao['Data'] = $dataM;
                    $revisao['Ordem'] = 3;
                    Revisao::add($revisao);


                    break;
                case 4:
                    $data = $_SESSION['data'];
                    $dataM = date('Y-m-d', strtotime($data. ' + 5 days'));
                    $revisao['Data'] = $dataM;
                    $revisao['Ordem'] = 4;
                    Revisao::add($revisao);


                    break;
                case 5:
                    $data = $_SESSION['data'];
                    $dataM = date('Y-m-d', strtotime($data. ' + 8 days'));
                    $revisao['Data'] = $dataM;
                    $revisao['Ordem'] = 5;
                    Revisao::add($revisao);


                    break;
                case 6:
                    $data = $_SESSION['data'];
                    $dataM = date('Y-m-d', strtotime($data. ' + 13 days'));
                    $revisao['Data'] = $dataM;
                    $revisao['Ordem'] = 6;
                    Revisao::add($revisao);


                    break;
                case 7:
                    $data = $_SESSION['data'];
                    $dataM = date('Y-m-d', strtotime($data. ' + 21 days'));
                    $revisao['Data'] = $dataM;
                    $revisao['Ordem'] = 7;
                    Revisao::add($revisao);


                    break;
                case 8:
                    $data = $_SESSION['data'];
                    $dataM = date('Y-m-d', strtotime($data. ' + 34 days'));
                    $revisao['Data'] = $dataM;
                    $revisao['Ordem'] = 8;
                    Revisao::add($revisao);


                    break;
                case 9:
                    $data = $_SESSION['data'];
                    $dataM = date('Y-m-d', strtotime($data. ' + 55 days'));
                    $revisao['Data'] = $dataM;
                    $revisao['Ordem'] = 9;
                    Revisao::add($revisao);


                    break;
                case 10:
                    $data = $_SESSION['data'];
                    $dataM = date('Y-m-d', strtotime($data. ' + 89 days'));
                    $revisao['Data'] = $dataM;
                    $revisao['Ordem'] = 10;
                    Revisao::add($revisao);


                    break;

            }
            
            
            
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
    public function apagarRevs() { //recebe o id da palavra e apaga todas as revisões
        $id = $_GET['id'];
        Revisao::deletarPorId($id);
        echo 'Revisões apagadas com sucesso!';        
    }
}

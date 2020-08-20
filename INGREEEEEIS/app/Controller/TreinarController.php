<?php

class TreinarController {
    public function index() {//mostrar palavras esquecidas + revisões + aleatóreas
        try {
            $arrId = $this->criaRev();
            $qtd = count($arrId);
            $palavras = array();
            for ($index = 1; $index <= $qtd; $index++) { //recebe um array com os ids das palavras a serem treinadas(primeira key = 1)
                $palavra = Palavra::selecionaPorId($arrId[$index]);
                $palavras[] = $palavra;
            }
            $loader = new \Twig\Loader\FilesystemLoader('app/View');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('treinar.html');
            $parametros = array();
            $parametros['palavras'] = $palavras;
            $conteudo = $template->render($parametros);
            echo $conteudo;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function esqueceu() { //função usada para quando uma palavra foi esquecida individualmente (clicado manualmente pela home por exemplo) 
        try {
            $id = $_GET['id'];
            
            $palavra = Palavra::selecionaPorId($id);
            if($palavra->Dificuldade < 2){
               $palavra->Dificuldade = $palavra->Dificuldade + 1; 
            }
            $palavra->Erros = $palavra->Erros + 1;
            $palavra->UltimaRev = $_SESSION['data'];
            $palavra->DataEsquecida = $_SESSION['data'];
            $palavra = (array) $palavra; // esta linha converte o objeto para array
            Palavra::update($palavra);
            //PalavrasEsquecidas::add($id); esta tabela do banco de dados não é mais utilizada pelo programa
            RevisaoController::addRev($id);
            
            $palavra = Palavra::selecionaPorId($id);
            $loader = new \Twig\Loader\FilesystemLoader('app/View');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('palavraEsquecida.html');
            $parametros = array();
            $parametros['palavra'] = $palavra;
            $conteudo = $template->render($parametros);
            echo $conteudo;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function esquecidas() { //função usada para quando as palavras da pagina treinar foram esquecidas
        try {
            $keys = array_keys($_POST);
            $valores = array_values($_POST);
            $palavrasEsquecidas = array(); //array de palavras (objetos) esquecidas para o twig
            for ($index = 0; $index < count($_POST); $index++) {
                //associar as key (ids) com os valores do post!!
                switch ($valores[$index]) {
                    case 0:
                        $palavra = Palavra::selecionaPorId($keys[$index]);
                        //se a dificuldade é maior que 0 e se a ultima rev não foi hj, a dificulda da palavra abaixa um ponto, se acertada
                        if($palavra->Dificuldade > 0 && $palavra->UltimaRev != $_SESSION['data']){ 
                            $palavra->Dificuldade = $palavra->Dificuldade - 1; 
                        }
                        $palavra->UltimaRev = $_SESSION['data'];
                        $palavra->Acertos = $palavra->Acertos + 1;
                        $palavra = (array) $palavra; // esta linha converte o objeto para array
                        Palavra::update($palavra);
                        Revisao::apagaMenorData($palavra['id']);
                        break;
                    case 1:
                        $palavra = Palavra::selecionaPorId($keys[$index]);
                        if($palavra->Dificuldade < 2){
                        $palavra->Dificuldade = $palavra->Dificuldade + 1; 
                        }
                        $palavra->Erros = $palavra->Erros + 1;
                        $palavra->UltimaRev = $_SESSION['data'];
                        $palavra->DataEsquecida = $_SESSION['data'];
                        $palavrasEsquecidas[] = $palavra;
                        $palavra = (array) $palavra; // esta linha converte o objeto para array
                        Palavra::update($palavra);
                        //PalavrasEsquecidas::add($keys[$index]); não usado
                        RevisaoController::addRev($keys[$index]);
                        break;
                }
                
            }
            $loader = new \Twig\Loader\FilesystemLoader('app/View');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('palavrasEsquecidas.html');
            $parametros = array();
            $parametros['palavra'] = $palavrasEsquecidas;
            $conteudo = $template->render($parametros);
            echo $conteudo;
            //var_dump($palavrasEsquecidas);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function criaRev() {//cria o array de palavras a serem treinadas
        $data = $_SESSION['data'];
        $revs = Revisao::idPorData($data); //id das palavras da lista de revisão de hoje e dos dias anteriores
        $idAtual = Palavra::getIID();
        $idAtual--; //Quantidade de palavras que existem
        $resultado = array();
        for ($index = 0; $index < 5; $index++) {
            $revs[] = rand(1, $idAtual); //palavra aleatoria
        }
        $revs = array_unique($revs); //remove redundancias
        $revs = array_values($revs); //para rearranjar as keys
        shuffle($revs); //embaralha (1.2)
        $qtd = count($revs); 
        for ($index1 = 1; $index1 <= $qtd; $index1++) {//converte o array para começar em 1
            $indexMenos = $index1 - 1;
            $resultado[$index1] = $revs[$indexMenos];
        }
        return $resultado;
    }
    public function palavrasDia() {
        $data = $_SESSION['data'];
        $loader = new \Twig\Loader\FilesystemLoader('app/View');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('palavraDiaData.html');
        $parametros = array();
        $parametros['data'] = $data;
        $conteudo = $template->render($parametros);
        echo $conteudo;
        
    }
    public function retornaPalavrasDia() {
        $data = $_POST['data'];
        $palavras = Palavra::palavrasDia($data);
        $qtd = Palavra::palavrasDiaId($data);
        $qtd = count($qtd);
        $loader = new \Twig\Loader\FilesystemLoader('app/View');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('palavrasDia.html');
        $parametros = array();
        $parametros['palavras'] = $palavras;
        $parametros['qtd'] = $qtd;
        $parametros['data'] = $data;
        $conteudo = $template->render($parametros);
        echo $conteudo;
    }
}

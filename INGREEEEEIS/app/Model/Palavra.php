<?php

class Palavra {
    public static function selecionaTodos(){
        $con = Connection::getConn();
        $sql = "SELECT * FROM palavra ORDER BY id DESC";
        $sql = $con->prepare($sql);
        $sql->execute();
        $resultado = array();
        
        while ($row = $sql->fetchObject('palavra')){ //pega os registros da classe Palavra e converte em um objeto (converte o array em objeto)
            $resultado[] = $row;
        }
        if (!$resultado){
           // throw new Exception("Não foi encontrado nenhum registro no banco de dados :("); Esta linha esta comentada porque se não existem palavras no banco de dados o programa para
        }
        return $resultado;
    }
    public static function selecionaPorId($idPalavra){
        $con = Connection::getConn();
        $sql = "SELECT * FROM palavra WHERE id = :id"; //o ":id" pode ser substiruido por "$id" (menos seguro)
        $sql = $con->prepare($sql);
        $sql->bindValue(':id', $idPalavra, PDO::PARAM_INT);
        $sql->execute();
        $resultado = $sql->fetchObject('Palavra');
        return $resultado;
    }
    public static function add($dadosPalavra) {
        $con = Connection::getConn();
        $sql = 'INSERT INTO palavra (Significados, DataAdd, Dificuldade, DataEsquecida, Palavra, Acertos, Erros, UltimaRev) VALUES (:sig, :dad, :dif, :dae, :pal, :ace, :err, :dar)';
        $sql = $con->prepare($sql);
        $sql->bindValue(':sig', $dadosPalavra['Significados']);
        $sql->bindValue(':dad', $dadosPalavra['DataAdd']);
        $sql->bindValue(':dif', $dadosPalavra['Dificuldade']);
        $sql->bindValue(':dae', $dadosPalavra['DataEsquecida']);
        $sql->bindValue(':pal', $dadosPalavra['Palavra']);
        $sql->bindValue(':ace', $dadosPalavra['Acertos']);
        $sql->bindValue(':err', $dadosPalavra['Erros']);
        $sql->bindValue(':dar', $dadosPalavra['UltimaRev']);
        $res = $sql->execute();
        if ($res == 0){ //se o objeto retornar falso
            throw new Exception("Falha ao inserir dados");
            return false;
        }
        return true;
    }
    public static function update($dadosPalavra) {
        $con = Connection::getConn();
        $sql = "UPDATE palavra SET Significados = :sig, DataAdd = :dad, Dificuldade = :dif, DataEsquecida = :dae, Palavra = :pal, Acertos = :ace, Erros = :err, UltimaRev = :dar WHERE id = :id";
        $sql = $con->prepare($sql);
        $sql->bindValue(':sig', $dadosPalavra['Significados']);
        $sql->bindValue(':dad', $dadosPalavra['DataAdd']);
        $sql->bindValue(':dif', $dadosPalavra['Dificuldade']);
        $sql->bindValue(':dae', $dadosPalavra['DataEsquecida']);
        $sql->bindValue(':pal', $dadosPalavra['Palavra']);
        $sql->bindValue(':ace', $dadosPalavra['Acertos']);
        $sql->bindValue(':err', $dadosPalavra['Erros']);
        $sql->bindValue(':dar', $dadosPalavra['UltimaRev']);
        
        $sql->bindValue(':id', $dadosPalavra['id']);
        $resultado = $sql->execute();
        
        if ($resultado == 0){ //if erro
            throw new Exception("Falha ao alterar dados");
            return false;
        } else {
            return true; // Não precisa do else, pode usar só o return
        }
    }
    public static function delete($id) {
        $con = Connection::getConn();
        $sql = "DELETE FROM postagem WHERE id = :id";
        $sql = $con->prepare($sql);
        $sql->bindValue(':id', $id);
        $resultado = $sql->execute();
        
        if ($resultado == 0){ //if erro
            throw new Exception("Falha ao apagar publicação");
            return false;
        } else {
            return true; // Não precisa do else, pode usar só o return
            
        }
        
    }
    public static function getIID() { // retorna o próximo id incremental
         
        $con = Connection::getConn();
        $sql = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'bancoingles' AND TABLE_NAME = 'palavra'"; 
        $sql = $con->prepare($sql);
        $sql->execute();
        $resultado = $sql->fetchObject('palavra');
        $resultado = (array) $resultado;
        $resultado = $resultado["AUTO_INCREMENT"];
        return $resultado;
    }
    public static function selecionaPorPalavra($palavra){ //usada para saber se já existe determinada palavra (bool)
        $con = Connection::getConn();
        $sql = "SELECT * FROM palavra WHERE Palavra = :pal"; 
        $sql = $con->prepare($sql);
        $sql->bindValue(':pal', $palavra);
        $sql->execute();
        $resultado = $sql->fetchObject('Palavra');
        $resultado = (array) $resultado;
        $resultado = isset($resultado['Palavra']);
        return $resultado;
    }
    public static function palavrasDiaId($data){//retorna um array contendo os ids das palavras inseridas no dia
        $con = Connection::getConn();        
        $sql = "SELECT id FROM palavra WHERE DataAdd = :dat"; 
        $sql = $con->prepare($sql);
        $sql->bindValue(':dat', $data);
        $sql->execute();
        while ($row = $sql->fetchObject('palavra')){ 
            $resultado[] = $row;
        }
        if(isset($resultado)){
            $resultado = (array) $resultado;
        }else{
            $resultado = array();
        }
        return $resultado;
    }
    public static function palavrasDia($data){//retorna array de objs contendo as palavras inseridas no dia
        $con = Connection::getConn();        
        $sql = "SELECT * FROM palavra WHERE DataAdd = :dat ORDER BY id DESC"; 
        $sql = $con->prepare($sql);
        $sql->bindValue(':dat', $data);
        $sql->execute();
        while ($row = $sql->fetchObject('palavra')){ 
            $resultado[] = $row;
        }
        if(isset($resultado)){
            $resultado = (array) $resultado;
        }else{
            $resultado = false;
        }
        return $resultado;
    }
   
}

<?php


class Revisao {
    public static function add($array) {
        $con = Connection::getConn();
        $sql = 'INSERT INTO revisoes (IdPalavra, Data, Ordem) VALUES (:id, :data, :ord)';
        $sql = $con->prepare($sql);
        $sql->bindValue(':id', $array['id']); //id da palavra
        $sql->bindValue(':data', $array['Data']);
        $sql->bindValue(':ord', $array['Ordem']);
        $res = $sql->execute();
        if ($res == 0){ //se o objeto retornar falso
            throw new Exception("Falha ao inserir dados");
            return false;
        }
        return true;
    }
    public static function deletarPorId($id) { //deleta todas as revisoes pelo id da palavra
        $con = Connection::getConn();
        $sql = 'DELETE FROM revisoes WHERE IdPalavra = :id';
        $sql = $con->prepare($sql);
        $sql->bindValue(':id', $id);
        $res = $sql->execute();
        if ($res == 0){ //se o objeto retornar falso
            throw new Exception("Falha ao apagar dados");
            return false;
        }
        return true;
    }
    public static function deletarPorIdRev($id) { //deleta apenas uma revisão 
        $con = Connection::getConn();
        $sql = 'DELETE FROM revisoes WHERE id = :id'; //id da revisão, não da palavra
        $sql = $con->prepare($sql);
        $sql->bindValue(':id', $id);
        $res = $sql->execute();
        if ($res == 0){ //se o objeto retornar falso
            throw new Exception("Falha ao apagar dados");
            return false;
        }
        return true;
    }
    public static function selecionarTodos(){
        $con = Connection::getConn();
        $sql = "SELECT * FROM revisoes  ORDER BY Data";
        $sql = $con->prepare($sql);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('revisao')){ // (converte o array em objeto)
            $resultado[] = $row;
        }
        if (!$resultado){
            //throw new Exception("Não foi encontrado nenhum registro no banco de dados :("); Comentado para o programa não parar caso a tabela de revisões esteja vazia
        }
        return $resultado;
    }
    public static function update($rev) {
        $con = Connection::getConn();
        $sql = "UPDATE revisoes SET Data = :dat, Ordem = :ord, idPalavra= :idp WHERE id = :id";
        $sql = $con->prepare($sql);
        $sql->bindValue(':dat', $rev['Data']);
        $sql->bindValue(':ord', $rev['Ordem']);
        $sql->bindValue(':idp', $rev['IdPalavra']);
        
        $sql->bindValue(':id', $rev['id']);//id da revisão
        $resultado = $sql->execute();
        
        if ($resultado == 0){ //if erro
            throw new Exception("Falha ao alterar dados");
            return false;
        } else {
            return true; // Não precisa do else, pode usar só o return
        }
    }
    public static function selecionaPorId($id) {
        $con = Connection::getConn();
        $sql = "SELECT * FROM revisoes WHERE id = :id"; 
        $sql = $con->prepare($sql);
        $sql->bindValue(':id', $id);
        $sql->execute();
        $resultado = $sql->fetchObject('revisao');
        return $resultado;//retorna obj
    }
    public static function idPorData($data) {//seleciona id da palavra de todas as revisoes inferioreas a determindada data (retorna array de ids)
        $con = Connection::getConn();
        $sql = "SELECT IdPalavra FROM revisoes WHERE Data <= :dat"; 
        $sql = $con->prepare($sql);
        $sql->bindValue(':dat', $data);
        $sql->execute();
        $resultado = array();
        while ($row = $sql->fetchObject('revisao')){ // (converte o array em objeto)
            $resultado[] = $row;
        }
        if (!$resultado){
            //throw new Exception("Não foi encontrado nenhum registro no banco de dados :(");  Comentado para o programa não parar caso a tabela de revisões esteja vazia nesta data
        }
        $ids = array();
        for ($index = 0; $index < count($resultado); $index++) {
            $resultado[$index] = (array) $resultado[$index];
            $ids[] = $resultado[$index]['IdPalavra'];
        }
        $resultado = $ids;
        return $resultado;
    }
    public static function apagaMenorData($id) {//apaga a revisão mais recente de determinada palavra
        $data = $_SESSION['data'];
        $con = Connection::getConn();
        $sql = 'DELETE FROM revisoes WHERE IdPalavra = :id AND Data <= :dat';
        $sql = $con->prepare($sql);
        $sql->bindValue(':id', $id);
        $sql->bindValue(':dat', $data);
        $res = $sql->execute();
        if ($res == 0){ //se o objeto retornar falso
            throw new Exception("Falha ao apagar dados");
            return false;
        }
        return true;
    }
}

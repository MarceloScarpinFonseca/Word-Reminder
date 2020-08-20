<?php


class PalavrasEsquecidas { //a tabela esquecidas não é mais utilizada pelo programa (0.8)
    public static function add($id) {
        $date = $_SESSION['data'];
        $con = Connection::getConn();
        $sql = 'INSERT INTO esquecidas (idPalavra, data) VALUES (:id, :data)';
        $sql = $con->prepare($sql);
        $sql->bindValue(':id', $id);
        $sql->bindValue(':data', $date);
        
        $res = $sql->execute();
        
        if ($res == 0){ //se o objeto retornar falso
            throw new Exception("Falha ao inserir dados");
            
            return false;
        }
        return true;
        
    }
}

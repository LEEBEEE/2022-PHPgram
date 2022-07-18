<?php
namespace application\models;
use PDO;

class DmModel extends Model {

    public function insDm() {
        $sql = "INSERT INTO t_dm (lastmsg) VALUES ('')";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return intval($this->pdo->lastInsertId());
    }
    
    public function selDmList(&$param) {
        $sql = "SELECT A.idm, A.regdt, A.lastmsg, A.lastdt, B.iuser as fromiuser
                  FROM t_dm A
                 INNER JOIN t_dm_user B
                    ON A.idm = B.idm
                   AND B.iuser = :iuser
                 ORDER BY A.idm DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":iuser", $param["iuser"]);
        $stmt->execute();        
        return $stmt->fetchAll(PDO::FETCH_OBJ); 
    }

    public function updDmLastMsg(&$param) {
        $sql = "UPDATE t_dm
                   SET lastmsg = #{msg}
                     , lastdt = current_timestamp()
                 WHERE idm = :idm";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":idm", $param["idm"]);
        $stmt->execute();
        return $stmt->rowCount();
    }


    //-------------------------------------------------- opponent

    public function insDmUser(&$param) {
        $sql = "INSERT INTO t_dm_user
                ( idm, iuser )
                VALUES
                ( :idm, :iuser )";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":idm", $param["idm"]);
        $stmt->bindValue(":iuser", $param["iuser"]);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function selDmOpponent(&$param) {
        $sql = "SELECT B.iuser, B.nm, B.mainimg
                  FROM t_dm_user A
                 INNER JOIN t_user B
                    ON A.iuser = B.iuser
                 WHERE A.idm = :idm
                   AND A.iuser != :iuser";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":idm", $param["idm"]);
        $stmt->bindValue(":iuser", $param["iuser"]);
        $stmt->execute();   
        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    //-------------------------------------------------- msg
    public function insDmMsg(&$param) {
        $sql = "INSERT INTO t_dm_msg
                ( idm, seq, iuser, msg )
                SELECT ?, (IFNULL(MAX(seq), 0) + 1), ?, ?
                FROM t_dm_msg
                WHERE idm = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $param["idm"]);
        $stmt->bindValue(2, $param["loginiuser"]);
        $stmt->bindValue(3, $param["msg"]);
        $stmt->bindValue(4, $param["idm"]);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function selDmMsgList(&$param) {
        $sql = "SELECT A.idm, A.seq, A.iuser, A.msg, A.regdt
                     , B.nm as writer
                  FROM t_dm_msg A
                 INNER JOIN t_user B
                    ON A.iuser = B.iuser
                 WHERE A.idm = :idm
                 ORDER BY A.seq DESC
                 LIMIT :startIdx, :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":idm", $param["idm"]);
        $stmt->bindValue(":startIdx", $param["startIdx"]);
        $stmt->bindValue(":limit", $param["limit"]);        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

}

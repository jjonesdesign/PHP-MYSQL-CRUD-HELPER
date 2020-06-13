<?php

class UID {

    private $uid;
    private $table_name;

    public function __construct($results) {
        foreach ($results as $k => $v) {
            $this->$k = $v;
        }
    }

    public function __get($var) {
        return $this->$var;
    }

    public function __toString() {
        return $this->uid;
    }

    public function save() {
        try {
            $NetworkConnection = new NetworkConnection();
            $PDO = $NetworkConnection->getNewDbConnection();

            $stmt = $PDO->prepare("INSERT INTO uids (uid,table_name) VALUES (?,?)");
            $result = $stmt->execute([$this->uid, $this->table_name]);

            if ($result) {
                //Created new lead entry
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

}

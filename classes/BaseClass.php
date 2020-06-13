<?php

class BaseClass {

    //Standard parent variables
    public $id;
    public $uid;
    public $created_at;
    public $updated_at;
    
    //Variables for Parent Usage
    private $tableName;
    private $hasUid;
    public $disregard = array("disregard","tableName","hasUid","uid","id");
    //TODO:: JMJ Demo usages with password fields, etc.
    
    public function __construct($tablename, $hasUid=true) {
        $this->tableName = $tablename;
        $this->hasUid = $hasUid;
    }

    public function __get($var) {
        //Check for disregarded field
        foreach($this->disregard as $disregard){
            if($var == $disregard){
                return;
            }
        }
        return $this->$var;
    }

    public function __toString() {
        return $this->uid;
    }
    
    public function save() {
        //If this object uses UID
        if($this->hasUid){
            //Create new uid, and varify that it hasn't been used.
            //This might take a bit of overhead, but useful for ensuring you don't have duplicate UID's in any table
            $uniqueID = new uniqueID();
            $UID = $uniqueID->shorten($uniqueID->generate()); //final shortened userId

            while (!checkUidIsUnique($UID)) {
                $uniqueID = new uniqueID();
                $UID = $uniqueID->shorten($uniqueID->generate()); //final shortened userId
            }

            $this->uid = $UID;
        }
        
        //Check if we are creating a new object
        if ($this->uid == "" && $this->id == "") {
            $newFields = array();
            foreach ($this as $key => $value) {
                //Skip disregarded fields
                foreach($this->disregard as $disregard){
                    if($key == $disregard){
                        continue 2;
                    }
                }
                
                $ins[] = ':' . $key;
                array_push($newFields, $key);
            }
            $ins = implode(',', $ins);
            $fields = implode(',', $newFields);
            
            //Get the current database time
            $NetworkConnection = new NetworkConnection();
            $PDO = $NetworkConnection->getNewDbConnection();
            
            $stmt = $PDO->prepare("SELECT NOW() today");
            $stmt->execute();
            $dateTime = $stmt->fetch();
            $this->created_at = $dateTime[0]->today;
            $NetworkConnection->closeConnection();
            

            try {
                $NetworkConnection = new NetworkConnection();
                $PDO = $NetworkConnection->getNewDbConnection();
                
                $stmt = $PDO->prepare("INSERT INTO " . $this->tableName ." ($fields) VALUES ($ins)");
                foreach ($this as $key => $value) {
                    //Skip disregarded fields
                    foreach($this->disregard as $disregard){
                        if($key == $disregard){
                            continue 2;
                        }
                    }
                    $stmt->bindValue(':' . $key, $value);
                    
                }
                var_dump($stmt);exit();
                $result = $stmt->execute();
                
                $NetworkConnection->closeConnection();
                
                if ($result && $this->hasUid) {
                    //Created new lead entry
                    $newUID = new UID(array("uid" => $UID, "table_name" => $this->tableName));
                    $newUID->save();
                    return $this;
                }else{
                    return $this;
                }
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        } else {
            
            $fields = array();
            $set = '';
            $x = 1;

            //TODO:: JMJ think this is reminent of old code, consider for removal
            //$this->created_at = date("Y-m-d H:i:s");

            foreach ($this as $key => $value) {
                //Skip disregarded fields
                foreach($this->disregard as $disregard){
                    if($key == $disregard){
                        continue 2;
                    }
                }
                array_push($fields, $key);
            }

            for ($x = 0; $x < count($fields); $x++) {
                $set .= "{$fields[$x]} = ?";
                if ($x < count($fields) - 1) {
                    $set .= ', ';
                }
            }

            //Update existing entry
            $NetworkConnection = new NetworkConnection();
            $PDO = $NetworkConnection->getNewDbConnection();
            
            if(!$this->hasUid){
                $stmt = $PDO->prepare("UPDATE " . $this->tableName ." SET {$set} WHERE id=?");
            }else{
                $stmt = $PDO->prepare("UPDATE " . $this->tableName ." SET {$set} WHERE uid=?");
            }
            for ($i = 0; $i < count($fields); $i++) {

                $stmt->bindParam($i + 1, $this->{$fields[$i]});
                if ($i + 1 == count($fields)) {
                    if(!$this->hasUid){
                        $stmt->bindParam($i + 2, $this->id);
                    }else{
                        $stmt->bindParam($i + 2, $this->uid);
                    }
                    
                }
            }

            $result = $stmt->execute();
            
            $NetworkConnection->closeConnection();
            
            if ($result) {
                return $this;
            }
        }
    }
    
    public function remove(){
        $NetworkConnection = new NetworkConnection();
        $PDO = $NetworkConnection->getNewDbConnection();
        
        if(!$this->hasUid){
            $stmt = $PDO->prepare("DELETE FROM " . $this->tableName ." WHERE id=?");
            $result = $stmt->execute([$this->id]);
        }else{
            $stmt = $PDO->prepare("DELETE FROM " . $this->tableName ." WHERE uid=?");
            $result = $stmt->execute([$this->uid]);
        }
        
        $NetworkConnection->closeConnection();
        
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    public function get($where_array=array(),$order_array=array()){
        try {
            $where = "";
            $order = "";
            $x = 0;
            $y = 0;
            
            if(count($where_array)>0){
                foreach ($where_array as $key => $value) {
                    if($x==0){
                        $where .= ' WHERE ';
                    }
                    $where .= "{$key} = '{$value}'";
                    if ($x < count($where_array) - 1) {
                        $where .= ' AND ';
                    }
                    $x++;
                }
            }
            
            //$order_array = array("");
            
            //ORDER BY created_at DESC
            if(count($order_array)>0){
                foreach($order_array as $key => $value){
                    if($y==0){
                        $where .= ' ORDER BY ';
                    }
                    $where .= "{$key} {$value}";
                    if ($y < count($where_array) - 1) {
                        $where .= ',';
                    }
                    $y++;
                }
            }
            
            $NetworkConnection = new NetworkConnection();
            $PDO = $NetworkConnection->getNewDbConnection();
            
            $stmt = $PDO->prepare("SELECT * FROM " . $this->tableName . $where . $order);
            
            
            
            $stmt->execute();

            $results = $stmt->fetchAll();
            
            if ($results) {
                $data = Array();
                
                foreach ($results as $result) {
                    $newResult = $this::withData($result);
                    array_push($data, $newResult);
                }
                
                return $data;
            } else {
                return null;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}

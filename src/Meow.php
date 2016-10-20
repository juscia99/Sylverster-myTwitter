<?php

class Meow {
    private $id;
    private $userId;
    private $note;
    private $creationDate;
    
    public function __construct() {
        $this->id = -1;
        $this->userId = null;
        $this->note = "";
        $this->creationDate = null;
    }
    
    public function setUserId($userId) {
        $this->userId = $userId;
    }
    
    public function setNote($note) {
        $this->note = $note;
    }
    //ustawianie daty?
    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getUserId(){
        return $this->userId;
    }
    
    public function getNote() {
        return $this->note;
    }
    
    public function getCreationDate() {
        return $this->creationDate;
    }
    
    static public function loadMeowById (mysqli $connection, $id) {
        $sql = "SELECT * FROM `Meow` WHERE id='$id'";
        
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            $loadedMeow = new Meow();
            $loadedMeow->id = $row['id'];
            $loadedMeow->userId = $row['userId'];
            $loadedMeow->note = $row['note'];
            $loadedMeow->creationDate = $row['creationDate'];
            
            return $loadedMeow;
        }
        return null;
    }
    
    static public function loadAllMeowsByUserId(mysqli $connection, $userId) {
        $sql = "SELECT * FROM `Meow` WHERE userId='$userId' ORDER BY `id` DESC";
        $returnArray = [];

        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedMeow = new Meow();
                //$loadedMeow->id = $row['id']; //trzeba ustawiać?
                $loadedMeow->userId = $row['userId']; 
                $loadedMeow->note = $row['note'];
                $loadedMeow->creationDate = $row['creationDate'];

                $returnArray[] = $loadedMeow;
            }
        }
        return $returnArray;
    }
    
    static public function loadAllMeows(mysqli $connection) {
        $sql = "SELECT * FROM `Meow`ORDER BY `id` DESC";
        $returnArray = [];

        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedMeow = new Meow();
                $loadedMeow->id = $row['id'];
                $loadedMeow->userId = $row['userId'];
                $loadedMeow->note = $row['note'];
                $loadedMeow->creationDate = $row['creationDate'];

                $returnArray[] = $loadedMeow;
            }
        }
        return $returnArray;
    }
    
    public function saveToDB (mysqli $connection) {
        if($this->id == -1) {
            $sql = "INSERT INTO `Meow`(userId, note, creationDate) "
                    . "VALUES ('$this->userId', '$this->note', '$this->creationDate');";
            
            $result = $connection->query($sql);
            
            if($result == true ) {
                $this->id = $connection->insert_id;
                return true;
            }
        } else {
            $sql = "UPDATE `Meow` SET note='$this->note', creationDate='$this->creationDate' WHERE id='$this->id'";
            
            $result = $connection->query($sql);
            
            if ($result == true) {
                return true;
            }
        }
        return false;
    }
}
?>
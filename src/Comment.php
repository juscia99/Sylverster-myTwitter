<?php

class Comment {

    private $id;
    private $userId;
    private $meowId;
    private $creation_date;
    private $commentText;

    public function __construct() {
        $this->id = -1;
        $this->userId = null;
        $this->meowId = null;
        $this->creation_date = null;
        $this->commentText = "";
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setMeowId($meowId) {
        $this->meowId = $meowId;
    }

    //ustawianie daty?
    public function setCreationDate($creation_date) {
        $this->creation_date = $creation_date;
    }

    public function setCommentText($commentText) {
        $this->commentText = $commentText;
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getMeowId() {
        return $this->meowId;
    }

    public function getCreationDate() {
        return $this->creation_date;
    }

    public function getCommentText() {
        return $this->commentText;
    }
//bez sensu?
    static public function loadCommentById(mysqli $connection, $id) {
        $sql = "SELECT * FROM `Comment` WHERE id='$id'";

        $result = $connection->query($sql);
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();

            $loadedComment = new Comment();
            $loadedComment->id = $row['id'];
            $loadedComment->userId = $row['userId'];
            $loadedComment->meowId = $row['meowId'];
            $loadedComment->creation_date = $row['creation_date'];
            $loadedComment->commentText = $row['commentText'];

            return $loadedComment;
        }
        return null;
    }

    static public function loadAllCommentsByMeowId(mysqli $connection, $meowId) {
        $sql = "SELECT * FROM `Comment` WHERE meowId='$meowId'";
        $returnArray = [];

        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedComment = new Comment();
                $loadedComment->id = $row['id'];
                $loadedComment->userId = $row['userId'];
                $loadedComment->meowId = $row['meowId']; //trzeba nastawiać skoro wprowadzamy do funkcji?
                $loadedComment->creation_date = $row['creation_date'];
                $loadedComment->commentText = $row['commentText'];

                $returnArray[] = $loadedComment;
            }
        }
        return $returnArray;
    }

    //Czy dodać możliwość zmiany komentarzy?
    public function saveToDB(mysqli $connection) {
        if ($this->id == -1) {
            $sql = "INSERT INTO `Comment`(userId, meowId, commentText, creation_date) "
                    . "VALUES ('$this->userId', '$this->meowId', '$this->commentText', '$this->creation_date');";

            $result = $connection->query($sql);

            if ($result == true) {
                $this->id = $connection->insert_id;
                return true;
            }
        } else {
            $sql = "UPDATE `Comment` SET commentText='$this->commentText', creation_date='$this->creation_date' WHERE id='$this->id'";

            $result = $connection->query($sql);

            if ($result == true) {
                return true;
            }
        }
        return false;
    }

}
?>
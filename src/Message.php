<?php

class Message {

    private $id;
    private $senderId;
    private $message;
    private $recipientId;
    private $creationDate;
    private $isRead;

    public function __construct() {
        $this->id = -1;
        $this->senderId = null;
        $this->recipientId = null;
        $this->message = "";
        $this->creationDate = null;
        $this->isRead = 0;
    }

    public function setSenderId($senderId) {
        $this->senderId = $senderId;
    }

    public function setRecipientId($recipientId) {
        $this->recipientId = $recipientId;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    public function getId() {
        return $this->id;
    }

    public function getSenderId() {
        return $this->senderId;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getRecipientId() {
        return $this->recipientId;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getIsRead() {
        return $this->isRead;
    }

    public function saveToDB(mysqli $connection) {
        if ($this->id == -1) {
            $sql = "INSERT INTO `Message`(senderId, message, recipientId, creationDate, isRead) "
                    . "VALUES ('$this->senderId', '$this->message', '$this->recipientId', '$this->creationDate', '$this->isRead');";

            $result = $connection->query($sql);

            if ($result == true) {
                $this->id = $connection->insert_id;
                return true;
            }
        }
        return false;
    }

    static public function loadMessageById(mysqli $connection, $id) {
        $sql = "SELECT * FROM `Message` WHERE id='$id'";

        $result = $connection->query($sql);
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();

            $loadedMessage = new Message();
            $loadedMessage->id = $row['id'];
            $loadedMessage->senderId = $row['senderId'];
            $loadedMessage->message = $row['message'];
            $loadedMessage->recipientId = $row['recipientId'];
            $loadedMessage->creationDate = $row['creationDate'];
            $loadedMessage->isRead = $row['isRead'];

            return $loadedMessage;
        }
        return null;
    }

    static public function loadAllMeessagesByRecipientId(mysqli $connection, $recipientId) {
        $sql = "SELECT * FROM `Message` WHERE recipientId='$recipientId' ORDER BY `id` DESC";
        $returnArray = [];

        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->senderId = $row['senderId'];
                $loadedMessage->message = $row['message'];
                $loadedMessage->recipientId = $row['recipientId'];
                $loadedMessage->creationDate = $row['creationDate'];
                $loadedMessage->isRead = $row['isRead'];

                $returnArray[] = $loadedMessage;
            }
        }
        return $returnArray;
    }

    static public function loadAllMeessagesBySenderId(mysqli $connection, $senderId) {
        $sql = "SELECT * FROM `Message` WHERE senderId='$senderId' ORDER BY `id` DESC";
        $returnArray = [];

        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->senderId = $row['senderId'];
                $loadedMessage->message = $row['message'];
                $loadedMessage->recipientId = $row['recipientId'];
                $loadedMessage->creationDate = $row['creationDate'];
                $loadedMessage->isRead = $row['isRead'];

                $returnArray[] = $loadedMessage;
            }
        }
        return $returnArray;
    }

    public function changeMessageStatus(mysqli $connection, $id) {
        if ($this->isRead == 0) {
            $sql = "UPDATE `Message` SET `isRead`=1 WHERE `id`=$id";
            $result = $connection->query($sql);
            return true;
        }
        return false;
    }

}
?>
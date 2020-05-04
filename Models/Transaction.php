<?php


class Transaction
{
    private $conn;
    private $table = 'TRANSACTION';

    public $id;
    public $betId;
    public $date;
    public $value;
    public $clientId;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function save(){
        $query = 'INSERT INTO ' . $this->table . ' (BET_ID, DATUM, VALUE, CLIENT_ID)  
                                                    VALUES (:betId, :datum, :valuee, :clientId)';

        $stmt = $this->conn->prepare($query);

        $this->date =  time();

        $stmt->bindParam(':betId', $this->betId);
        $stmt->bindParam(':datum', $this->date);
        $stmt->bindParam(':valuee', $this->value);
        $stmt->bindParam(':clientId', $this->clientId);

        if($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->error);
    }

}
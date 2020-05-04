<?php


class Client
{
    private $conn;
    private $table = 'CLIENT';

    public $id;
    public $ime;
    public $prezime;
    public $username;
    public $password;
    public $balance;

    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function getClient() {
        $query =  "select * from ".$this->table." where ID=?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->prezime = $row['PREZIME'];
        $this->ime = $row['IME'];
        $this->balance = $row['BALANCE'];

    }

    public function checkClient($username,$password) {
        $query =  "select * from ".$this->table." where username=? and password = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$username);
        $stmt->bindParam(2,$password);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->id = $row['ID'];
        $this->prezime = $row['PREZIME'];
        $this->ime = $row['IME'];
        $this->balance = $row['BALANCE'];

        if($this->id == null)
            return false;

        return true;
    }

    public function toString(){
        return $this->ime." ".$this->prezime;
    }

    public function getBalance(){
        $query =  "select * from ".$this->table." where id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->balance = $row['BALANCE'];

        return $this->balance;
    }

    public function create(){

        echo $this->toString();
        if($this->id != null)
            return false;

        if($this->exists($this->username))
            return false;


        $query = 'INSERT INTO ' . $this->table . ' (ime, prezime, username, password, balance)   VALUES (:ime, :prezime, :username, :password, :balance)';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->ime = htmlspecialchars(strip_tags($this->ime));
        $this->prezime = htmlspecialchars(strip_tags($this->prezime));
        $this->balance = htmlspecialchars(strip_tags($this->balance));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = htmlspecialchars(strip_tags($this->password));

        // Bind data
        $stmt->bindParam(':ime', $this->ime);
        $stmt->bindParam(':prezime', $this->prezime);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':balance', $this->balance);

        echo $query;
        // Execute query
        if($stmt->execute())
            return true;
        return false;

    }

    private function exists($username){
        $query =  "select * from ".$this->table." where username=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$username);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row['ID'] == null)
            return true;
        return false;
    }

    public function changeBalance($value, $betId){

        $this->balance += $value;
        $query = "update ".$this->table.' set BALANCE = :balance where ID=:id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':balance', $this->balance);


        if($stmt->execute()){
            $transaction = new Transaction($this->conn);
            $transaction->betId = $betId;
            $transaction->value = $value;
            $transaction->clientId = $this->id;
            $transaction->save();
            return  true;
        }

        return false;
    }


}
?>
<?php


class Bet
{
    public const STATUS_BEGIN = 'begin';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_DONE = 'done';

    private $conn;
    private $table = 'BET';

    public $id;
    public $client;
    public $normalBet;
    public $tieBet;
    public $timestamp;
    public $status;
    public $dealerCard;
    public $userCard;
    public $token;


    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function saveBet() {
        if($this->status == self::STATUS_BEGIN)
            $this->createBet();
        else
           $this->updateBet();
    }

    public function updateBet() {
        $query = 'update ' . $this->table . '
                            set  CLIENT_ID = :clientId, NORMAL_BET = :normalBet, 
                                 TIE_BET = :tieBet, STATUS = :status,
                                 DEALER_CARD =:dealerCard
                            where token = :token';


        $stmt = $this->conn->prepare($query);

        //$uCard =  $this->cardToString($this->userCard);
        $dCard = htmlspecialchars(strip_tags($this->cardToString($this->dealerCard)));

        //$stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':clientId', $this->client->id);
        $stmt->bindParam(':normalBet', $this->normalBet);
        $stmt->bindParam(':tieBet', $this->tieBet);
        $stmt->bindParam(':status', $this->status);
        // $stmt->bindParam(':userCard', $uCard);
        $stmt->bindParam(':dealerCard', $dCard);
        //$stmt->bindParam(':timestamp', $this->timestamp);
        $stmt->bindParam(':token', $this->token);

        if($stmt->execute())
            return true;

        printf("Error: %s.\n", $stmt->error);
    }

    public function createBet(){
        $query = 'INSERT INTO ' . $this->table . ' (CLIENT_ID,NORMAL_BET, TIE_BET, STATUS, USER_CARD, DEALER_CARD, timestamp, TOKEN)  
                                                    VALUES (:clientId, :normalBet, :tieBet, :status, :userCard, :dealerCard,:carl, :token)';

        $stmt = $this->conn->prepare($query);

        $uCard = htmlspecialchars(strip_tags($this->cardToString($this->userCard)));
        $dCard = htmlspecialchars(strip_tags($this->cardToString($this->dealerCard)));
        $this->timestamp =  time();

        $stmt->bindParam(':clientId', $this->client->id);
        $stmt->bindParam(':normalBet', $this->normalBet);
        $stmt->bindParam(':tieBet', $this->tieBet);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':userCard', $uCard);
        $stmt->bindParam(':dealerCard', $dCard);
        $stmt->bindParam(':carl', $this->timestamp);
        $stmt->bindParam(':token', $this->token);


        if($stmt->execute()) {
            $this->getBet();
            return true;
        }

        printf("Error: %s.\n", $stmt->error);
    }

    public function cardToString($card){
        return $card[0].'<br>'. $card[1];
    }


    public function getBet(){
        if($this->token){
            $query =  "select * from ".$this->table." where token=:token";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':token',$this->token);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            //$this->client = new Client($this->conn);
            $this->id = $row['ID'];
//            $this->client->id = $row['CLIENT_ID'];
//            $this->client->getClient($this->client->id);
//            $this->normalBet = $row['NORMAL_BET'];
//            $this->tieBet = $row['TIE_BET'];
//            $this->status = $row['STATUS'];
//            $this->userCard = $row['USER_CARD'];
//            $this->dealerCard = $row['DEALER_CARD'];
//            $this->timestamp = $row['TIMESTAMP'];
        }
    }

    public function getBetById(){
        if($this->id){
            $query =  "select * from ".$this->table." where ID=:id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id',$this->id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->client = new Client($this->conn);
            $this->client->id = $row['CLIENT_ID'];
            $this->client->getClient($this->client->id);
            $this->normalBet = $row['NORMAL_BET'];
            $this->tieBet = $row['TIE_BET'];
            $this->status = $row['STATUS'];
            $this->userCard = $row['USER_CARD'];
            $this->dealerCard = $row['DEALER_CARD'];
            $this->timestamp = $row['TIMESTAMP'];
        }
    }

    public function checkBet(){
        if((int)$this->normalBet == 0)
            return false;

        $betValue = (int)$this->normalBet + (int)$this->tieBet;
        if($betValue > $this->client->balance)
            return false;

        // treba proveriti i aktualne igre, tj da li igra vise od jedne igre
        // pretrazi bet tabeli sa statusom i istim klijentom

        return true;
    }

    public function settle(){
        $userCard = array_search($this->userCard[0], GAME::DECK_NUMS);
        $dealCard = array_search($this->dealerCard[0], GAME::DECK_NUMS);

        $normalBetValue = (int)$this->normalBet;
        $tieBetValue = (int)$this->tieBet;
        if($userCard < $dealCard) {
            $this->client->changeBalance(-1*$normalBetValue, $this->id);
            if((int)$this->tieBet != 0)
                $this->client->changeBalance(-1*$tieBetValue, $this->id);
            return "dealer";
        }
        else if($userCard > $dealCard){
            $this->client->changeBalance($normalBetValue, $this->id);
            return "user";
        }
        else return "war";
    }
    public function settleWar($decision)
    {
        $tieBetValue = (int)$this->tieBet * 7.5;
        $normalBetValue = (int)$this->normalBet;

        if($tieBetValue != 0)
            $this->client->changeBalance($tieBetValue, $this->id);

        if($decision == 'fold'){
            $normalBetValue = $normalBetValue*0.5;
            $this->client->changeBalance(-1*$normalBetValue, $this->id);
        }
        if($decision == 'war'){
            $userCard = array_search($this->userCard[0], GAME::DECK_NUMS);
            $dealCard = array_search($this->dealerCard[0], GAME::DECK_NUMS);

            if($userCard < $dealCard) {
                $this->client->changeBalance(-2*$normalBetValue, $this->id);
                return "dealer";
            }
            else{
                $this->client->changeBalance(3*$normalBetValue, $this->id);
                return "user";
            }

        }

        if($decision == 'lose'){
            $normalBetValue = $normalBetValue*2;
            $this->client->changeBalance(-1*$normalBetValue, $this->id);
        }
    }
}
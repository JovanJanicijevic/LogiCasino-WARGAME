<?php


class Game
{
    // array("D", "K", "S", "L");
    private const DECK_SUITS =   array("&#9827", "&#9830", "	&#9829" , "&#9824");
    public const DECK_NUMS = array( "2","3","4","5","6","7","8","9","10","J","Q","K","A");

    private $conn;
    public  $client;
    public $cards;
    public $bet;


    private const NUMBER_OF_CARNUMS = 13;
    private const NUMBER_OF_SUITS = 4;
    private const NUMBER_OF_DECKS = 3;



    public function __construct($db)
    {
        $this->conn = $db;

        for($i=0;$i < self::NUMBER_OF_SUITS; $i++){
            for($j=0;$j < self::NUMBER_OF_CARNUMS; $j++)
                $this->cards[$i][$j] = self::NUMBER_OF_DECKS;
        }
    }

    public function getClient($id){

        $this->client = new Client($this->conn);
        $this->client->id = $id;
        $this->client->getClient();

        return $this->client;
    }

    public function printDeck(){
        for($i=0;$i < self::NUMBER_OF_SUITS; $i++){
            for($j=0;$j < self::NUMBER_OF_CARNUMS; $j++)
                echo $this->cards[$i][$j].' ';
            echo '<br>';
        }
    }

    public function getCardFromDeck(){
        $i = array_rand($this->cards);
        $j = array_rand($this->cards[$i]);
        $this->cards[$i][$j]--;
        return array(self::DECK_NUMS[$j], self::DECK_SUITS[$i]);
    }

    public function checkClentBalance($betValue){
        if($this->client->balance <  $betValue)
            return false;

        //  treba proveriti i da li ima aktivnih betova trenutno
        return true;


    }


}
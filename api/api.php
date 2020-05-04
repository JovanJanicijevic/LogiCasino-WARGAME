<?php


include '../Config/DatabaseConnection.php';
include '../Models/Client.php';
include '../api/Game.php';
include '../Models/Bet.php';
include '../Models/Transaction.php';

session_start();
if (!isset($_SESSION['userId']))
    header("location:../index.php");


if (isset($_POST['deal'])) {
    if (!isset($_SESSION['token']))
        $_SESSION['token'] = bin2hex(random_bytes(8));

    $db = new DatabaseConnection();
    $conn = $db->connect();
    $game = new Game($conn);
    $game->getClient($_SESSION['userId']);

    $bet = new Bet($conn);
    $bet->token = $_SESSION['token'];
    $bet->client = $game->client;
    $bet->normalBet = $_POST['normalBet'];
    $bet->tieBet = $_POST['tieBet'];
    if ($bet->checkBet()) {
        $bet->userCard = $game->getCardFromDeck();
        $bet->dealerCard = $bet->userCard;//$game->getCardFromDeck();
        $bet->status = BET::STATUS_BEGIN;

        $bet->saveBet();
        $winner = $bet->settle();

        $game->bet = $bet;

        $_SESSION['balance'] = $bet->client->balance;
        $_SESSION['userCard'] = $bet->cardToString($bet->userCard);
        $_SESSION['dealerCard'] = $bet->cardToString($bet->dealerCard);

        if($winner == "war"){
            $_SESSION["WAR"] = $bet->id;
        }else{
            unset($_SESSION['token']);
        }

        echo json_encode(array("userCard" => $bet->userCard, "dealerCard" => $bet->dealerCard, "bet" => $winner, "balance" => $bet->client->balance));
    } else {
        $_SESSION['userCard'] = "X<BR>X";
        $_SESSION['dealerCard'] = "X<BR>X";
        echo json_encode(array("userCard" => "X<BR>X", "dealerCard" => "X<BR>X", "bet" => 'BAD'));
    }

}





//if (!isset($_SESSION['token'])) {
//
//    $_SESSION['token'] = bin2hex(random_bytes(8));
//
//    $db = new DatabaseConnection();
//    $conn = $db->connect();
//    $game = new Game($conn);
//    $game->getClient($_SESSION['userId']);
//
//    $bet = new Bet($conn);
//    $bet->token = $_SESSION['token'];
//    $bet->client = $game->client;
//    $bet->userCard = $game->getCardFromDeck();
//    $bet->status = BET::STATUS_BEGIN;
//    $bet->saveBet();
//
//    $game->bet = $bet;
//
//    $_SESSION['betId'] = $bet->id;
//
//    unset($_SESSION['dealerCard']);
//    $_SESSION['userCard'] = $bet->cardToString($bet->userCard);
//
//    echo json_encode(array("card" => $bet->userCard, "turn" => 'client'));
//} else {
//
//    $db = new DatabaseConnection();
//    $conn = $db->connect();
//    $game = new Game($conn);
//
//    $bet = new Bet($conn);
//    $bet->token = $_SESSION['token'];
//    $bet->getBet();
//    $bet->id = $_SESSION['betId'];
//    $bet->dealerCard = $game->getCardFromDeck();
//    $bet->status = BET::STATUS_DONE;
//    $bet->saveBet();
//
//    $game->bet = $bet;
//
//    $_SESSION['dealerCard'] = $bet->cardToString($bet->dealerCard);
//    unset($_SESSION['token']);
//    echo json_encode(array("card" => $game->bet->dealerCard, "turn" => 'dealer'));
//}
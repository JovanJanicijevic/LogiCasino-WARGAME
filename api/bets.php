<?php

include '../Config/DatabaseConnection.php';
include '../Models/Client.php';
include '../api/Game.php';
include '../Models/Bet.php';

session_start();
if (!isset($_SESSION['userId']))
    header("location:../index.php");


if (isset($_POST['normalBet'])) {

    $db = new DatabaseConnection();
    $conn = $db->connect();
    $game = new Game($conn);
    $game->getClient($_SESSION['userId']);

    $betValue = $_POST['normalBet'] + $_POST['tieBet'];
    if(is_numeric($betValue)) {
        $betValue = (int)$betValue;
        if($game->checkClentBalance($betValue))
            echo json_encode(array("res" => 'OK'));
        else
            echo json_encode(array("res" => 'NEMA PARA'));
    }else
        echo json_encode(array("res" => 'LOSE TI OVO!'));
}



//if (isset($_POST['normalBet'])) {
//
//    $db = new DatabaseConnection();
//    $conn = $db->connect();
//    $game = new Game($conn);
//    $game->getClient($_SESSION['userId']);
//
//    $bet = new Bet($conn);
//    $bet->token = $_SESSION['token'];
//    $bet->getBet();
//    $bet->normalBet = $_POST['normalBet'];
//    $bet->status = BET::STATUS_ACTIVE;
//
//    if($game->checkClentBalance($_POST['normalBet']))
//    {
//        $bet->saveBet();
//        echo json_encode(array("res" => 'successfuly'));
//    }else{
//        echo json_encode(array("res" => 'Nemas para'));
//    }
//
//}

//if (isset($_POST['tieBet'])) {
//    $db = new DatabaseConnection();
//    $conn = $db->connect();
//    $game = new Game($conn);
//    $game->getClient($_SESSION['userId']);
//
//    $bet = new Bet($conn);
//    $bet->token = $_SESSION['token'];
//    $bet->getBet();
//    // proveri status?
//    $bet->tieBet = $_POST['tieBet'];
//    $bet->status = BET::STATUS_ACTIVE;
//    $bet->saveBet();
//    echo json_encode(array("res" => 'successfuly tie!!'));
//}

?>
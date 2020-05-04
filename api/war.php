<?php

include '../Config/DatabaseConnection.php';
include '../Models/Client.php';
include '../Models/Transaction.php';
include '../api/Game.php';
include '../Models/Bet.php';

session_start();
if (!isset($_SESSION['userId']))
    header("location:../index.php");

if (isset($_SESSION['WAR'])) {

    $_SESSION['userCard'] = "X<BR>X";
    $_SESSION['dealerCard'] = "X<BR>X";

    $db = new DatabaseConnection();
    $conn = $db->connect();
    $game = new Game($conn);
    $game->getClient($_SESSION['userId']);

    $bet = new Bet($conn);
    $bet->id =  $_SESSION['WAR'];
    $bet->getBetById();

    if (isset($_POST['decision']) && $_POST['decision'] == 'fold') {
        $bet->status = BET::STATUS_DONE;
        $bet->settleWar($_POST['decision']);
        $bet->saveBet();
        echo json_encode(array("res" => "fold"));
    } if(isset($_POST['decision']) && $_POST['decision'] == 'war'){
        $bet->status=BET::STATUS_ACTIVE;

        if ($bet->checkBet()) {
            $bet->userCard = $game->getCardFromDeck();
            $bet->dealerCard = $game->getCardFromDeck();
            $bet->status = BET::STATUS_DONE;
            $bet->saveBet();
            $winner = $bet->settleWar('war');

            $_SESSION['balance'] = $bet->client->balance;
            $_SESSION['userCard'] = $bet->cardToString($bet->userCard);
            $_SESSION['dealerCard'] = $bet->cardToString($bet->dealerCard); // dealerCard

            echo json_encode(array("res" => "war","userCard" => $bet->userCard, "dealerCard" => $bet->dealerCard, "bet" => $winner, "balance" => $bet->client->balance));
        }else{

            $bet->settleWar('fold');
            $bet->saveBet();
            echo json_encode(array("res" => "Nema dovljno novca!"));
        }
    }

    unset($_SESSION['WAR']);

} else
    echo json_encode(array("res" => "fold1"));
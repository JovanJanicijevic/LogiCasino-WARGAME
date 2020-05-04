<?php
include './Models/Bet.php';
include './Models/Client.php';
require "./Config/DatabaseConnection.php";
require "./Models/Transaction.php";
require 'api/Game.php';



    $db = new DatabaseConnection();
    $conn = $db->connect();
    $game = new Game($conn);
    $game->getClient(1);

    $bet = new Bet($conn);
    $bet->id = 163;
    $bet->getBetById();

        $bet->status=BET::STATUS_DONE;
        $bet->settleWar('fold');
        $bet->saveBet();
        echo json_encode(array("res" => "foldAAA"));

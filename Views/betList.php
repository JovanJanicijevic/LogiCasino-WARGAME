<?php
include '../Config/DatabaseConnection.php';
include '../Models/Client.php';
include '../api/Game.php';
include '../Models/Bet.php';


session_start();
if(!isset($_SESSION['userId']))
    header("location:../index.php");


$db = new DatabaseConnection();
$conn = $db->connect();
$client = new Client($conn);
$client->id = $_SESSION['userId'];
$client->getClient();



echo 'Current balance: '.$client->balance;
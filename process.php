<?php
require "./Config/DatabaseConnection.php";
require "./Models/Client.php";
if(isset($_GET['logout'])){
    session_start();
    session_destroy();
    header("location:./index.php");
}

$db = new DatabaseConnection();

if (isset($_POST['submit'])) {

    $cli = new Client($db->connect());

    if ($cli->checkClient($_POST['username'], $_POST['password'])) {
        session_start();
        $_SESSION['fullName'] = $cli->toString();
        $_SESSION['balance'] = $cli->balance;
        $_SESSION['userId'] = $cli->id;
        header("location:./Views/mainBoard.php");
    } else
        header("location:./index.php?exist=false");

}else if (isset($_POST['submitReg'])  && !empty($_POST['username']) && !empty($_POST['password'])) {

    $cli = new Client($db->connect());

    $cli->ime = $_POST['Ime'];
    $cli->prezime = $_POST['Prezime'];
    $cli->username= $_POST['username'];
    $cli->password= $_POST['password'];
    $cli->balance = $_POST['balance'];

    if($cli->create()) {
        session_start();
        $_SESSION['fullName'] = $cli->toString();
        $_SESSION['balance'] = $cli->balance;
        $_SESSION['userId'] = $cli->id;
        header("location:./Views/mainBoard.php");
    }
    else
        header("location:./index.php?exist=true");
}
else
    header("location:./index.php");

?>
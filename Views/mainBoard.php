<?php
    session_start();
    if(!isset($_SESSION['userId']))
        header("location:../index.php");

?>

<html>
<head>
    <meta charset="UTF-8">
    <link href="../style/global.css" rel="stylesheet" type="text/css"/>
    <script src="../Scripts/jquery-3.5.0.min.js"></script>
    <script language="JavaScript" type="text/javascript" src="../Scripts/mainBoard.js"></script>

    <title>WAR GAME</title>
</head>
<body>
    <div id="betValues" class="mainBoard">
        <h1>Bet values</h1>
        <span style="float: left;"> NORMALN BET VALUE: <?php echo 50 ?></span><br>
        <span style="float: left;"> TIE BET VALUE: <?php echo 5 ?></span>
    </div>

    <div id="userInfo" class="mainBoard">
        <h1> User info </h1>
        <span>Ime: </span><span id="userName"> <?php if(isset($_SESSION['fullName'])) echo $_SESSION['fullName'];  ?></span> <br>
        <span>Balans: </span> <span id="userBalance"> <?php if(isset($_SESSION['balance'])) echo $_SESSION['balance'];  ?></span><br>
        <button id="logutBtn">LOG OUT</button><br>
        <a href="betList.php">LIST BETS</a>
        <br>
        <p id="betInformation">

        </p>
    </div>

    <div id="dealerBoard" class="mainBoard">
        <h1> Dealer Board </h1>
        <span>KARTA</span> <br>
        <div id="dealerCard" > <?php if(isset($_SESSION['dealerCard'])) echo $_SESSION['dealerCard']; else echo 'X<br>X'; ?></div>
    </div>

    <div id="betButtons" class="mainBoard">
        <h3> Bet buttons </h3>
        <input id="dealCard" style="float: left; height: 30%; width: 100%;  font-size: 30px;"  type="button" value="DEAL"></input>
        <input id="normalBetBtn" style="float: left; height: 30%; width: 100%;  font-size: 30px;" type="button" value="NORMAL BET"/>
        <input id="tieBetBtn" style="float: left; height: 20%; width: 100%;  font-size: 30px;" type="button" value="TIE"/>
    </div>

    <div id="betBalance" class="mainBoard">
        <h1>Bet balance</h1>
        <span>Noraml bet value: </span> <div id="normalBetValue"> 0</div> <br>
        <span>Tie bet value: </span> <div id="tieBetValue"> 0 </div>
    </div>

    <div id="userBoard" class="mainBoard">
        <h1> User Board </h1>
        <span>KARTA</span> <br>
        <div id="userCard"> <?php if(isset($_SESSION['userCard'])) echo $_SESSION['userCard']; else echo 'X<br>X'; ?>  </div>
    </div>
</body>
</html>

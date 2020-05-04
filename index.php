<html>
<head>
    <meta charset="UTF-8">
    <link href="style/global.css" rel="stylesheet" type="text/css"/>
    <title></title>
</head>
<body>



    <form action="process.php" method="post" class="forma">
        <div class="container">

        <div class="imgcontainer">
            <img src="Resources/R.jpg" alt="Avatar" class="avatar">
        </div>
            <?php
                if(isset($_GET['exist']))
                {
                    if($_GET['exist'] == "false")
                    {
            ?>
                    <span>Pogresam username i password!</span><br>
            <?php
                    }else{
             ?>
                    <span>Korisnik vec postoji!</span><br>
            <?php
                    }
                }
            ?>

            <label for="password"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="username" required>

            <label for="password"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password" required>

            <input type="submit" name="submit" value="LogIn">


            <a href="Views/registerPage.php" style="float: right;">Register</a>
        </div>
    </form>

</body>
</html>

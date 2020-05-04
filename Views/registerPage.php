<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../style/registerPage.css" rel="stylesheet" type="text/css"/>

</head>
<body>

<form action="../process.php" method="post">
    <div class="container">
        <h1>Register</h1>
        <p>Please fill in this form to create an account.</p>
        <hr>

        <label for="Ime"><b>Ime</b></label>
        <input type="text" placeholder="Unesi ime" name="Ime" required>

        <label for="Prezime"><b>Prezime</b></label>
        <input type="text" placeholder="Unesi prezime" name="Prezime" required>

        <label for="username"><b>Username</b></label>
        <input type="text" placeholder="Unesi username" name="username" required>

        <label for="password"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" required>

   <!--     <label for="psw-repeat"><b>Repeat Password</b></label>
        <input type="password" placeholder="Repeat Password" name="psw-repeat" required> -->

        <label for="balance"><b>Balans</b></label>
        <input type="number" placeholder="Balans" name="balance" required>
        <hr>
        <p>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>

        <input type="submit" name="submitReg" value="Register" class="registerbtn">

    </div>

    <div class="container signin">
        <p>Already have an account? <a href="../index.php">Sign in</a>.</p>
    </div>
</form>

</body>
</html>

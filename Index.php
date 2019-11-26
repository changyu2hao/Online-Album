<?php include("./ProjectCommon/Header.php"); ?>
<?php include('./ProjectCommon/Footer.php'); ?>
<?php session_start();?>
<html>
    <head>
        <title>Home</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    </head>
    <body>
    <div class="container">
    <h1>Welcome to Algonquin Social Media Website</h1>
    <p>If you have never used this before, you have to <a href="NewUser.php">sign up</a> first.</p>
    <p>If you have already signed up, you can <?php if(isset($_SESSION["login"]))
    {echo '<a href="Logout.php">log out</a>'; }
    else
    {
        echo '<a href="Login.php">log in</a>';
    }
 ?> now.</p>
    </div>
    </body>
</html>>
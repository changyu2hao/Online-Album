<?php include("./ProjectCommon/Header.php"); ?>
<?php include('./ProjectCommon/Footer.php'); ?>
<?php
    session_start();
    include 'ProjectCommon/Functions.php';
    $Userid=$_POST["Userid"];
    $PassWord=$_POST["Password"];
    $HasPassWord=sha1($_POST["Password"]);
    $submitbutton= $_POST['submit1'];
    $Info="";
    if($_SESSION["login"]=="yes")
    {
        header("Location: Logout.php");
    }
    if(isset($submitbutton))
    {
        if(ValidateIdLogIn($Userid)==""&&ValidatePasswordLogIn($PassWord)=="")
        {
        //$Info="";
            $Info=ValidateLogin($Userid,$HasPassWord,$Info);
            if($Info=="")
            {
                $LoginOrNot="yes";
                $_SESSION["login"] = $LoginOrNot;
                $_SESSION["Userid"]=$Userid;               
            }
        }
    }
    
?>
<html>
    <html>
    <head>
        <title>Log In</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    </head>
    <body>
        <form method = "post" action = "Login.php">
            <h1 class="col-md-6 col-md-offset-1">Log In</h1>
            <div class="row vertical-margin form-group">
            <div class="col-md-6 col-md-offset-1">
                <p>You need to sign up if you are a new user</p>
            </div>
            </div>
            <div class="row vertical-margin form-group">
            <div class="col-md-6 col-md-offset-1 text-danger">
                <p><?php 
                    echo "$Info";
                ?></p>
            </div>
            </div>
            <div class="row vertical-margin form-group">
            <div class="col-md-3 col-md-offset-1">
                Student ID:
            </div>
            <div class="col-md-2">
                  <input type = "text" name = "Userid" value="<?php echo isset($_POST['Userid']) ? $_POST['Userid'] : '' ?>" />  
            </div>
            <div class="col-md-2 text-danger">
                <?php if($_POST){
                    echo ValidateIdLogIn($Userid);
                }?>
            </div>
            </div>
            <div class="row vertical-margin form-group">
            <div class="col-md-3 col-md-offset-1">
                Password:
            </div>
            <div class="col-md-2">
                <input type = "text" name = "Password" value="<?php echo isset($_POST['Password']) ? $_POST['Password'] : '' ?>" />  
            </div>
            <div class="col-md-2 text-danger">
                <?php if($_POST){
                    echo ValidatePasswordLogIn($PassWord);
                }?>
            </div>
            </div>
            <div class="row vertical-margin form-group">
            <div class="col-md-3 col-md-offset-1">
                <button name="submit1" type="submit" class="btn btn-success">Submit</button>               
            </div>
            <div class="col-md-4">
                  <input type = "reset" onclick="location.href='Login.php'" class="btn btn-warning" />
            </div>                         
            </div>
        </form>
    </body>
</html>
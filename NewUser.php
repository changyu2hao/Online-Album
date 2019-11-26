<?php include("./ProjectCommon/Header.php"); ?>
<?php include('./ProjectCommon/Footer.php'); ?>
<?php
    session_start();
    include 'ProjectCommon/Functions.php';
    $Userid=$_POST["Userid"];
    $Name=$_POST["Name"];
    $PhoneNumber=$_POST["Phonenumber"];
    $PassWord= $_POST["Password"];
    $HasPassWord=sha1($_POST["Passwordagain"]);
    $HashRePassWord=sha1($_POST["Password"]);
    $RePassWord=$_POST["Passwordagain"];
    if(ValidateUserId($Userid)==""&&ValidateName($Name)==""
            &&ValidatePhone($PhoneNumber)==""&&ValidatePassword($PassWord)==""
            &&ValidateRePassword($RePassWord,$PassWord)=="")
    {
        @$link= mysqli_connect('localhost','PHPSCRIPT','1234','CST8257project',3306);
        if(!$link)
        {
            die('System is currently unavailable, please try later.');
        }   
        $insertStudent="INSERT INTO User VALUES('$Userid','$Name','$PhoneNumber','$HasPassWord')";
        if($result=mysqli_query($link, $insertStudent))
        {       
            $LoginOrNot="yes";
            $_SESSION["login"] = $LoginOrNot;
            $_SESSION["Userid"]=$Userid; 
            header("Location: AddAlbum.php");
        }
        else
        {
            echo "Query fail! Error:  ". mysqli_error($link);
        }
        mysqli_close($link);
    }
?>
<html>
    <head>
        <title>Sign Up</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    </head>
    <body>
    <div class="container">
    <form method = "post" action = "NewUser.php">
        <h1 class="col-md-6 col-md-offset-1">Sign Up</h1>
        <div class="row vertical-margin form-group">
            <div class="col-md-6">
                <p>All fields are required</p>
            </div>
        </div>
        <div class="row vertical-margin form-group">
            <div class="col-md-3">
                Student ID:
            </div>
            <div class="col-md-3">
                  <input type = "text" name = "Userid" value="<?php echo isset($_POST['Userid']) ? $_POST['Userid'] : '' ?>" />  
            </div>
        <div class="col-md-3 text-danger">
                <?php if($_POST){
                    echo ValidateUserId($Userid);
                }?>
        </div>
        </div>
    <div class="row vertical-margin form-group">
            <div class="col-md-3">
                Name:
            </div>
            <div class="col-md-3">
                  <input type = "text" name = "Name" value="<?php echo isset($_POST['Name']) ? $_POST['Name'] : '' ?>" />  
            </div>
        <div class="col-md-3 text-danger">
                <?php if($_POST){
                    echo ValidateName($Name);
                }?>
        </div>
    </div>
    <div class="row vertical-margin form-group">
            <div class="col-md-3">
                Phone Number:(nnn-nnn-nnnn)
            </div>
            <div class="col-md-3">
                  <input type = "text" name = "Phonenumber" value="<?php echo isset($_POST['Phonenumber']) ? $_POST['Phonenumber'] : '' ?>" />  
            </div>
        <div class="col-md-2 text-danger">
                <?php if($_POST){
                    echo ValidatePhone($PhoneNumber);
                }?>
        </div>
    </div>
    <div class="row vertical-margin form-group">
            <div class="col-md-3">
                Password:
            </div>
            <div class="col-md-3">
                  <input type = "text" name = "Password" value="<?php echo isset($_POST['Password']) ? $_POST['Password'] : '' ?>" />  
            </div>
        <div class="col-md-3 text-danger">
                <?php if($_POST){
                    echo ValidatePassword($PassWord);
                }?>
        </div>
    </div>
    <div class="row vertical-margin form-group">
            <div class="col-md-3">
                Password Again:
            </div>
            <div class="col-md-3">
                  <input type = "text" name = "Passwordagain" value="<?php echo isset($_POST['Passwordagain']) ? $_POST['Passwordagain'] : '' ?>" />  
            </div>
        <div class="col-md-2 text-danger">
                <?php if($_POST){
                    echo ValidateRePassword($RePassWord,$PassWord);
                }?>
        </div>
    </div>
    <div class="row vertical-margin form-group">
            <div class="col-md-3">
                <button name="submit" type="submit" class="btn btn-success">Submit</button>               
            </div>
            <div class="col-md-4">
                  <input type = "reset" onclick="location.href='NewUser.php'" class="btn btn-warning" />
            </div>                         
    </div>
    </form>
    </div>
    </body>
</html>


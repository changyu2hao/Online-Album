<?php include("./ProjectCommon/Header.php"); ?>
<?php include('./ProjectCommon/Footer.php'); ?>
<?php session_start();
include 'ProjectCommon/Functions.php';
?>
<html>
    <head>
        <title>Home</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    </head>
    <body>
        <?php 
        $Userid=$_SESSION["Userid"];
        $Title=$_POST["title"];
        $Description=$_POST['description'];
        $ButtonSubmit=$_POST["submit"];
        //$accessibility=$_GET["Accessibility"];
        $accessibility=$_POST["accessibility"];
        $dbConnection = parse_ini_file("db_connection.ini");
        $date=date("Y/m/d");
        if(empty($Userid))
        {
            header("Location: Login.php"); 
        }
        if(isset($ButtonSubmit))
        {
            if(ValidateTitle($Title)==""&&ValidateAccessibility($accessibility)=="")
            {
                extract($dbConnection);
                $myPdo = new PDO($dsn, $user, $password);
                $sqlstatementaddalbum="INSERT INTO Album VALUES(default,:Title,:Description,:date,:Userid,:accessibility)";
                $pStmtaddalbum = $myPdo -> prepare($sqlstatementaddalbum);
                $pStmtaddalbum ->execute( [ 'Title' => $Title,'Description'=>$Description,'date'=>$date,'Userid'=>$Userid,'accessibility'=>$accessibility] );
            }
        }
        extract($dbConnection);
        $myPdo = new PDO($dsn, $user, $password);
        $sqlstatementname='select Name FROM User where UserId=:Id';//staement for the name
        $pStmtname = $myPdo -> prepare($sqlstatementname);
        $pStmtname ->execute( [ 'Id' => $Userid] );
        $name = $pStmtname->fetchColumn();
        ?>
        <div class="container">
            <form method = "post" action = "AddAlbum.php">
            <h1 class="col-md-6 col-md-offset-4">Create New Album</h1>
            <div class="row vertical-margin form-group">
            <div class="col-md-6">
                <p>Welcome <?php echo '<strong>'.$name.'</strong>'?>!(not you? change user <a href="Login.php">here</a>)</p>            
            </div>
            </div>
            <div class="row vertical-margin form-group">
            <div class="col-md-3">
                Title:
            </div>
            <div class="col-md-3">
                  <input type = "text" name = "title" value="<?php echo isset($_POST['title']) ? $_POST['title'] : '' ?>" />  
            </div>
            <div class="col-md-2 text-danger">
                <?php if($_POST){
                    echo ValidateTitle($Title);
                }?>
            </div>
            </div>
            <div class="row vertical-margin form-group">
            <div class="col-md-3">
                Accessibility:
            </div>
            <?php 
            extract($dbConnection);
            $myPdo = new PDO($dsn, $user, $password);
            $sqlstatementAc='select * FROM Accessibility';
            $pStmtAc = $myPdo -> prepare($sqlstatementAc);
            $pStmtAc ->execute();
            $AccesssibilityDropDown=$pStmtAc->fetchAll();
            ?>
            <div class="col-md-3">
                  <select name="accessibility" class="form-control" >
                    <?php foreach ($AccesssibilityDropDown as $row): ?>
                    <?php $selected=(isset($accessibility)&&$accessibility== $row["Accessibility_Code"])?"selected":""?>
                    <option value="<?php echo $row["Accessibility_Code"] ?>" 
                        <?php echo $selected ?>  ><?php echo $row["Description"] ?>
                    </option>                              
                    <?php endforeach ?>                     
                </select>
            </div>
            <div class="col-md-2 text-danger">
                <?php if($_POST){
                    echo ValidateAccessibility($accessibility);
                }?>
            </div>
            </div>
            <div class="row vertical-margin form-group">
            <div class="col-md-3">
                Description:
            </div>
            <div class="col-md-3">
                  <textarea name="description" id="textarea-char-counter" class="form-control md-textarea" length="120" rows="5" value="<?php echo isset($_POST['description']) ? $_POST['description'] : '' ?>"></textarea>
            </div>
            </div>
            <div class="row vertical-margin form-group">
            <div class="col-md-5">
                <button name="submit" type="submit" value="validate" class="btn btn-success">Submit</button>
                <input type = "reset" onclick="location.href='AddAlbum.php'" class="btn btn-warning" />
            </div>                       
            </div>
            </form>
           
            
        </div>
    </body>
</html>
<?php include("./ProjectCommon/Header.php"); ?>
<?php include('./ProjectCommon/Footer.php'); ?>
<?php session_start(); ?>
<html>
    <head>
        <title>Home</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    </head>
    <body>
        <?php 
        include 'ProjectCommon/Functions.php';
        $Userid=$_SESSION["Userid"];
        if(empty($Userid))
        {
            header("Location: Login.php"); 
        }
        $dbConnection = parse_ini_file("db_connection.ini");
        extract($dbConnection);
        $myPdo = new PDO($dsn, $user, $password);
        $sqlstatementname='select Name FROM User where UserId=:Id';//staement for the name
        $pStmtname = $myPdo -> prepare($sqlstatementname);
        $pStmtname ->execute( [ 'Id' => $Userid] );
        $ButtonSubmit=$_POST['Save'];
        $name = $pStmtname->fetchColumn();
        $accessibility=$_POST['accessibility'];
        if($_GET['action']=='delete'&&isset($_GET['id']))
        {
            $AlbumId=$_GET['id'];
            $sqlselect="select Picture_Id from Picture where Album_Id=:Album_Id";
            $stmt3= $myPdo->prepare($sqlselect);
            $stmt3->execute( [ 'Album_Id' => $AlbumId] );
            $TemID = $stmt3->fetchAll();
            foreach($TemID as $PerID)
            {
                $sqlDeleteComment="Delete from Comment where Picture_Id=:Picture_Id && Author_Id=:Author_Id";
                $stmt2= $myPdo->prepare($sqlDeleteComment);
                $stmt2->execute( [ 'Picture_Id' => $PerID['Picture_Id'],'Author_Id' => $Userid] );
                
            }
            
            $sqlDeletePic="Delete from picture Where Album_Id =:Album_Id";
            $stmt = $myPdo->prepare($sqlDeletePic);
            $stmt->execute( [ 'Album_Id' => $AlbumId] );
            $deleteAlbumn="Delete from album where Album_Id=:Album_Id";
            $stmt1=$myPdo->prepare($deleteAlbumn);
            $stmt1->execute( [ 'Album_Id' => $AlbumId] );
                       
        }
        if(isset($ButtonSubmit))
        {
            foreach($accessibility as $acce)
            {
                $sqlstatementupdate="UPDATE Album SET Accessibility_Code = :Accessibility_Code WHERE Owner_Id=:Owner_Id AND Album_Id=:Album_Id";
                $pStmtupdate = $myPdo -> prepare($sqlstatementupdate);
                $value = explode("|",$acce);
                $pStmtupdate ->execute( [  'Accessibility_Code' =>$value[0],'Owner_Id'=>$Userid,'Album_Id'=>$value[1]] );
                
            }
        }
        ?>
        <div class="container">
            <form method = "post" action = "MyAlbums.php">
            <h1 class="col-md-6 col-md-offset-4">My Albums</h1>
            <div class="row vertical-margin form-group">
            <div class="col-md-6">
                <p>Welcome <?php echo '<strong>'.$name.'</strong>'?>!(not you? change user <a href="Login.php">here</a>)</p>            
            </div>
            </div>
            <div class="col-md-6 col-md-offset-10">
                    <p><a href="AddAlbum.php">Create a New Album</a></p>            
            </div>
                
            <div class="row vertical-margin form-group">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr><th scope="col">Title</th>
                            <th scope="col">Data Updated</th>
                            <th scope="col">Number of Pictures</th>
                            <th scope="col">Accessibility</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    //$myPdo = new PDO("mysql:host=localhost;dbname=CST8257;port=3306;charset=utf8", "PHPSCRIPT", "1234");
                    $sqlstatementshow="Select Album.Album_Id,Album.Title,Album.Date_Updated,Album.Accessibility_Code,Album.Date_Updated,Accessibility.Description
                    from Album left join Accessibility on Album.Accessibility_Code=Accessibility.Accessibility_Code
                    where Owner_Id=:Owner_Id";
                    //$sqlstatement1="Select * from Registration where (StudentId=:StudentId AND SemesterCode=:SemesterCode)";     
                    $pStmtshow = $myPdo -> prepare($sqlstatementshow);
                    //$pStmt1 = $myPdo -> prepare($sqlstatement1);
                    $pStmtshow ->execute( [ 'Owner_Id' => $Userid] );
                    //$pStmt1 ->execute( [ 'StudentId' => $Studentid,'SemesterCode'=> $Semester] );
                    $Album = $pStmtshow->fetchAll();
                    //$Course1=$pStmt1->fetchAll();
                    
                    foreach ($Album as $row): ?>
                        <?php 
                            $Album_Id=$row["Album_Id"];
                            $sqlstatementpicturescount="Select count(*) from Picture where Album_Id=:Album_Id";
                            $pStmtpicturescount=$myPdo-> prepare($sqlstatementpicturescount);
                            $pStmtpicturescount ->execute( [ 'Album_Id' => $Album_Id] );
                            $PictureNumber = $pStmtpicturescount->fetchColumn();
                        ?>
                    <tr>                       
                        <td scope="col"><?php echo $row["Title"] ?></td>
                            <td scope="col"><?php echo $row["Date_Updated"] ?></td>
                            <td scope="col"><?php echo $PictureNumber ?></td>
                            <td scope="col">
                            <?php
                                //$Album_Id=$row['Album_Id'];
                                $sqlstatementAcc='select Accessibility_Code,Description FROM Accessibility';
                                $pStmtAcc = $myPdo -> prepare($sqlstatementAcc);
                                $pStmtAcc ->execute();
                                $Acc = $pStmtAcc->fetchAll();
                            ?>   
                            <select name="accessibility[]" class="form-control" >
                            <?php foreach ($Acc as $row1): ?>
                            <?php $selected=(isset($row["Accessibility_Code"])&&$row["Accessibility_Code"]== $row1["Accessibility_Code"])?"selected":""?>
                            <option value="<?php $Acode=$row1["Accessibility_Code"]; print("$Acode|$Album_Id"); ?>" 
                            <?php echo $selected ?>><?php echo $row1["Description"] ?>
                            </option>                              
                            <?php endforeach ?>                     
                            </select>                                
                            </td>
                            <td scope='col'><a href='MyAlbums.php?action=delete&id=<?php echo $Album_Id?>' onclick='return myFunctionDelete()'/>Delete</td>
                    </tr>
                    <?php endforeach ?>
                    </tbody> 
                </table>
            </div>   
            </div>
            <div class="row vertical-margin form-group">
            <div class="col-md-5">
                <button name="Save" type="submit" class="btn btn-success">Save Changes</button>
            </div>                       
            </div>
            </form>
        </div>
    </body>
    <script>
    function myFunctionDelete() 
    {
        if(confirm("The selected album and its pictures will be deleted!"))
        {
            return true;
        }
        else
        {
            return false; 
        }      
    }
    </script>
</html>

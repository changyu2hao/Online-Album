<?php include("./ProjectCommon/Header.php"); ?>
<?php include('./ProjectCommon/Footer.php'); ?>

<?php session_start();
    include 'ProjectCommon/Functions.php';
    $Userid=$_SESSION["Userid"];
    $AlbumDrop=$_POST['AlbumDropList'];
    $dbConnection = parse_ini_file("db_connection.ini");
?>
<html>
    <head>
        <title>Home</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    </head>
    <body>
    
        <div class="container">
        <form method = "post" action = "AddAlbum.php">
        <h1 class="col-md-6 col-md-offset-4">My Pictures</h1>
        <div class="row vertical-margin form-group">
            <?php 
            extract($dbConnection);
            $myPdo = new PDO($dsn, $user, $password);
            $sqlstatementAlbum='select Title,Album_Id,Date_Updated FROM Album where Owner_Id=:Userid';
            $pStmtAlbum = $myPdo -> prepare($sqlstatementAlbum);
            $pStmtAlbum ->execute(['Userid' => $Userid]);
            $AlbumDropDownList=$pStmtAlbum->fetchAll();
            ?>
            <div class="col-md-8">
                <select name="AlbumDropList" class="form-control" >
                    <?php foreach ($AlbumDropDownList as $row): ?>
                    <?php $selected=(isset($AlbumDrop)&&$AlbumDrop== $row["Album_Id"])?"selected":""?>
                    <option value="<?php echo $row["Album_Id"] ?>" 
                        <?php echo $selected ?>  ><?php echo $row["Title"]."-updated on ".$row["Date_Updated"] ?>
                    </option>                              
                    <?php endforeach ?>                     
                </select>
            </div>           
        </div>
        </form>
        </div>
    </body>
</html>


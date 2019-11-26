<?php include("./ProjectCommon/Header.php"); ?>
<?php include('./ProjectCommon/Footer.php'); ?>
<?php session_start();
    include 'ProjectCommon/Functions.php';
    $Userid=$_SESSION["Userid"];
    $AlbumDrop=$_POST['AlbumDrop'];
    $ButtonSubmit=$_POST["submit"];
    $Title=$_POST["title"];
    $Description=$_POST['description'];
    $date=date("Y/m/d");
    $dbConnection = parse_ini_file("db_connection.ini");
    $erroralbumdrop="";
    $errortitle="";
    if(isset($ButtonSubmit))
    {
        $erroralbumdrop=ValidateAlbumDrop($AlbumDrop,$Userid);
        $errortitle=ValidateTitle($Title);
                
        $destination = './uploads';       	// define the path to a folder to save the file
	if (!file_exists($destination))
	{
            mkdir($destination);
	}
    
    for ($j = 0; $j < count($_FILES['txtUpload']['tmp_name']); $j++)
	{
		if ($_FILES['txtUpload']['error'][$j] == 0&&$erroralbumdrop==""
                        &&$errortitle=="")
		{
			$fileTempPath = $_FILES['txtUpload']['tmp_name'][$j];
			$filePath = $destination."/".$_FILES['txtUpload']['name'][$j];
			
			$pathInfo = pathinfo($filePath);
			$dir = $pathInfo['dirname'];
			$fileName = $pathInfo['filename'];
			$ext = $pathInfo['extension'];
			
			$i="";
			while (file_exists($filePath))
			{	
				$i++;
				$filePath = $dir."/".$fileName."_".$i.".".$ext;
			}
			move_uploaded_file($fileTempPath, $filePath);
                        $dbConnection = parse_ini_file("db_connection.ini");
                        extract($dbConnection);
                        $myPdo = new PDO($dsn, $user, $password);
                        $sqlstatementpictures='INSERT INTO Picture VALUES(default,:Album_Id,:FileName,:Title,:Description,:Date_Added)';
                        $pStmtpictures = $myPdo -> prepare($sqlstatementpictures);
                        $pStmtpictures ->execute( [ 'Album_Id' => $AlbumDrop,'FileName'=>$fileName,'Title'=>$Title,'Description'=>$Description,'Date_Added'=>$date] );
                        $errorupload="";
		}
		elseif ($_FILES['txtUpload']['error'][$j]  == 1)
		{			
			$errorupload= "$fileName is too large";
		}
		elseif ($_FILES['txtUpload']['error'][$j]  == 4)
		{
			$errorupload= "No upload file specified"; 
		}
		else
		{
			$errorupload= "Error happened while uploading the file(s). Try again late"; 
		}
	}
    }
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
            <form method = "post" action = "UploadPictures.php" enctype="multipart/form-data">
            <h1 class="col-md-6 col-md-offset-4">Upload Pictures</h1>
            <div class="row vertical-margin form-group">
            <div class="col-md-8">
                <p>Accepted picture types JPG(JPEG), GIF and PNG</p>
                <p>You can upload multiple pictures at a time crossing the shift key while selecting pictures.</p>
                <p>When uploading multiple pictures, the title and description fields will be applied to all pictures.</p>
                <br>
            </div>
            </div>
            <div class="row vertical-margin form-group">
            <div class="col-md-3">
                Upload to Album:
            </div>
            <?php 
            extract($dbConnection);
            $myPdo = new PDO($dsn, $user, $password);
            $sqlstatementalbum='select Title,Album_Id FROM Album where Owner_Id=:Userid';
            $pStmtalbum = $myPdo -> prepare($sqlstatementalbum);
            $pStmtalbum ->execute(['Userid' => $Userid]);
            $AlbumDropDown=$pStmtalbum->fetchAll();
            ?>
            <div class="col-md-3">
                <select name="AlbumDrop" class="form-control" >
                    <?php foreach ($AlbumDropDown as $row): ?>
                    <?php $selected=(isset($AlbumDrop)&&$AlbumDrop== $row["Title"])?"selected":""?>
                    <option value="<?php echo $row["Album_Id"] ?>" 
                        <?php echo $selected ?>  ><?php echo $row["Title"] ?>
                    </option>                              
                    <?php endforeach ?>                     
                </select>
            </div>
            <div class="col-md-2 text-danger">
                <?php echo $erroralbumdrop; ?>             
            </div>
            </div>
            <div class="row vertical-margin form-group">
            <div class="col-md-3">
                File to Upload:
            </div>
            <div class="col-md-3">
                <input type="file" name="txtUpload[]" size="40" accept="image/*" multiple/>  
            </div>
            <div class="col-md-2 text-danger">
                <?php echo $errorupload; ?>
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
                <?php echo $errortitle; ?>
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
                <input type = "reset" onclick="location.href='UploadPictures.php'" class="btn btn-warning" />
            </div>                       
            </div>
            </form>
            
    </body>
</html>


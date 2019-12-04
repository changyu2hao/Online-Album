<?php
    //$dbConnection = parse_ini_file("db_connection.ini");
    function ValidateUserId($id)
    {
        @$link= mysqli_connect('localhost','PHPSCRIPT','1234','CST8257project',3306);
        if(empty($id))
        {
            $errorid="Student ID cannot be empty!";
        }
        else
        {
           if(!$link)
            {
                die('System is currently unavailable, please try later.');
            }
            $result = @$link->query("SELECT * FROM `User` WHERE `UserId` = '$id'");
            if($result->num_rows == 0) {
                $errorid="";
            }
            else 
            {
                $errorid="A student with this ID has already signed up";
            } 
        }      
        $link->close();
        return $errorid;
    }
    function ValidateIdLogIn($id)
    {
        if(empty($id))
        {
            $erroridlogin="Student ID cannot be empty!";
        }
        else
        {
            $erroridlogin="";
        }
        return $erroridlogin;
    }
    function ValidateName($name)
    {
        if(empty($name))
        {
            $errorname="Name cannot be empty!";
        }
        else
        {
            $errorname="";
        }
        return $errorname;
    }
    function ValidatePhone($phone)
    {
        $phoneNumberRegex="/^\d{3}-\d{3}-\d{4}$/i";
        if(empty($phone))
        {
            $errorphone="Phone Number cannot be empty!";
        }
        elseif(preg_match($phoneNumberRegex,$phone))
        {
            $errorphone="";
        }
        else
        {
            $errorphone="Incorrect phone number";
        }
        return $errorphone;
    }
    function ValidatePassword($Password)
    {
        $PasswordRegex="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/";
        if(empty($Password))
        {
            $errorpassword="Password cannot be empty!";
        }
        elseif(preg_match($PasswordRegex,$Password))
        {
            $errorpassword="";
        }
        else
        {
            $errorpassword="Password should be at least 6 characterslong, contain at least one upper case, one lower case and one digit";
        }
        
        return $errorpassword;       
    }
    function ValidatePasswordLogIn($Password)
    {
        if(empty($Password))
        {
            $errorpasswordlogin="Password cannot be empty!";
        }
        else
        {
            $errorpasswordlogin="";
        }
        return $errorpasswordlogin;
    }
    function ValidateRePassword($RePassWord,$Password)
    {
        if(empty($RePassWord))
        {
            $errorrepassword="Cannot be empty!";
        }
        elseif($RePassWord!=$Password)
        {
            $errorrepassword="Please make sure you inputted the same password!";       
        }
        else{
            $errorrepassword="";
        }
        return $errorrepassword;
    }
    function ValidateLogin($Userid,$PassWord,$Info)
    {
        @$link= mysqli_connect('localhost','PHPSCRIPT','1234','CST8257project',3306);
        if(!$link)
        {
            die('System is currently unavailable, please try later.');
        }
        $SelectStudent="SELECT UserId,Password FROM User WHERE UserId = '$Userid'";
        if($result=mysqli_query($link, $SelectStudent))
        {       
            if($result->num_rows == 0)
            {
                $Info="Incorrect User ID and/or Password!";
            }
            else
            {
                While($User = mysqli_fetch_assoc($result))
                {
                    if($User[UserId]==$Userid&&$User[Password]==$PassWord)
                    {
                        $Info="";
                        header("Location: MyAlbums.php");
                    }
                    else
                    {
                        $Info="Incorrect student ID and/or Password!";
                    }
                }
            }
            return $Info;
        }
    }
    function ValidateCheckButton($id,$code,$RegisteredCode)
    {        
        $totalhours=0;
        if(count($RegisteredCode)==0)
        {
            $errorcoursesselection=1;
        }
        else
        {
        $allhours=0;
        $allhours1=0;
        extract($dbConnection);
        $myPdo = new PDO($dsn, $user, $password);
        $sqlstatementregistration="select CourseCode FROM Registration where StudentId=:Id AND SemesterCode=:semestercode";
        $pStmtregistration = $myPdo -> prepare($sqlstatementregistration);
        $pStmtregistration ->execute( [ 'Id' => $id,'semestercode'=>$code] );
        $registration = $pStmtregistration->fetchAll();
        if(empty($registration)==false)
        {
            foreach ($registration as $rowcode)
            {
                $sqlstatementhours="select WeeklyHours FROM Course where CourseCode=:rowcode";
                $pStmthours = $myPdo -> prepare($sqlstatementhours);
                $pStmthours ->execute( [ 'rowcode' => $rowcode["CourseCode"]] );
                $hours = $pStmthours->fetchColumn();
                $allhours+=$hours;
            }            
        }
        else
        {
            $allhours=0;
        }
        foreach($RegisteredCode as $codeelement)
            {
                $sqlstatementhours1="select WeeklyHours FROM Course where CourseCode=:codeelement";
                $pStmthours1 = $myPdo -> prepare($sqlstatementhours1);
                $pStmthours1 ->execute( [ 'codeelement' => $codeelement] );
                $hours1=$pStmthours1->fetchColumn();
                $allhours1+=$hours1;
            }        
        $totalhours=$allhours+$allhours1;
        if($totalhours>16)
        {
            $errorcoursesselection=2;
        }
        else
        {
            $errorcoursesselection="";
        }
        }
        return $errorcoursesselection;
    }
    function ValidateTitle($Title)
    {
        if(empty($Title))
        {
            $errortitle="Title cannot be empty!";
        }
        else 
        {
            $errortitle="";
        }
        return $errortitle;
    }
    function ValidateAccessibility($accessibility)
    {
        if($accessibility=="private"||$accessibility=="shared")
        {
            $errorac=""; 
        }
        else
        {
            $errorac="Please don't modify the source code";
        }
        return $errorac;
    }
    function ValidateAlbumDrop($AlbumDrop,$Userid)
    {
        $dbConnection = parse_ini_file("db_connection.ini");
        extract($dbConnection);
        $myPdo = new PDO($dsn, $user, $password);
        $sqlstatementalbum='select Album_Id FROM Album where Owner_Id=:Userid';
        $pStmtalbum = $myPdo -> prepare($sqlstatementalbum);
        $pStmtalbum ->execute(['Userid' => $Userid]);
        $AlbumDropDown=$pStmtalbum->fetchAll();
        foreach ($AlbumDropDown as $row)
        {
            if($AlbumDrop==$row['Album_Id'])
            {
                $erroralbumdrop="";
                break;
            }
            else
            {
                $erroralbumdrop="Please don't modify the source code";
            }
        }
        return $erroralbumdrop;
        
    }
    function ValidateBlankAlbum($albumTxt){
        if ($albumTxt == ""){
            return "Album is required";
        }
    }
    function ValidateFileUpload($files, $name){
        $allowed =  array('gif','png' ,'jpg', 'jpeg');
        $total = count($_FILES[$name]['name']);
        if (in_array(1, $files[$name]['error'], false))
        {
            return "Upload file is too large"; 
        }
        if (in_array(4, $files[$name]['error'], false))
        {
            return "No upload file specified"; 
        }
        //validates extensions and sizes for all files
        for ($i=0; $i < $total ; $i++) {
            $filename = $files[$name]['name'][$i];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if(!in_array($ext, $allowed)){
                return 'Accepted picture types: JPG(JPEG), GIF and PNG!';
            }
        }
    }
    
?>



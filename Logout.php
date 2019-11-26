<?php include("./ProjectCommon/Header.php"); ?>
<?php include('./ProjectCommon/Footer.php'); ?>
<?php
session_start();
if(isset($_SESSION["login"]))
{
    session_destroy();
    header("Location: Index.php");
    exit();
}

?>



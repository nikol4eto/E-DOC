<?php

session_start();

if(isset($_SESSION["user"])){
    if(($_SESSION["user"])=="" or $_SESSION['usertype']!='p'){
        header("location: ../login.php");
    }else{
        $useremail=$_SESSION["user"];
    }

}else{
    header("location: ../login.php");
}

include("../connection.php");
$sqlmain= "select * from patient where pemail=?";
$stmt = $database->prepare($sqlmain);
$stmt->bind_param("s",$useremail);
$stmt->execute();
$userrow = $stmt->get_result();
$userfetch=$userrow->fetch_assoc();
$userid= $userfetch["pid"];
$username=$userfetch["pname"];


$appoid = $_GET['appoid'];
$rating = $_GET['rating'];
$sqlUpdate = "UPDATE appointment
SET appointment_rating = $rating
WHERE appoid = $appoid
AND pid = $userid

 ";
$updateStmt =$database->prepare($sqlUpdate);
$updateStmt->execute();
$updateStmt->get_result();
?>
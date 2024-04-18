<?php

    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='a'){
            header("location: ../login.php");
        }

    }else{
        header("location: ../login.php");
    }
    
    
    if($_GET){

        include("../connection.php");
        $id=$_GET["id"];
        
        $sql= $database->query("delete from schedule where scheduleid='$id';");
  
        header("location: schedule.php");
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>Appointments</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }

.star-rating {
  direction: rtl; 
  font-size: 30px;
}

.star-rating input[type="radio"] {
  display: none;
}

.star-rating label {
  color: #ccc;
  cursor: pointer;
}

.star-rating input[type="radio"]:checked ~ label {
  color: orange;
}

.star-rating input[type="radio"]:disabled ~ label {
  cursor: default; /* Remove pointer cursor */
  opacity: 0.5; /* Reduce opacity for visual indication */
}

/* Style for the "Rate" button */
.rate-btn {
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  padding: 10px 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
  border-radius: 5px; /* Optional rounded corners */
}
.disabled{
    opacity: 0.5;

}
.star-rating:not([data-past="true"]) .rate-btn { /* Enable "Rate" button for past appointments */
  opacity: 1; 
  cursor: pointer;
}     
        
</style>
</head>
<body>
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
    

    //import database
    include("../connection.php");
    $sqlmain= "select * from patient where pemail=?";
    $stmt = $database->prepare($sqlmain);
    $stmt->bind_param("s",$useremail);
    $stmt->execute();
    $userrow = $stmt->get_result();
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];


    //echo $userid;
    //echo $username;


    //TODO
    $sqlmain= "select appointment.appoid,schedule.scheduleid,schedule.title,doctor.docname,patient.pname,schedule.scheduledate,schedule.scheduletime,appointment.apponum,appointment.appodate,appointment.appointment_rating from schedule inner join appointment on schedule.scheduleid=appointment.scheduleid inner join patient on patient.pid=appointment.pid inner join doctor on schedule.docid=doctor.docid  where  patient.pid=$userid ";

    if($_POST){
        //print_r($_POST);
        


        
        if(!empty($_POST["sheduledate"])){
            $sheduledate=$_POST["sheduledate"];
            $sqlmain.=" and schedule.scheduledate='$sheduledate' ";
        };

    

        //echo $sqlmain;

    }

    $sqlmain.="order by appointment.appodate  asc";
    $result= $database->query($sqlmain);
    ?>
    <div class="container">
        <div class="menu">
        <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px" >
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title"><?php echo substr($username,0,13)  ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail,0,22)  ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php" ><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                    </table>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-home" >
                        <a href="index.php" class="non-style-link-menu "><div><p class="menu-text">Home</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor">
                        <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">All Doctors</p></a></div>
                    </td>
                </tr>
                
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-appoinment  menu-active menu-icon-appoinment-active">
                        <a href="appointment.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">My Bookings</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></a></div>
                    </td>
                </tr>
                
            </table>
        </div>
        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr >
                    <td width="13%" >
                    <a href="appointment.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;">My Bookings history</p>
                                           
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php 

                        date_default_timezone_set('Europe/Sofia');

                        $today = date('Y-m-d');
                        echo $today;

                        
                        ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>


                </tr>
               
             
                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;" >
                    
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">My Bookings (<?php echo $result->num_rows; ?>)</p>
                    </td>
                    
                </tr>
                <tr>
                    <td colspan="4" style="padding-top:0px;width: 100%;" >
                        <center>
                        <table class="filter-container" border="0" >
                        <tr>
                           <td width="10%">

                           </td> 
                        <td width="5%" style="text-align: center;">
                        Date:
                        </td>
                        <td width="30%">
                        <form action="" method="post">
                            
                            <input type="date" name="sheduledate" id="date" class="input-text filter-container-items" style="margin: 0;width: 95%;">

                        </td>
                        
                    <td width="12%">
                        <input type="submit"  name="filter" value=" Filter" class=" btn-primary-soft btn button-icon btn-filter"  style="padding: 15px; margin :0;width:100%">
                        </form>
                    </td>

                    </tr>
                            </table>

                        </center>
                    </td>
                    
                </tr>
                
               
                  
                <tr>
                   <td colspan="4">
                       <center>
                        <div class="abc scroll">
                        <table width="93%" class="sub-table scrolldown" border="0" style="border:none">
                        
                        <tbody>
                        
                            <?php

                                
                                

                                if($result->num_rows==0){
                                    echo '<tr>
                                    <td colspan="7">
                                    <br><br><br><br>
                                    <center>
                                    <img src="../img/notfound.svg" width="25%">
                                    
                                    <br>
                                    <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">We  couldnt find anything related to your keywords !</p>
                                    <a class="non-style-link" href="appointment.php"><button  class="login-btn btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show all Appointments &nbsp;</font></button>
                                    </a>
                                    </center>
                                    <br><br><br><br>
                                    </td>
                                    </tr>';
                                    
                                }
                                else{

                                    for ( $x=0; $x<($result->num_rows);$x++){
                                        echo "<tr>";
                                        for($q=0;$q<3;$q++){
                                            $row=$result->fetch_assoc();
                                            if (!isset($row)){
                                            break;
                                            };
                                            $scheduleid=$row["scheduleid"];
                                            $title=$row["title"];
                                            $docname=$row["docname"];
                                            $scheduledate=$row["scheduledate"];
                                            $scheduletime=$row["scheduletime"];
                                            $apponum=$row["apponum"];
                                            $appodate=$row["appodate"];
                                            $appoid=$row["appoid"];
                                            $rating=intval($row["appointment_rating"]);
                                            $btnClass=($rating>0)?("disabled"):("");
                                            

    
                                            if($scheduleid==""){
                                                break;
                                            }
    
                                            echo '
<td style="width: 25%;">
    <div class="dashboard-items search-items">
        <div style="width:100%;">
            <div class="h3-search">
                Booking Date: '.substr($appodate, 0, 30).'<br>
                Reference Number:'.$appoid.'
            </div>
            <div class="h1-search">
                '.substr($title, 0, 21).'<br>
            </div>
            <div class="h3-search">
                Appointment Number:<div class="h1-search">0'.$apponum.'</div>
            </div>
            <div class="h3-search">
                '.substr($docname, 0, 30).'
            </div>
            <div class="h4-search">
                Scheduled Date: '.$scheduledate.'<br>Starts: <b>@';
                
                // Calculate and display adjusted start time based on appointment number
                $baseStartTime = new DateTime(substr($scheduletime, 0, 5));
                $appointmentDuration = 15; // Assuming each appointment lasts for 15 minutes
                $timeDifference = ($apponum - 1) * $appointmentDuration;
                $newStartTime = $baseStartTime->modify("+".$timeDifference." minutes");
                echo $newStartTime->format('H:i'); // Format time as desired
                
echo '</b> (24h)
            </div>
            <br>';
            
            // Check if appointment date has passed
            if (strtotime($scheduledate) < strtotime(date('Y-m-d'))) {
                echo '
                <div class="appointment-container" id="appointment-container-'.$appoid.'">
                <h2 class="appointment-title"></h2>
                <p class="appointment-time">Rate us</p>
                <div class="star-rating" data-past="true" data-rating-id="rating1" data-appointment-id='.$appoid.'>
                  <input type="radio" id="star5-rating'.$appoid.'" name="rating-group'.$appoid.'" data-appointment-id='.$appoid.' value="5">
                  <label for="star5-rating1" data-appointment-id='.$appoid.' value="5">★</label>
                  <input type="radio" id="star4-rating'.$appoid.'" name="rating-group'.$appoid.'" data-appointment-id='.$appoid.' value="4">
                  <label for="star4-rating1" data-appointment-id='.$appoid.' value="4">★</label>
                  <input type="radio" id="star3-rating'.$appoid.'" name="rating-group'.$appoid.'" data-appointment-id='.$appoid.' value="3">
                  <label for="star3-rating1" data-appointment-id='.$appoid.' value="3">★</label>
                  <input type="radio" id="star2-rating'.$appoid.'" name="rating-group'.$appoid.'" data-appointment-id='.$appoid.' value="2">
                  <label for="star2-rating1" data-appointment-id='.$appoid.' value="2">★</label>
                  <input type="radio" id="star1-rating'.$appoid.'" name="rating-group'.$appoid.'" data-appointment-id='.$appoid.' value="1">
                  <label for="star1-rating1" data-appointment-id='.$appoid.' value="1">★</label>
                  <button class="rate-btn '.$btnClass.'"  data-appointment-id='.$appoid.'>Rate</button>

                </div>
                
              </div>
              
                
                
                ';
                
            } else {
                echo '
                <a href="?action=drop&id='.$appoid.'&title='.$title.'&doc='.$docname.'">
                    <button class="login-btn btn-primary-soft btn" style="padding-top:11px;padding-bottom:11px;width:100%">
                        <font class="tn-in-text">Cancel Booking</font>
                    </button>
                </a>';
            }
            
echo '
        </div>
    </div>
</td>';

                                            

                                            
                                            
                                        }
                                        echo "</tr>";
                           
                                }
                            }
                                 
                            ?>
 
                            </tbody>

                        </table>
                        </div>
                        </center>
                   </td> 
                </tr>
                       
                        
                        
            </table>
        </div>
    </div>
    <?php
    
    if($_GET){
        $id=$_GET["id"];
        $action=$_GET["action"];
        if($action=='booking-added'){
            
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                    <br><br>
                        <h2>Booking Successfully.</h2>
                        <a class="close" href="appointment.php">&times;</a>
                        <div class="content">
                        Your Appointment number is '.$id.'.<br><br>
                            
                        </div>
                        <div style="display: flex;justify-content: center;">
                        
                        <a href="appointment.php" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;OK&nbsp;&nbsp;</font></button></a>
                        <br><br><br><br>
                        </div>
                    </center>
            </div>
            </div>
            ';
        }elseif($action=='drop'){
            $title=$_GET["title"];
            $docname=$_GET["doc"];
            
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2>Are you sure?</h2>
                        <a class="close" href="appointment.php">&times;</a>
                        <div class="content">
                            You want to Cancel this Appointment?<br><br>
                            Session Name: &nbsp;<b>'.substr($title,0,40).'</b><br>
                            Doctor name&nbsp; : <b>'.substr($docname,0,40).'</b><br><br>
                            
                        </div>
                        <div style="display: flex;justify-content: center;">
                        <a href="delete-appointment.php?id='.$id.'" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"<font class="tn-in-text">&nbsp;Yes&nbsp;</font></button></a>&nbsp;&nbsp;&nbsp;
                        <a href="appointment.php" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;No&nbsp;&nbsp;</font></button></a>

                        </div>
                    </center>
            </div>
            </div>
            '; 
        }elseif($action=='view'){
            $sqlmain= "select * from doctor where docid=?";
            $stmt = $database->prepare($sqlmain);
            $stmt->bind_param("i",$id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row=$result->fetch_assoc();
            $name=$row["docname"];
            $email=$row["docemail"];
            $spe=$row["specialties"];
            
            $sqlmain= "select sname from specialties where id=?";
            $stmt = $database->prepare($sqlmain);
            $stmt->bind_param("s",$spe);
            $stmt->execute();
            $spcil_res = $stmt->get_result();
            $spcil_array= $spcil_res->fetch_assoc();
            $spcil_name=$spcil_array["sname"];
            $nic=$row['docnic'];
            $tele=$row['doctel'];
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2></h2>
                        <a class="close" href="doctors.php">&times;</a>
                        <div class="content">
                            eDoc Web App<br>
                            
                        </div>
                        <div style="display: flex;justify-content: center;">
                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                        
                            <tr>
                                <td>
                                    <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">View Details.</p><br><br>
                                </td>
                            </tr>
                            
                            <tr>
                                
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Name: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.$name.'<br><br>
                                </td>
                                
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Email" class="form-label">Email: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                '.$email.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="nic" class="form-label">NIC: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                '.$nic.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Tele" class="form-label">Telephone: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                '.$tele.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="spec" class="form-label">Specialties: </label>
                                    
                                </td>
                            </tr>
                            <tr>
                            <td class="label-td" colspan="2">
                            '.$spcil_name.'<br><br>
                            </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="doctors.php"><input type="button" value="OK" class="login-btn btn-primary-soft btn" ></a>
                                
                                    
                                </td>
                
                            </tr>
                           

                        </table>
                        </div>
                    </center>
                    <br><br>
            </div>
            </div>
            ';  
    }
}

?>
      <script>
         const appointmentContainers = document.querySelectorAll('.appointment-container');

appointmentContainers.forEach(container => {
  const appointmentIdString = container.id;
  const appointmentId = parseInt(appointmentIdString.split(/-/).pop())
  console.log(appointmentId+"<---APPOINTMENT ID");
  const starRating = container.querySelector('#'+appointmentIdString+' .star-rating');
  console.log(starRating);
  const rateBTN = container.querySelector('#'+appointmentIdString+' .rate-btn');


  const storedRating = parseInt(localStorage.getItem(`rating-${appointmentId}`));
  console.log(storedRating);
  if (storedRating && storedRating !== null) {
    starRating.querySelector('#'+appointmentIdString+` input[value="${storedRating}"]`).checked = true;
  }

  starRating.addEventListener('click', onRatingClick , true);
  rateBTN.addEventListener('click', updateRating , true);

});

function onRatingClick(e) {
    const _starRating = e.target;
    console.log(_starRating);
    if(_starRating.matches("label")) {
        console.log('aa');

        _appointmentId = _starRating.getAttribute('data-appointment-id');
        console.log(_appointmentId);

       // const selectedRating = starRating.querySelector('#appointment-container-'+appointmentId+' input[type="radio"]:checked').value;
       const selectedRating = _starRating.getAttribute('value');
       document.querySelector('#appointment-container-'+_appointmentId+` input[value="${selectedRating}"]`).checked = true;

        console.log(selectedRating);

        localStorage.setItem(`rating-${_appointmentId}`, selectedRating);
    }

  }

  
function updateRating(e) {
    const _rateBTN = e.target;
    console.log(_rateBTN);
    if(_rateBTN.matches("button")) {

        _appointmentId = _rateBTN.getAttribute('data-appointment-id');

        console.log(_appointmentId);

 const Rating = parseInt(localStorage.getItem(`rating-${_appointmentId}`));
  console.log(Rating);
      
        fetch(`update-rating.php?appoid=${_appointmentId}&rating=${Rating}`)
        _rateBTN.classList.add("disabled");
    }

  }
  

              </script>
                
    </div>

</body>
</html>

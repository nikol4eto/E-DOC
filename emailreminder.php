<?php

//  session_start();

// if (isset($_SESSION["user"])) {
//   if (($_SESSION["user"]) == "" || $_SESSION['usertype'] != 'p') {
//     header("location:login.php");
//     exit(); 
//   } else {
//     $useremail = $_SESSION["user"];

     include ("connection.php");

//     $sqlmain = "select * from patient where pemail=?";
//     $stmt = $database->prepare($sqlmain);
//     $stmt->bind_param("s", $useremail);
//     $stmt->execute();
//     $userrow = $stmt->get_result();
//     $userfetch = $userrow->fetch_assoc();

//     $userid = $userfetch["pid"];
//     $username = $userfetch["pname"];
//   }
// } else {
//   header("location:login.php");
//   exit(); 
// }

$reminderThreshold = 60 * 60 * 24;

$currentDate = date('Y-m-d');

$sql = "SELECT p.pemail, s.scheduledate, a.appoid
        FROM appointment AS a
        INNER JOIN patient AS p ON a.pid = p.pid
        INNER JOIN schedule AS s ON a.scheduleid = s.scheduleid
        WHERE p.pemail IS NOT NULL
        AND s.scheduledate >=CURDATE() 
        AND s.scheduledate <=CURDATE()  + INTERVAL 1 DAY
        AND a.isEmailed = 0

        ";


 
$stmt = $database->prepare($sql);
//$stmt->bind_param("s", $useremail); 

$stmt->execute();
$result = $stmt->get_result();

if (mysqli_num_rows($result) > 0) {

  while($row = $result->fetch_assoc()) {
    $patientEmail = $row["pemail"];
    $appointmentDate = $row["scheduledate"];
    $appoid =  $row["appoid"];

    echo'
<p>SENT EMAIL TO '.$patientEmail.' for '.$appointmentDate.' for appointment '.$appoid.'</p>
 ';

 
    $subject = "Appointment Reminder: Dr.'s Office";
    $message = "Dear Patient,\n\nThis is a friendly reminder that you have an appointment with Dr.'s Office on $appointmentDate.\n\nWe look forward to seeing you then!\n\nSincerely,\nDr.'s Office Team";


    $headers = 'From: Dr.'."'s Office <pupeeet333@gmail.com>\r\n";
    $headers .= 'Reply-To: pupeeet333@gmail.com\r\n';
    $headers .= 'Content-Type: text/plain; charset=UTF-8\r\n';

   $mail_sent = mail($patientEmail, $subject, $message, $headers);

  if ($mail_sent) {
      echo "Email sent successfully to $patientEmail!";

      $updateQuery = "UPDATE appointment
      SET isEmailed = 1
      WHERE appoid = $appoid; ";
      
      $updateStmt = $database->prepare($updateQuery);

      $updateStmt->execute();
      $resultUpdate = $updateStmt->get_result();

    } else {
      echo "Error sending email: " . error_get_last()['message'];
    }
  }
}

$database->close();

?>
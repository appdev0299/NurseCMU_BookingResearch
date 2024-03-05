<?php
if (
  isset($_POST['date'], $_POST['timeslot'])
) {
  require_once 'connect.php';

  $date = $_POST['date'];
  $timeslot = $_POST['timeslot'];
  $title = isset($_POST['title']) ? $_POST['title'] : ''; // กำหนดค่าเริ่มต้นเป็นค่าว่าง
  $name = isset($_POST['name']) ? $_POST['name'] : ''; // กำหนดค่าเริ่มต้นเป็นค่าว่าง
  $email = isset($_POST['email']) ? $_POST['email'] : ''; // กำหนดค่าเริ่มต้นเป็นค่าว่าง
  $tel = isset($_POST['tel']) ? $_POST['tel'] : ''; // กำหนดค่าเริ่มต้นเป็นค่าว่าง
  $meeting = isset($_POST['meeting']) ? $_POST['meeting'] : ''; // กำหนดค่าเริ่มต้นเป็นค่าว่าง
  $manutitle = isset($_POST['manutitle']) ? $_POST['manutitle'] : ''; // กำหนดค่าเริ่มต้นเป็นค่าว่าง
  $booking_id = isset($_POST['booking_id']) ? $_POST['booking_id'] : ''; // กำหนดค่าเริ่มต้นเป็นค่าว่าง

  $stmt1 = $mysqli->prepare("INSERT INTO booking_t2 (date, timeslot, title, name, email, tel, meeting, manutitle, booking_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

  $stmt1->bind_param("sssssssss", $date, $timeslot, $title, $name, $email, $tel, $meeting, $manutitle, $booking_id);

  $result1 = $stmt1->execute();
  if ($result1) {
    echo '
      <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script>
      swal({
        title: " Off! Your booking has been off",
        text: "success",
        type: "success",
        timer: 1500,
        showConfirmButton: false
      }, function(){
        window.location = "index.php";
      });
    </script>';
    exit; // หยุดการทำงานของสคริปต์
  } else {
    $error_message = $stmt1->error; // รับข้อความข้อผิดพลาดจาก MySQL
    echo '<script>
      swal({
        title: "Fail",
        text: "Failed: ' . $error_message . '",
        type: "error",
        timer: 1500,
        showConfirmButton: false
      }, function(){
        window.location.href = "index.php";
      });
    </script>';
    exit; // หยุดการทำงานของสคริปต์
  }
  $mysqli->close();
}
?>

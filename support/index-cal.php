<?php
if (
    isset($_POST['id'])
    && isset($_POST['service'])
    && isset($_POST['note'])
    && isset($_POST['status_user'])
    && isset($_POST['dateCreate'])
) {
    require_once 'connect.php';
    $id = $_POST['id'];
    $service = $_POST['service'];
    $note = $_POST['note'];
    $status_user = $_POST['status_user'];
    $dateCreate = $_POST['dateCreate'];
    $stmt = $mysqli->prepare("UPDATE booking SET service=?, note=?, status_user=?, dateCreate=? WHERE id=?");
    $stmt->bind_param('ssssi', $service, $note, $status_user, $dateCreate, $id);
    $result = $stmt->execute();

    $stmt = $mysqli->prepare("SELECT * FROM booking WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    if ($row) {
        $id = $row['id'];
        $booking_id = $row['booking_id'];
        $date = $row['date'];
        $timeslot = $row['timeslot'];
        $title = $row['title'];
        $name = $row['name'];
        $email = $row['email'];
        $tel = $row['tel'];
        $meeting = $row['meeting'];
        $manutitle = $row['manutitle'];
        $status_user = $row['status_user'];
        $service = $row['service'];
        $note = $row['note'];
        $dateCreate = $row['dateCreate'];

        if ($result) {
            require_once "../phpmailer/PHPMailerAutoload.php";
            $mail = new PHPMailer;
            $mail->CharSet = "UTF-8";
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;

            $gmail_username = "research.nursingcmu@gmail.com";
            $gmail_password = "mutl idgt ihzl gcxi";

            $sender = "noreply@research support services booking";
            $email_sender = "research.nursingcmu@gmail.com";
            $email_receiver = $email;

            $subject = "Booking Id: $booking_id NRC: New Booking ($title)";

            $mail->Username = $gmail_username;
            $mail->Password = $gmail_password;
            $mail->setFrom($email_sender, $sender);
            $mail->addAddress($email_receiver);
            $mail->Subject = $subject;

            $email_content = "
      <!DOCTYPE html>
  <html>
      <head>
          <meta charset='utf-8'>
      </head>
      <body>
          <h1
              style='background: #FF6A00; padding: 10px 0 10px 10px; margin-bottom: 10px; font-size: 20px; color: white;'>
              <p>Research Support Services Booking</p>
          </h1>
          <div style='padding: 20px;'>
              <div style='margin-top: 10px;'>
                  <h3 style='font-size: 18px;'>Automatic e-mail booking
                      confirmation research support services booking</h3>
                  <h4 style='font-size: 16px; margin-top: 10px;'>Dear Khun $name</h4>
  
                  <p style='font-size: 16px; margin-top: 10px;'>The Nursing Research Center (NRC) online submission system regrets to inform you that the following booking has been cancelled:</p>
  
                  <p style='font-size: 16px; margin-top: 10px;'>
                      Booking Id : $booking_id<br>
                      Date : $date <br>
                      Time : $timeslot <br>
                      Service Type : $title <br>
                      Meeting Option : $meeting <br>
                      Status: canceled <br>
                      Please book a new date/time at https://app.nurse.cmu.ac.th/booking <br>
                  </p>
  
                  <p style='font-size: 16px; margin-top: 10px;'>
                  Thank you for using the NRC consultation service. Please do not hesitate to contact us with any questions or concerns.
                  </p>
                  <p style='font-size: 16px; margin-top: 10px;'>
                      Sincerely, <br>
                      Nursing Research Center (NRC)<br>
                      Faculty of Nursing, Chiang Mai University<br>
                      Should you have any queries, please contact us.<br>
                      Tel.: 053-935033
                  </p>
              </div>
  
              <div style='margin-top: 10px;'>
              </div>
          </div>
          <div style='background: #FF6A00; color: #ffffff; padding: 30px;'>
              <div style='text-align: center'>
                  2023 © Research Support Services Booking
              </div>
          </div>
      </body>
  </html>
  ";

            $mail->msgHTML($email_content);

            if (!$mail->send()) {
                echo "Email sending failed: " . $mail->ErrorInfo;
            } else {
                echo "Email sent successfully.";
            }
            $sToken = ["LN8KPFOSBCYWrDpZThezFPSo76uK0atqX8slQFbLJ2z"];
            $sMessage .= "";
            $sMessage .= "Booking Id: " . $row['booking_id'] . "\n";
            $sMessage .= "Date: " . $row['date'] . "\n";
            $sMessage .= "Time: " . $row['timeslot'] . "\n";
            $sMessage .= "Service Type: " . $row['title'] . "\n";
            $sMessage .= "\n";
            $sMessage .= "Booked by: " . $row['name'] . "\n";
            $sMessage .= "E-mail: " . $row['email'] . "\n";
            $sMessage .= "Tel: " . $row['tel'] . "\n";
            $sMessage .= "\n";
            $sMessage .= $row['meeting'] . ":" . $row['service'] . "\n";
            $sMessage .= "Note: " . $row['note'] . "\n";
            $sMessage .= "Update: " . $row['dateCreate'] . "\n";
            $sMessage .= "\n";
            $sMessage .= "login : " . "https://app.nurse.cmu.ac.th/booking" . "\n";


            function notify_message($sMessage, $Token)
            {
                $chOne = curl_init();
                curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
                curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($chOne, CURLOPT_POST, 1);
                curl_setopt($chOne, CURLOPT_POSTFIELDS, "message=" . $sMessage);
                $headers = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $Token . '',);
                curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($chOne);
                if (curl_error($chOne)) {
                    echo 'error:' . curl_error($chOne);
                }
                curl_close($chOne);
            }
            foreach ($sToken as $Token) {
                notify_message($sMessage, $Token);
            }
        }
    }


    $mysqli->close();

    echo '
        <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';

    if ($result) {
        echo '<script>
            swal({
              title: "Update Booking Success",
              text: "success",
              type: "success",
              timer: 1500,
              showConfirmButton: false
            }, function(){
              window.location = "index.php";
            });
          </script>';
    } else {
        echo '<script>
            swal({
              title: "Update Booking Fail",
              text: "fail",
              type: "fail",
              timer: 1500,
              showConfirmButton: false
            }, function(){
              window.location.href = "index.php";
            });
          </script>';
    }
}

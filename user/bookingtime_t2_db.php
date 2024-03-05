<?php
if (
    isset($_POST['date']) &&
    isset($_POST['timeslot']) &&
    isset($_POST['title']) &&
    isset($_POST['name']) &&
    isset($_POST['email']) &&
    isset($_POST['tel']) &&
    isset($_POST['meeting']) &&
    isset($_POST['manutitle']) &&
    isset($_POST['booking_id']) &&
    isset($_POST['status_user']) &&
    isset($_POST['service']) &&
    isset($_POST['note'])
) {
    require_once 'connect.php';

    $date = $_POST['date'];
    $timeslot = $_POST['timeslot'];
    $title = $_POST['title'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $meeting = $_POST['meeting'];
    $manutitle = $_POST['manutitle'];
    $booking_id = $_POST['booking_id'];
    $status_user = $_POST['status_user'];
    $service = $_POST['service'];
    $note = $_POST['note'];

    $stmt1 = $mysqli->prepare("INSERT INTO booking_t2 (date, timeslot, title, name, email, tel, meeting, manutitle, booking_id, status_user, service, note)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt1->bind_param("ssssssssssss", $date, $timeslot, $title, $name, $email, $tel, $meeting, $manutitle, $booking_id, $status_user, $service, $note);

    $stmt2 = $mysqli->prepare("INSERT INTO booking (date, timeslot, title, name, email, tel, meeting, manutitle, booking_id, status_user, service, note)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt2->bind_param("ssssssssssss", $date, $timeslot, $title, $name, $email, $tel, $meeting, $manutitle, $booking_id, $status_user, $service, $note);
    $stmt2->execute();

    $result1 = $stmt1->execute();

    if ($result1) {
        // ส่วนของการส่งอีเมลล์
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

                <p style='font-size: 16px; margin-top: 10px;'>The Nursing
                    Research Center (NRC) online submission system has received
                    a submission for booking for Research Consult Service the
                    following Booking:-</p>

                <p style='font-size: 16px; margin-top: 10px;'>
                    Booking Id : $booking_id<br>
                    Date : $date <br>
                    Time : $timeslot <br>
                    Service Type : $title <br>
                    Meeting Option : $meeting <br>
                </p>

                <p style='font-size: 16px; margin-top: 10px;'>
                    Please wait for
                    confirmation from the research consultant or the English
                    editor within 24 hours.<br>
                    Thank you for using the NRC consultation service. Please do
                    not hesitate to contact us with any questions or concerns.
                </p>
                <p style='font-size: 16px; margin-top: 10px;'>
                    Sincerely, <br>
                    Nursing Research Center (NRC)<br>
                    Faculty of Nursing, Chiang Mai University<br>
                    Should you have any queries, please contact us.
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

        // ส่วนของการแจ้งเตือนผ่าน LINE LN8KPFOSBCYWrDpZThezFPSo76uK0atqX8slQFbLJ2z
        $sToken = ["T6pYBZljHjLz1fTbMJoSv8uiPEwSj7FRxu8QTcegFmU","LN8KPFOSBCYWrDpZThezFPSo76uK0atqX8slQFbLJ2z"];
        $sMessage = "New Booking !\r\n";
        $sMessage .= "\n";
        $sMessage .= "Booking Id: " . $booking_id . "\n";
        $sMessage .= "Date: " . $date . "\n";
        $sMessage .= "Time: " . $timeslot . "\n";
        $sMessage .= "Service Type: " . $title . "\n";
        $sMessage .= "Meeting Option: " . $meeting . "\n";
        $sMessage .= "\n";
        $sMessage .= "Booked by: " . $name . "\n";
        $sMessage .= "E-mail: " . $email . "\n";
        $sMessage .= "Tel: " . $tel . "\n";
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
        echo '
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
        echo '<script>
    swal({
      title: "Booking Success",
      text: "Pending",
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
      title: "Booking Fail",
      text: "Pending",
      type: "error",
      timer: 1500,
      showConfirmButton: false
    }, function(){
      window.location.href = "index.php";
    });
  </script>';
    }

    $stmt1->close();
    $mysqli->close();
}

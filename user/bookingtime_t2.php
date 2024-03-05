<?php
session_start();
if (!isset($_SESSION['login_info'])) {
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['login_info'])) {
    $json = $_SESSION['login_info'];
} else {
    echo "You are not logged in.";
}
?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">

<?php require_once 'head.php'; ?>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php require_once 'aside.php'; ?>
            <div class="layout-page">
                <?php require_once 'nav.php'; ?>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="col-lg-12 mb-4 order-0">
                                <div class="card">
                                    <div class="d-flex align-items-end row">
                                        <div class="col-sm-12">
                                            <div class="card-body">
                                                <?php
                                                require_once 'connect.php';
                                                if (isset($_GET['date'])) {
                                                    $title = $_GET['title'];
                                                    $date = $_GET['date'];
                                                    $stmt = $mysqli->prepare("select * from booking_t2 where date = ?");
                                                    $stmt->bind_param('s', $date);
                                                    $bookings = array();
                                                    if ($stmt->execute()) {
                                                        $result = $stmt->get_result();
                                                        if ($result->num_rows > 0) {
                                                            while ($row = $result->fetch_assoc()) {
                                                                $bookings[] = $row['timeslot'];
                                                            }

                                                            $stmt->close();
                                                        }
                                                    }
                                                }
                                                $duration = 60;
                                                $cleanup = 0;
                                                $start = "13:00";
                                                $end = "16:00";

                                                function timeslots($duration, $cleanup, $start, $end)
                                                {
                                                    $start = new DateTime("$start");
                                                    $end = new DateTime("$end");
                                                    $interval = new DateInterval("PT" . $duration . "M");
                                                    $cleanupInterval = new DateInterval("PT" . $cleanup . "M");
                                                    $slots = array();
                                                    for ($intStart = $start; $intStart < $end; $intStart->add($interval)->add($cleanupInterval)) {
                                                        $endPeriod = clone $intStart;
                                                        $endPeriod->add($interval);
                                                        if ($endPeriod > $end) {
                                                            break;
                                                        }
                                                        $slots[] = $intStart->format("H:iA") . "_" . $endPeriod->format("H:iA");
                                                    }
                                                    return $slots;
                                                }
                                                ?>
                                                <div class="row">
                                                    <div class="col-md-12 col-12 mt-2">
                                                        <?php echo isset($msg) ? $msg : ""; ?>
                                                    </div>
                                                    <?php $timeslots = timeslots($duration, $cleanup, $start, $end,);
                                                    foreach ($timeslots as $ts) {
                                                    ?>
                                                        <div class="col-md-2 col-12 mt-2">
                                                            <div class="form-group"></div>
                                                            <?php if (in_array($ts, $bookings)) { ?>
                                                                <button class="col-md-12  col-12 btn btn-secondary"><?php echo $ts; ?></button><br>
                                                            <?php } else { ?>
                                                                <button class="col-md-12 col-12 btn btn-primary book" data-bs-toggle="modal" data-bs-target="#exLargeModal" data-timeslot="<?php echo $ts; ?>"><?php echo $ts; ?></button>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="exLargeModal" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-xl" title="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel4">Booking</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form method="POST" id="bookingForm" onsubmit="return disableSaveButton()">
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                        <label for="date" class="form-label">Date</label>
                                                                        <div class="input-group input-group-merge">
                                                                            <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-calendar"></i></span>
                                                                            <input type="text" name="date" id="date" class="form-control" value="<?= $date; ?>" readonly />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                        <label for="timeslot" class="form-label">Time</label>
                                                                        <div class="input-group input-group-merge">
                                                                            <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-time"></i></span>
                                                                            <input type="text" name="timeslot" id="timeslot" class="form-control" value="<?= $timeslot; ?>" readonly />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                        <label for="title" class="form-label">Service</label>
                                                                        <div class="input-group input-group-merge">
                                                                            <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-purchase-tag-alt"></i></span>
                                                                            <input type="text" name="title" id="title" class="form-control" value="<?= $title; ?>" readonly />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                        <label for="name" class="form-label">FullName</label>
                                                                        <div class="input-group input-group-merge">
                                                                            <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-user"></i></span>
                                                                            <input type="text" name="name" id="name" class="form-control" value="<?php echo $json['firstname_EN'] . ' ' . $json['lastname_EN']; ?>" readonly />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                        <label for="email" class="form-label">Email</label>
                                                                        <div class="input-group input-group-merge">
                                                                            <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-envelope"></i></span>
                                                                            <input type="text" name="email" id="email" class="form-control" value="<?php echo $json['cmuitaccount']; ?>" readonly />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                        <label for="tel" class="form-label">Mobile Number</label>
                                                                        <div class="input-group input-group-merge">
                                                                            <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-phone"></i></span>
                                                                            <input type="text" name="tel" id="tel" class="form-control" value="" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-6 col-12 mb-2">
                                                                        <label for="meeting" class="form-label">Meeting Option</label>
                                                                        <div class="input-group input-group-merge">
                                                                            <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-down-arrow-alt"></i></span>
                                                                            <select class="form-select" name="meeting" required id="meeting" aria-label="Default select example">
                                                                                <option value="" disabled selected>Choose an option</option>
                                                                                <option value="Zoom meeting">Zoom meeting</option>
                                                                                <option value="Face to face meeting">Face to face meeting</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-6 col-12 mb-2">
                                                                        <label class="form-label" for="basic-icon-default-message">Manuscript Title</label>
                                                                        <div class="input-group input-group-merge">
                                                                            <span id="basic-icon-default-message2" class="input-group-text"><i class="bx bx-comment"></i></span>
                                                                            <textarea id="manutitle" name="manutitle" class="form-control" placeholder="Could you please provide a concise summary of your manuscript? Let me know what assistance is needed to this." aria-describedby="basic-icon-default-message2" required></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" name="booking_id" value="<?php echo 'P-' . date('is') . '-' . (date('Y')); ?>" hidden>
                                                                    <input type="text" name="status_user" value="0" hidden>
                                                                    <input type="text" name="service" value="" hidden>
                                                                    <input type="text" name="note" value="" hidden>

                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                                            Close
                                                                        </button>
                                                                        <button type="submit" class="btn btn-primary" id="saveButton">Save changes</button>
                                                                        <script>
                                                                            function disableSaveButton() {
                                                                                // ปิดการใช้งานปุ่ม Save changes
                                                                                document.getElementById("saveButton").innerHTML = "Please wait...";
                                                                                document.getElementById("saveButton").disabled = true;

                                                                                // รอให้การส่งคำขอเสร็จสิ้นก่อนที่จะเปิดใช้งานปุ่มอีกครั้ง
                                                                                setTimeout(function() {
                                                                                    document.getElementById("saveButton").innerHTML = "Save changes";
                                                                                    document.getElementById("saveButton").disabled = false;
                                                                                }, 5000); // รอ 5 วินาที (ปรับตามความเหมาะสม)

                                                                                return true; // อนุญาตให้ฟอร์มส่งคำขอ
                                                                            }
                                                                        </script>

                                                                    </div>
                                                                    <input type="hidden" name="status_user" id="status_user" class="form-control" value="pending" readonly />

                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
                                                    <script>
                                                        $(document).ready(function() {
                                                            $(".book").click(function() {
                                                                var timeslot = $(this).data('timeslot');
                                                                var urlParams = new URLSearchParams(window.location.search);
                                                                var date = urlParams.get('date');
                                                                $("#timeslot").val(timeslot);
                                                                $("#date").val(date);
                                                                $("#exLargeModal").modal('show');
                                                            });
                                                        });
                                                    </script>

                                                </div>
                                            </div>
                                            <?php
                                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                                require_once 'bookingtime_t2_db.php';
                                                // echo '<pre>';
                                                // print_r($_POST);
                                                // echo '</pre>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php require_once 'footer.php'; ?>
                            <div class="content-backdrop fade"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layout-overlay layout-menu-toggle"></div>
        </div>
        <script src="../assets/vendor/libs/jquery/jquery.js"></script>
        <script src="../assets/vendor/libs/popper/popper.js"></script>
        <script src="../assets/vendor/js/bootstrap.js"></script>
        <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

        <script src="../assets/vendor/js/menu.js"></script>
        <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

        <script src="../assets/js/main.js"></script>

        <script src="../assets/js/dashboards-analytics.js"></script>

        <!-- Place this tag in your head or just before your close body tag. -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
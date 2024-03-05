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
                                            <style>
                                                    @media only screen and (max-width: 760px),
                                                    (min-device-width: 802px) and (max-device-width: 1020px) {

                                                        /* Force table to not be like tables anymore */
                                                        table,
                                                        thead,
                                                        tbody,
                                                        th,
                                                        td,
                                                        tr {
                                                            display: block;

                                                        }

                                                        .empty {
                                                            display: none;
                                                        }

                                                        th {
                                                            position: absolute;
                                                            top: -9999px;
                                                            left: -9999px;
                                                        }

                                                        tr {
                                                            border: 1px solid #ccc;
                                                        }

                                                        td {
                                                            border: none;
                                                            border-bottom: 1px solid #eee;
                                                            position: relative;
                                                            padding-left: 50%;
                                                        }

                                                        td:nth-of-type(0):before {
                                                            content: "Sunday";
                                                        }

                                                        td:nth-of-type(1):before {
                                                            content: "Monday";
                                                        }

                                                        td:nth-of-type(2):before {
                                                            content: "Tuesday";
                                                        }

                                                        td:nth-of-type(3):before {
                                                            content: "Wednesday";
                                                        }

                                                        td:nth-of-type(4):before {
                                                            content: "Thursday";
                                                        }

                                                        td:nth-of-type(5):before {
                                                            content: "Friday";
                                                        }

                                                        td:nth-of-type(6):before {
                                                            content: "Saturday";
                                                        }
                                                    }
                                                </style>
                                                <?php
                                                $dateComponents = getdate();
                                                if (isset($_GET['month']) && isset($_GET['year'])) {
                                                    $month = $_GET['month'];
                                                    $year = $_GET['year'];
                                                } else {
                                                    $month = $dateComponents['mon'];
                                                    $year = $dateComponents['year'];
                                                }
                                                $day = isset($_GET['day']) ? $_GET['day'] : $dateComponents['mday'];
                                                echo build_calendar($month, $year, $day);

                                                function build_calendar($month, $year, $day)
                                                {
                                                    require_once 'connect.php';
                                                    $daysOfWeek = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
                                                    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
                                                    $numberDays = date('t', $firstDayOfMonth);
                                                    $dateComponents = getdate($firstDayOfMonth);
                                                    $monthName = utf8_encode($dateComponents['month']);
                                                    $dayOfWeek = $dateComponents['wday'];

                                                    if ($dayOfWeek == 0) {
                                                        $dayOfWeek = 6;
                                                    } else {
                                                        $dayOfWeek = $dayOfWeek - 1;
                                                    }

                                                    $datetoday = date('Y-m-d');
                                                    $calendar = "<table class='table table-bordered'>";
                                                    $calendar .= "<center><h2>$monthName $year</h2>";
                                                    $calendar .= "<a class='btn btn-primary' href='?month=" . date('m', mktime(0, 0, 0, $month - 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, $month - 1, 1, $year)) . "&title=" . urlencode($_GET['title']) . "'>Previous Month</a> ";
                                                    $calendar .= " <a class='btn btn-primary' href='?month=" . date('m') . "&year=" . date('Y') . "&title=" . urlencode($_GET['title']) . "'>Current Month</a> ";
                                                    $calendar .= "<a class='btn btn-primary' href='?month=" . date('m', mktime(0, 0, 0, $month + 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, $month + 1, 1, $year)) . "&title=" . urlencode($_GET['title']) . "'>Next Month</a></center><br>";

                                                    $calendar .= "<tr>";
                                                    foreach ($daysOfWeek as $day) {
                                                        $calendar .= "<th class='header'>$day</th>";
                                                    }
                                                    $currentDayLoop = 1;
                                                    $calendar .= "</tr><tr>";
                                                    if ($dayOfWeek > 0) {
                                                        for ($k = 0; $k < $dayOfWeek; $k++) {
                                                            $calendar .= "<td class='empty'></td>";
                                                        }
                                                    }

                                                    $month = str_pad($month, 2, "0", STR_PAD_LEFT);

                                                    while ($currentDayLoop <= $numberDays) {
                                                        if ($dayOfWeek == 7) {
                                                            $dayOfWeek = 0;
                                                            $calendar .= "</tr><tr>";
                                                        }

                                                        $currentDayRel = str_pad($currentDayLoop, 2, "0", STR_PAD_LEFT);
                                                        $date = "$year-$month-$currentDayRel";

                                                        $dayname = strtolower(date('l', strtotime($date)));
                                                        $eventNum = 0;
                                                        $today = $date == date('Y-m-d') ? "today" : "";
                                                        $todayStyle = $today ? "background-color: #dee0e6;" : "";

                                                        if ($dayname == 'sunday' || $dayname == 'monday' || $dayname == 'tuesday' || $dayname == 'friday' || $dayname == 'saturday') {
                                                            $calendar .= "<td style='$todayStyle'><h4>$currentDayLoop</h4> <button class='col-12 btn btn-secondary'>Unavailable</button>";
                                                        } elseif ($date < date('Y-m-d')) {
                                                            $calendar .= "<td style='$todayStyle'><h4>$currentDayLoop</h4> <button class='col-12 btn btn-warning'>Pass</button>";
                                                        } else {
                                                            $totalbookings = checkSlots($mysqli, $date);
                                                            if ($totalbookings == 6) {
                                                                $calendar .= "<td class='$today' style='$todayStyle'><h4>$currentDayLoop</h4> <a href='#' class='btn btn-primary'>Booking Full</a>";
                                                            } else {
                                                                $availableslots = 6 - $totalbookings;
                                                                $titleParam = isset($_GET['title']) ? "&title=" . urlencode($_GET['title']) : "";
                                                                $bookingLink = "bookingtime_t1.php?date=" . $date . $titleParam;
                                                                $calendar .= "<td class='$today' style='$todayStyle'><h4>$currentDayLoop</h4><a> $availableslots slots</a><a href='$bookingLink' class='col-12 btn btn-success'>Available times</a>";
                                                            }
                                                        }

                                                        $calendar .= "</td>";
                                                        $currentDayLoop++;
                                                        $dayOfWeek++;
                                                    }

                                                    if ($dayOfWeek != 7) {
                                                        $remainingDays = 7 - $dayOfWeek;
                                                        for ($l = 0; $l < $remainingDays; $l++) {
                                                            $calendar .= "<td class='empty'></td>";
                                                        }
                                                    }

                                                    $calendar .= "</tr>";
                                                    $calendar .= "</table>";

                                                    echo $calendar;
                                                }

                                                function checkSlots($mysqli, $date)
                                                {
                                                    $stmt = $mysqli->prepare("select * from booking_t1 where date = ?");
                                                    $stmt->bind_param('s', $date);
                                                    $totalbookings = 0;
                                                    if ($stmt->execute()) {
                                                        $result = $stmt->get_result();
                                                        if ($result->num_rows > 0) {
                                                            while ($row = $result->fetch_assoc()) {
                                                                $totalbookings++;
                                                            }
                                                            $stmt->close();
                                                        }
                                                    }
                                                    return $totalbookings;
                                                }
                                                ?>
                                            </div>
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


        <!-- Core JS -->
        <!-- build:js assets/vendor/js/core.js -->
        <script src="../assets/vendor/libs/jquery/jquery.js"></script>
        <script src="../assets/vendor/libs/popper/popper.js"></script>
        <script src="../assets/vendor/js/bootstrap.js"></script>
        <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

        <script src="../assets/vendor/js/menu.js"></script>
        <!-- endbuild -->

        <!-- Vendors JS -->
        <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

        <!-- Main JS -->
        <script src="../assets/js/main.js"></script>

        <!-- Page JS -->
        <script src="../assets/js/dashboards-analytics.js"></script>

        <!-- Place this tag in your head or just before your close body tag. -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
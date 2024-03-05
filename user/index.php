<?php
session_cache_limiter("nocache");
session_start();
if (!isset($_SESSION['login_info'])) {
    header('Location: login.php');
    exit;
}

require_once 'connect.php';

if (isset($_SESSION['login_info'])) {
    $json = $_SESSION['login_info'];
    $cmuitaccount = $json['cmuitaccount'];
    $insertStmt = $mysqli->prepare("INSERT INTO log_user (cmuitaccount, login_time) VALUES (?, NOW())");
    $insertStmt->bind_param("s", $cmuitaccount);
    $insertStmt->execute();
    $insertStmt->close();
    $stmt = $mysqli->prepare("SELECT * FROM cmuitaccount WHERE cmuitaccount = ?");
    $stmt->bind_param("s", $cmuitaccount);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        header("Location: https://app.nurse.cmu.ac.th/booking/support/index.php");
        exit;
    }
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
                                                <div style="text-align: center;">
                                                    <!-- <h4>system is currently being updated</h4> -->
                                                    <h4>Choose a service that you need and schedule an appointment time.</h4>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-4 mb-4 order-0">
                                                        <div class="card">
                                                            <div class="d-flex align-items-end row">
                                                                <div class="col-sm-12">
                                                                    <div class="card-body">
                                                                        <a href="calendar_t1.php?title=Editor English Hours" class="btn btn-secondary col-12">Editor English Hours <br>(Mr. Michael Cote)</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 mb-4 order-0">
                                                        <div class="card">
                                                            <div class="d-flex align-items-end row">
                                                                <div class="col-sm-12">
                                                                    <div class="card-body">
                                                                        <a href="calendar_t2.php?title=Research Consult" class="btn btn-secondary col-12">Research Consult <br>(Dr.Patompong Khaw-on)</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 mb-4 order-0">
                                                        <div class="card">
                                                            <div class="d-flex align-items-end row">
                                                                <div class="col-sm-12">
                                                                    <div class="card-body">
                                                                        <a href="calendar_t2.php?title=Statistic Consult" class="btn btn-secondary col-12">Statistic Consult <br>(Dr.Patompong Khaw-on)</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

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
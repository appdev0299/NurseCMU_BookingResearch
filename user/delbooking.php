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
                            <?php
                            if (isset($_GET['booking_id'])) {
                                $cancel_id = $_GET['booking_id'];
                                require_once 'connect.php';

                                $delete_t2 = $mysqli->prepare("DELETE FROM booking_t2 WHERE booking_id = ?");
                                $delete_t2->bind_param('s', $cancel_id);
                                $delete_t2_success = $delete_t2->execute();

                                $delete_t1 = $mysqli->prepare("DELETE FROM booking_t1 WHERE booking_id = ?");
                                $delete_t1->bind_param('s', $cancel_id);
                                $delete_t1_success = $delete_t1->execute();

                                $delete_all = $mysqli->prepare("DELETE FROM booking WHERE booking_id = ?");
                                $delete_all->bind_param('s', $cancel_id);
                                $delete_booking_success = $delete_all->execute();

                                if ($delete_booking_success && $delete_t1_success && $delete_t2_success) {
                                    echo '
                                        <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
                                        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
                                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
                                    echo '<script>
                                            setTimeout(function() {
                                            swal({
                                                title: "Cancel Booking Success",
                                                type: "success",
                                                timer: 2000,
                                                showConfirmButton: false
                                            });
                                            setTimeout(function() {
                                                window.location = "viewdata.php";
                                            }, 1500);
                                            }, 1000);
                                        </script>';
                                } else {
                                    echo '<script>
                                            setTimeout(function() {
                                            swal({
                                                title: "Cancel Booking Error",
                                                type: "error",
                                                timer: 2000,
                                                showConfirmButton: false
                                            });
                                            setTimeout(function() {
                                                window.location = "viewdata.php";
                                            }, 1500);
                                            }, 1000);
                                        </script>';
                                }
                                $mysqli->close();
                            }
                            ?>
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
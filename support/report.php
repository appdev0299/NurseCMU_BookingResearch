<?php
session_start();
if (!isset($_SESSION['login_info'])) {
    header('Location: ../user/login.php');
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
                            <div class="col-lg-12 mb-12 order-0">
                                <div class="card">
                                    <div class="d-flex align-items-end row">
                                        <div class="col-sm-12">
                                            <div class="card-body">
                                                <form method="post" enctype="multipart/form-data" id="your_form_id">
                                                    <div class="row">
                                                        <div class="form-group col-lg-6 col-md-3 col-6">
                                                            <div class="form-group">
                                                                <label for="start_date" class="control-label mb-1">Start</label>
                                                                <input type="date" name="start_date" class="form-control" id="start_date" value="<?php echo isset($_POST['start_date']) ? htmlspecialchars($_POST['start_date']) : ''; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-6 col-md-3 col-6">
                                                            <div class="form-group">
                                                                <label for="end_date" class="control-label mb-1">End</label>
                                                                <input type="date" name="end_date" class="form-control" id="end_date" value="<?php echo isset($_POST['end_date']) ? htmlspecialchars($_POST['end_date']) : ''; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-lg-6 col-md-3 col-6">
                                                            <div class="form-group">
                                                                <label for="title" class="control-label mb-1">Service</label>
                                                                <select class="form-control" name="title" id="title">
                                                                    <option value="" disabled <?= empty($_POST['title']) ? 'selected' : ''; ?>>ShowAll</option>
                                                                    <?php
                                                                    require_once 'connect.php';
                                                                    $sql = "SELECT DISTINCT title FROM booking";
                                                                    $result = $mysqli->query($sql);

                                                                    if ($result) {
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            $title = $row['title'];
                                                                            $selected = isset($_POST['title']) && $_POST['title'] === $title ? 'selected' : '';
                                                                            echo "<option value='$title' $selected>$title</option>";
                                                                        }
                                                                        $result->free(); // ปิด result set
                                                                    } else {
                                                                        echo "Error: " . $mysqli->error;
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-6 col-md-3 col-6">
                                                            <div class="form-group">
                                                                <label for="status_user" class="control-label mb-1">Status</label>
                                                                <select class="form-control" name="status_user" id="status_user">
                                                                    <option value="" disabled <?= empty($_POST['status_user']) ? 'selected' : ''; ?>>ShowAll</option>
                                                                    <?php
                                                                    $sql = "SELECT DISTINCT status_user FROM booking";
                                                                    $result = $mysqli->query($sql);

                                                                    if ($result) {
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            $status_user = $row['status_user'];
                                                                            $selected = isset($_POST['status_user']) && $_POST['status_user'] === $status_user ? 'selected' : '';
                                                                            echo "<option value='$status_user' $selected>$status_user</option>";
                                                                        }
                                                                        $result->free(); // ปิด result set
                                                                    } else {
                                                                        echo "Error: " . $mysqli->error;
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        &nbsp;
                                                        <hr>
                                                        <div class="form-group col-lg-6 col-md-3 col-6">
                                                            <div class="form-group">
                                                                <button type="submit" name="display_data" class="btn btn-primary">Submit</button>
                                                                &nbsp;
                                                                <button type="button" id="export_data" class="btn btn-success">Export</button>
                                                                &nbsp;
                                                                <button type="button" id="clear_data" class="btn btn-danger">Clear</button>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            document.addEventListener("DOMContentLoaded", function() {
                                                                var clearButton = document.getElementById("clear_data");
                                                                clearButton.addEventListener("click", function() {
                                                                    var form = document.getElementById("your_form_id");
                                                                    var elements = form.elements;

                                                                    for (var i = 0; i < elements.length; i++) {
                                                                        if (elements[i].type === "text" || elements[i].type === "textarea" || elements[i].type === "select-one") {
                                                                            elements[i].value = ""; // ล้างค่าข้อมูลในฟิลด์
                                                                        }
                                                                    }
                                                                });
                                                            });
                                                        </script>
                                                        <script>
                                                            document.getElementById("export_data").addEventListener("click", function() {
                                                                var start_date = document.getElementById("start_date").value;
                                                                var end_date = document.getElementById("end_date").value;
                                                                var status_user = document.getElementById("status_user").value;
                                                                var title = document.getElementById("title").value;
                                                                var url = "report_db.php?";
                                                                if (start_date) {
                                                                    url += "start_date=" + encodeURIComponent(start_date) + "&";
                                                                }
                                                                if (end_date) {
                                                                    url += "end_date=" + encodeURIComponent(end_date) + "&";
                                                                }
                                                                if (status_user) {
                                                                    url += "status_user=" + encodeURIComponent(status_user) + "&";
                                                                }
                                                                if (title) {
                                                                    url += "title=" + encodeURIComponent(title) + "&";
                                                                }
                                                                window.location.href = url;
                                                            });
                                                        </script>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <?php
                                                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                        if (isset($_POST['display_data'])) {
                                                            require_once 'connect.php';

                                                            $sql = "SELECT * FROM booking WHERE 1=1";

                                                            if (isset($_POST['start_date']) && !empty($_POST['start_date']) && isset($_POST['end_date']) && !empty($_POST['end_date'])) {
                                                                $start_date = $_POST['start_date'];
                                                                $end_date = $_POST['end_date'];
                                                                $sql .= " AND date BETWEEN ? AND ?";
                                                            }

                                                            if (isset($_POST['title']) && !empty($_POST['title'])) {
                                                                $selected_title = $_POST['title'];
                                                                $sql .= " AND title = ?";
                                                            }

                                                            if (isset($_POST['status_user']) && !empty($_POST['status_user'])) {
                                                                $selected_status_user = $_POST['status_user'];
                                                                $sql .= " AND status_user = ?";
                                                            }

                                                            $sql .= " ORDER BY id DESC";

                                                            $stmt = $mysqli->prepare($sql);

                                                            if ($stmt) {
                                                                if (isset($start_date) && isset($end_date) && isset($selected_title) && isset($selected_status_user)) {
                                                                    $stmt->bind_param('ssss', $start_date, $end_date, $selected_title, $selected_status_user);
                                                                } else if (isset($start_date) && isset($end_date) && isset($selected_title)) {
                                                                    $stmt->bind_param('sss', $start_date, $end_date, $selected_title);
                                                                } else if (isset($start_date) && isset($end_date) && isset($selected_status_user)) {
                                                                    $stmt->bind_param('sss', $start_date, $end_date, $selected_status_user);
                                                                } else if (isset($selected_title) && isset($selected_status_user)) {
                                                                    $stmt->bind_param('ss', $selected_title, $selected_status_user);
                                                                } else if (isset($start_date) && isset($end_date)) {
                                                                    $stmt->bind_param('ss', $start_date, $end_date);
                                                                } else if (isset($selected_title)) {
                                                                    $stmt->bind_param('s', $selected_title);
                                                                } else if (isset($selected_status_user)) {
                                                                    $stmt->bind_param('s', $selected_status_user);
                                                                }

                                                                $stmt->execute();
                                                                $result = $stmt->get_result(); // ดึงผลลัพธ์เป็น result set
                                                                $results = $result->fetch_all(MYSQLI_ASSOC); // ดึงข้อมูลและแปลงเป็นอาร์เรย์แบบแอสโซซิเอชั่น
                                                                $stmt->close();
                                                            } else {
                                                                echo "Error in prepare statement: " . $mysqli->error;
                                                                $results = array(); // กำหนดให้เป็น array เริ่มต้นเพื่อป้องกันข้อผิดพลาดนี้
                                                            }

                                                    ?>
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Booking</th>
                                                                        <th>Date</th>
                                                                        <th>Times</th>
                                                                        <th>Title</th>
                                                                        <th>Name</th>
                                                                        <th>Manutitle</th>
                                                                        <th>Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="table-border-bottom-0">
                                                                    <?php $i = 1; ?>
                                                                    <?php foreach ($results as $row) : ?>
                                                                        <tr>
                                                                            <td><?php echo $i++; ?></td>
                                                                            <td><?php echo $row['booking_id']; ?></td>
                                                                            <td><?php echo $row['date']; ?></td>
                                                                            <td><?php echo $row['timeslot']; ?></td>
                                                                            <td><?php echo $row['title']; ?></td>
                                                                            <td><?php echo $row['name']; ?></td>
                                                                            <td><?php echo $row['manutitle']; ?></td>
                                                                            <td>
                                                                                <?php
                                                                                $status_user = $row['status_user'];
                                                                                $class = '';

                                                                                if ($status_user === 'pending') {
                                                                                    $class = 'badge bg-label-secondary me-1';
                                                                                } elseif ($status_user === 'confirmed') {
                                                                                    $class = 'badge bg-label-success me-1';
                                                                                } elseif ($status_user === 'cancel') {
                                                                                    $class = 'badge bg-label-danger me-1';
                                                                                }

                                                                                echo "<span class='$class'>$status_user</span>";
                                                                                ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                    <?php
                                                        } else {
                                                            echo "No data found.";
                                                        }
                                                    }
                                                    ?>
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
<?php
// session_start();
// if (!isset($_SESSION['login_info'])) {
//     header('Location: ../user/login.php');
//     exit;
// }
// require_once 'connect.php';

// $json = $_SESSION['login_info'];
// $email = $json['cmuitaccount'];

// $sql = "SELECT COUNT(*) AS count FROM cmuitaccount WHERE cmuitaccount = ?";
// $stmt = $mysqli->prepare($sql);
// $stmt->bind_param("s", $email);
// $stmt->execute();
// $result = $stmt->get_result();
// $row = $result->fetch_assoc();
// $count = $row['count'];
// if ($count === 0) {
//     header('Location: ../user/login.php');
//     exit;
// }

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
                            <div class="col-md-12 col-lg-4 col-xl-12 order-0 mb-4">
                                <div class="card h-100">
                                    <?php
                                    require_once 'connect.php';
                                    $itemsPerPage = 9;

                                    $searchTitle1 = "Editor English Hours";
                                    $searchTitle2 = "Research Consult";
                                    $searchTitle3 = "Statistic Consult";

                                    // คำนวณจำนวนข้อมูลทั้งหมด
                                    $stmtCount = $mysqli->prepare("SELECT COUNT(*) FROM booking WHERE title = ? OR title = ? OR title = ?");
                                    $stmtCount->bind_param("sss", $searchTitle1, $searchTitle2, $searchTitle3);
                                    $stmtCount->execute();
                                    $resultCount = $stmtCount->get_result();
                                    $totalItems = $resultCount->fetch_assoc()['COUNT(*)'];

                                    // คำนวณจำนวนหน้าทั้งหมด
                                    $totalPages = ceil($totalItems / $itemsPerPage);

                                    $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

                                    // คำนวณข้อมูลที่จะแสดงบนหน้าปัจจุบัน
                                    $offset = ($currentPage - 1) * $itemsPerPage;

                                    // ดึงข้อมูลจากฐานข้อมูลโดยใช้ LIMIT เพื่อแบ่งหน้า
                                    $stmt = $mysqli->prepare("SELECT * FROM booking WHERE title = ? OR title = ? OR title = ? ORDER BY date DESC LIMIT ?, ?");
                                    $stmt->bind_param("sssss", $searchTitle1, $searchTitle2, $searchTitle3, $offset, $itemsPerPage);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    ?>
                                    <div class="card-body">
                                        <div class="row">
                                            <?php foreach ($result as $t1) {
                                                $title = $t1['title'];
                                                $bgColor = '';

                                                if ($title == 'Editor English Hours') {
                                                    $bgColor = '#FFECEA';
                                                } else if ($title == 'Research Consult') {
                                                    $bgColor = '#FFF7E6';
                                                } else if ($title == 'Statistic Consult') {
                                                    $bgColor = '#FFF7E6';
                                                }

                                                $canCancel = $title != 'Editor English Hours' && $title != 'Research Consult' && $title != 'Statistic Consult';
                                            ?>

                                                <div class="col-md- col-lg-4 mb-3">
                                                    <div class="card h-100" style="background-color: <?php echo $bgColor; ?>">
                                                        <div class="card-body">
                                                            <p class="card-text">Booking id <?= $t1['booking_id']; ?></p>
                                                            <h5 class="card-title">
                                                                <?= strftime('%d %B %Y', strtotime($t1['date'])); ?> | <?= $t1['timeslot']; ?>
                                                            </h5>
                                                            <p class="card-text"><?= $t1['name']; ?> | <?= $t1['title']; ?></p>
                                                            <p class="card-text"><b><?= $t1['meeting']; ?> : <?= $t1['service']; ?></b></p>
                                                            <a class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#exLargeModalConfirmed<?= $t1['id']; ?>">Confirmed</a>
                                                            <a class="btn btn-danger text-white" data-bs-toggle="modal" data-bs-target="#exLargeModalCancel<?= $t1['id']; ?>">Cancel</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination">
                                                    <li class="page-item first">
                                                        <a class="page-link" href="?page=1"><i class="tf-icon bx bx-chevrons-left"></i></a>
                                                    </li>
                                                    <li class="page-item prev">
                                                        <a class="page-link" href="?page=<?php echo max(1, $currentPage - 1); ?>"><i class="tf-icon bx bx-chevron-left"></i></a>
                                                    </li>
                                                    <?php for ($page = 1; $page <= $totalPages; $page++) { ?>
                                                        <li class="page-item <?php if ($page == $currentPage) echo 'active'; ?>">
                                                            <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                                                        </li>
                                                    <?php } ?>
                                                    <li class="page-item next">
                                                        <a class="page-link" href="?page=<?php echo min($totalPages, $currentPage + 1); ?>"><i class="tf-icon bx bx-chevron-right"></i></a>
                                                    </li>
                                                    <li class="page-item last">
                                                        <a class="page-link" href="?page=<?php echo $totalPages; ?>"><i class="tf-icon bx bx-chevrons-right"></i></a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                    <?php foreach ($result as $t1) { ?>
                                        <div class="modal fade" id="exLargeModalConfirmed<?= $t1['id']; ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-xl" title="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel4"> Confirmed <?= $t1['booking_id']; ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                    <label for="timeslot" class="form-label">Date</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-calendar"></i></span>
                                                                        <input type="text" name="date" id="date" class="form-control" value="<?= $t1['date']; ?>" readonly />
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                    <label for="timeslot" class="form-label">Time</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-time"></i></span>
                                                                        <input type="text" name="timeslot" id="timeslot" class="form-control" value="<?= $t1['timeslot']; ?>" readonly />
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                    <label for="title" class="form-label">Service</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-purchase-tag-alt"></i></span>
                                                                        <input type="text" name="title" id="title" class="form-control" value="<?= $t1['title']; ?>" readonly />
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                    <label for="name" class="form-label">FullName</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-user"></i></span>
                                                                        <input type="text" name="name" id="name" class="form-control" value="<?= $t1['name']; ?>" readonly />
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                    <label for="email" class="form-label">Email</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-envelope"></i></span>
                                                                        <input type="text" name="email" id="email" class="form-control" value="<?= $t1['email']; ?>" readonly />
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                    <label for="meeting" class="form-label">meeting</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-navigation"></i></span>
                                                                        <input type="text" name="meeting" id="meeting" class="form-control" value="<?= $t1['meeting']; ?>" readonly />
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12 col-md-6 col-12 mb-2">
                                                                    <label for="tel" class="form-label">Mobile Number</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-phone"></i></span>
                                                                        <input type="text" name="tel" id="tel" class="form-control" value="<?= $t1['tel']; ?>" readonly />
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12 col-md-12 col-12 mb-2">
                                                                    <label class="form-label" for="basic-icon-default-message">Manuscript Title</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span id="basic-icon-default-message2" class="input-group-text"><i class="bx bx-comment"></i></span>
                                                                        <textarea id="manutitle" name="manutitle" class="form-control" placeholder="Hi" aria-describedby="basic-icon-default-message2" readonly><?= $t1['manutitle']; ?></textarea>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                    <label for="status_user" class="form-label">Status</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-down-arrow-alt"></i></span>
                                                                        <input type="text" name="status_user" id="status_user" class="form-control" value="Confirmed" readonly />
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-12 mb-2 service-section">
                                                                    <label for="service" class="form-label"><?= $t1['meeting']; ?></label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span class="input-group-text"><i class="bx bx-map-pin"></i></span>
                                                                        <input type="text" name="service" class="form-control" value="<?= $t1['service']; ?>" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12 col-md-12 col-12 mb-2 note-section">
                                                                    <label class="form-label" for="basic-icon-default-message">Note</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span class="input-group-text"><i class="bx bx-comment"></i></span>
                                                                        <textarea name="note" class="form-control" placeholder="Note"><?= $t1['note']; ?></textarea>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="id" value="<?= $t1['id']; ?>">
                                                                <input type="hidden" name="dateCreate" value="<?= date('Y-m-d H:i:s'); ?>">
                                                            </div>
                                                            <div class="col-lg-12 col-md-6 col-12 mb-2" style="display: none;">
                                                                <label for="header" class="form-label">header</label>
                                                                <div class="input-group input-group-merge">
                                                                    <span id="basic-icon-default-fullname2" class="input-group-text"></i></span>
                                                                    <input type="text" name="header" id="header" class="form-control" value="NRC: Confirmed Booking <?= $t1['booking_id']; ?> | <?= $t1['title']; ?>" />
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-12 col-md-6 col-12 mb-2" style="display: none;">
                                                                <label class="form-label" for="basic-icon-default-message">detail</label>
                                                                <div class="input-group input-group-merge">
                                                                    <span id="basic-icon-default-message2" class="input-group-text"></span>
                                                                    <textarea id="detail" name="detail" class="form-control">
                                                                            The Nursing Research Center (NRC) online submission system has confirmed the following booking:<br>
                                                                            <hr>
                                                                            Booking Id: <?= $t1['booking_id']; ?><br>
                                                                            Date: <?= $t1['date']; ?><br>
                                                                            Time: <?= $t1['timeslot']; ?><br>
                                                                            Service Type: <?= $t1['title']; ?><br>
                                                                            <?= $t1['meeting']; ?>: <?= $t1['service']; ?><br>
                                                                            <hr>
                                                                            Thank you for using the NRC consultation service. Please do not hesitate to contact us with any questions or concerns.<br>
                                                                            <br>
                                                                            Sincerely,<br>
                                                                            Nursing Research Center (NRC)<br>
                                                                            Faculty of Nursing, Chiang Mai University<br>
                                                                            Should you have any queries, please contact us.<br>
                                                                            Tel.: 053-935033<br>
                                                                        </textarea>
                                                                </div>
                                                            </div>
                                                            <script type="text/javascript">
                                                                function sendEmail() {
                                                                    var name = $("#name");
                                                                    var email = $("#email");
                                                                    var header = $("#header");
                                                                    var detail = $("#detail");

                                                                    if (isNotEmpty(name) && isNotEmpty(email) && isNotEmpty(header) && isNotEmpty(detail)) {
                                                                        $.ajax({
                                                                            url: 'sendEmail.php',
                                                                            method: 'POST',
                                                                            dataType: 'json',
                                                                            data: {
                                                                                name: name.val(),
                                                                                email: email.val(),
                                                                                header: header.val(),
                                                                                detail: detail.val()
                                                                            },
                                                                            success: function(response) {
                                                                                $('.msg').text("Message send successfully");
                                                                            }
                                                                        });
                                                                    }
                                                                }

                                                                function isNotEmpty(caller) {
                                                                    if (caller.val() == "") {
                                                                        caller.css('border', '1px solid red');
                                                                        return false;
                                                                    } else caller.css('border', '');

                                                                    return true;
                                                                }
                                                            </script>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                                Close
                                                            </button>
                                                            <button type="submit" class="btn btn-primary">Confirmed</button>
                                                        </div>
                                                    </form>
                                                    <?php
                                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                                        // require_once 'index-db.php';
                                                        // require_once 'sendEmail.php';
                                                        echo '<pre>';
                                                        print_r($_POST);
                                                        echo '</pre>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php foreach ($result as $t1) { ?>
                                        <div class="modal fade" id="exLargeModalCancel<?= $t1['id']; ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-xl" title="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel4"> Cancel <?= $t1['booking_id']; ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                    <label for="timeslot" class="form-label">Date</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-calendar"></i></span>
                                                                        <input type="text" name="date" id="date" class="form-control" value="<?= $t1['date']; ?>" readonly />
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                    <label for="timeslot" class="form-label">Time</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-time"></i></span>
                                                                        <input type="text" name="timeslot" id="timeslot" class="form-control" value="<?= $t1['timeslot']; ?>" readonly />
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                    <label for="title" class="form-label">Service</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-purchase-tag-alt"></i></span>
                                                                        <input type="text" name="title" id="title" class="form-control" value="<?= $t1['title']; ?>" readonly />
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                    <label for="name" class="form-label">FullName</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-user"></i></span>
                                                                        <input type="text" name="name" id="name" class="form-control" value="<?= $t1['name']; ?>" readonly />
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                    <label for="email" class="form-label">Email</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-envelope"></i></span>
                                                                        <input type="text" name="email" id="email" class="form-control" value="<?= $t1['email']; ?>" readonly />
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                    <label for="meeting" class="form-label">meeting</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-navigation"></i></span>
                                                                        <input type="text" name="meeting" id="meeting" class="form-control" value="<?= $t1['meeting']; ?>" readonly />
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12 col-md-6 col-12 mb-2">
                                                                    <label for="tel" class="form-label">Mobile Number</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-phone"></i></span>
                                                                        <input type="text" name="tel" id="tel" class="form-control" value="<?= $t1['tel']; ?>" readonly />
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12 col-md-12 col-12 mb-2">
                                                                    <label class="form-label" for="basic-icon-default-message">Manuscript Title</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span id="basic-icon-default-message2" class="input-group-text"><i class="bx bx-comment"></i></span>
                                                                        <textarea id="manutitle" name="manutitle" class="form-control" placeholder="Hi" aria-describedby="basic-icon-default-message2" readonly><?= $t1['manutitle']; ?></textarea>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <div class="col-lg-12 col-md-6 col-12 mb-2">
                                                                    <label for="status_user" class="form-label">Status</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-down-arrow-alt"></i></span>
                                                                        <input type="text" name="status_user" id="status_user" class="form-control" value="Cancel" readonly />
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12 col-md-12 col-12 mb-2 note-section">
                                                                    <label class="form-label" for="basic-icon-default-message">Note</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span class="input-group-text"><i class="bx bx-comment"></i></span>
                                                                        <textarea name="note" class="form-control" placeholder="Note"><?= $t1['note']; ?></textarea>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="id" value="<?= $t1['id']; ?>">
                                                                <input type="hidden" name="dateCreate" value="<?= date('Y-m-d H:i:s'); ?>">
                                                            </div>
                                                            <div class="col-lg-12 col-md-6 col-12 mb-2" style="display: none;">
                                                                <label for="header" class="form-label">header</label>
                                                                <div class="input-group input-group-merge">
                                                                    <span id="basic-icon-default-fullname2" class="input-group-text"></i></span>
                                                                    <input type="text" name="header" id="header" class="form-control" value="NRC: Canceled Booking <?= $t1['booking_id']; ?> | <?= $t1['title']; ?>" />
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="service" class="form-control" value="" />
                                                            <div class="col-lg-12 col-md-6 col-12 mb-2" style="display: none;">
                                                                <label class="form-label" for="basic-icon-default-message">detail</label>
                                                                <div class="input-group input-group-merge">
                                                                    <span id="basic-icon-default-message2" class="input-group-text"></span>
                                                                    <textarea id="detail" name="detail" class="form-control">
                                                                        The Nursing Research Center (NRC) online submission system regrets to inform you that the following booking has been cancelled:<br>
                                                                        <hr>
                                                                        Booking Id: <?= $t1['booking_id']; ?><br>
                                                                        Date: <?= $t1['date']; ?><br>
                                                                        Time: <?= $t1['timeslot']; ?><br>
                                                                        Service <?= $t1['title']; ?><br>
                                                                        Status: canceled<br>
                                                                        Please book a new date/time at https://app.nurse.cmu.ac.th/booking<br>
                                                                        <hr>
                                                                        Thank you for using the NRC consultation service. Please do not hesitate to contact us with any questions or concerns.<br>
                                                                        Sincerely,<br>
                                                                        Nursing Research Center (NRC)<br>
                                                                        Faculty of Nursing, Chiang Mai University<br>
                                                                        Should you have any queries, please contact us.<br>
                                                                        Tel.: 053-935033<br>

                                                                        </textarea>
                                                                </div>
                                                            </div>
                                                            <script type="text/javascript">
                                                                function sendEmail() {
                                                                    var name = $("#name");
                                                                    var email = $("#email");
                                                                    var header = $("#header");
                                                                    var detail = $("#detail");

                                                                    if (isNotEmpty(name) && isNotEmpty(email) && isNotEmpty(header) && isNotEmpty(detail)) {
                                                                        $.ajax({
                                                                            url: 'sendEmail.php',
                                                                            method: 'POST',
                                                                            dataType: 'json',
                                                                            data: {
                                                                                name: name.val(),
                                                                                email: email.val(),
                                                                                header: header.val(),
                                                                                detail: detail.val()
                                                                            },
                                                                            success: function(response) {
                                                                                $('.msg').text("Message send successfully");
                                                                            }
                                                                        });
                                                                    }
                                                                }

                                                                function isNotEmpty(caller) {
                                                                    if (caller.val() == "") {
                                                                        caller.css('border', '1px solid red');
                                                                        return false;
                                                                    } else caller.css('border', '');

                                                                    return true;
                                                                }
                                                            </script>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                                Close
                                                            </button>
                                                            <button type="submit" class="btn btn-primary">Confirmed</button>
                                                        </div>
                                                    </form>
                                                    <?php
                                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                                        // require_once 'index-db.php';
                                                        // require_once 'sendEmail.php';
                                                        echo '<pre>';
                                                        print_r($_POST);
                                                        echo '</pre>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
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
    <div class="layout-overlay layout-menu-toggle"></div>
    </div>
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
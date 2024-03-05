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
                            <div class="col-md-12 col-lg-4 col-xl-12 order-0 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div id='calendar'></div>
                                    </div>
                                    <div class="modal fade" id="exLargeModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-xl" title="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel4">Booking</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: ['interaction', 'dayGrid'],
                editable: true,
                eventLimit: true,
                events: [],
                duration: {
                    days: 365
                },

                eventClick: function(info) {
                    var event = info.event;
                    var modal = new bootstrap.Modal(document.getElementById('exLargeModal'));
                    modal.show();

                    var modalContent = `
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel4">Booking</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <form method="POST">
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                             <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                                    <label for="timeslot" class="form-label">Time</label>
                                                                                    <div class="input-group input-group-merge">
                                                                                        <span class="input-group-text"><i class="bx bx-time"></i></span>
                                                                                        <input type="text" name="timeslot" id="timeslot" class="form-control" value="${event.extendedProps.timeslot}" readonly />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                                    <label for="title" class="form-label">Title</label>
                                                                                    <div class="input-group input-group-merge">
                                                                                        <span class="input-group-text"><i class="bx bx-purchase-tag-alt"></i></span>
                                                                                        <input type="text" name="title" id="title" class="form-control" value="${event.title}" readonly />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                                    <label for="name" class="form-label">FullName</label>
                                                                                    <div class="input-group input-group-merge">
                                                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                                                        <input type="text" name="name" id="name" class="form-control" value="${event.extendedProps.name}" readonly />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                                    <label for="email" class="form-label">Email</label>
                                                                                    <div class="input-group input-group-merge">
                                                                                        <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                                                                        <input type="text" name="email" id="email" class="form-control" value="${event.extendedProps.email}" readonly />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                                    <label for="meeting" class="form-label">Meeting</label>
                                                                                    <div class="input-group input-group-merge">
                                                                                        <span class="input-group-text"><i class="bx bx-navigation"></i></span>
                                                                                        <input type="text" name="meeting" id="meeting" class="form-control" value="${event.extendedProps.meeting}" readonly />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-12 col-md-12 col-12 mb-2">
                                                                                    <label for="manutitle" class="form-label">Manuscript Title</label>
                                                                                    <div class="input-group input-group-merge">
                                                                                        <span class="input-group-text"><i class="bx bx-comment"></i></span>
                                                                                        <textarea id="manutitle" name="manutitle" class="form-control" placeholder="Hi" aria-describedby="basic-icon-default-message2" readonly>${event.extendedProps.manutitle}</textarea>
                                                                                    </div>
                                                                                </div>      
                                                                                <hr>
                                                                                <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                                    <label for="status_user" class="form-label">Status</label>
                                                                                    <div class="input-group input-group-merge">
                                                                                        <span class="input-group-text"><i class="bx bx-down-arrow-alt"></i></span>
                                                                                        <input type="text" name="status_user" id="status_user" class="form-control" value="${event.extendedProps.status_user}" readonly />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6 col-md-6 col-12 mb-2">
                                                                                    <label for="service" class="form-label">Service</label>
                                                                                    <div class="input-group input-group-merge">
                                                                                        <span class="input-group-text"><i class="bx bx-down-arrow-alt"></i></span>
                                                                                        <input type="text" name="service" id="service" class="form-control" value="${event.extendedProps.service}" readonly />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-12 col-md-12 col-12 mb-2">
                                                                                    <label for="note" class="form-label">Note</label>
                                                                                    <div class="input-group input-group-merge">
                                                                                        <span class="input-group-text"><i class="bx bx-comment"></i></span>
                                                                                        <textarea id="note" name="note" class="form-control" placeholder="Note" aria-describedby="basic-icon-default-message2" readonly>${event.extendedProps.note}</textarea>
                                                                                    </div>
                                                                                </div>                                         
                                                                            </div>                                                                 
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                                                    Close
                                                                                </button>
                                                                                </div>
                                                                        </div>    
                                                                    </form>
                                                                `;
                    $('#exLargeModal .modal-content').html(modalContent);
                },
                eventRender: function(info) {
                    if (info.event.title === 'Editor English Hours') {
                        info.el.style.backgroundColor = '#FF3E1D';
                    } else if (info.event.title === 'Research Consult') {
                        info.el.style.backgroundColor = '#FFAB00';
                    } else if (info.event.title === 'Statistic Consult') {
                        info.el.style.backgroundColor = '#03C3EC';
                    }

                }
            });
            <?php
            require_once 'connect.php';

            $stmt = $mysqli->prepare("SELECT id, timeslot, title, date, manutitle, name, email, meeting, tel, status_user, service, note FROM booking");
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $statusUser = $row['status_user'];
                $statusText = ''; // ตัวแปรสำหรับเก็บข้อความที่จะแสดง

                // กำหนดข้อความตามเงื่อนไขของ status_user
                if ($statusUser == "'") {
                    $statusText = 'Pending';
                } elseif ($statusUser == 1) {
                    $statusText = 'Confirmed';
                } elseif ($statusUser == 2) {
                    $statusText = 'Cancel Booking';
                } else {
                    $statusText = 'Contact Admin';
                }

                $event = array(
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'date' => $row['date'],
                    'timeslot' => $row['timeslot'],
                    'manutitle' => $row['manutitle'],
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'meeting' => $row['meeting'],
                    'tel' => $row['tel'],
                    'status_user' => $statusText, // ใช้ข้อความที่กำหนดตามเงื่อนไข
                    'service' => $row['service'],
                    'note' => $row['note'],
                );
                echo "calendar.addEvent(" . json_encode($event) . ");\n";
            }
            ?>

            calendar.render();
        });
    </script>

    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src='../fullcalendar/packages/core/main.js'></script>
    <script src='../fullcalendar/packages/interaction/main.js'></script>
    <script src='../fullcalendar/packages/daygrid/main.js'></script>
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
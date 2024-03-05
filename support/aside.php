<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.php" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="../assets/img/favicon/logo.png" alt="Logo" width="50">
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">RSSB</span>
        </a>


        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <?php
        require_once '../user/connect.php';

        $stmt = $mysqli->prepare("SELECT COUNT(*) AS count FROM booking WHERE status_user = 0");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $count = $row['count'];
        } else {
            $count = 0;
        }
        ?>

        <li class="menu-item">
            <a href="index.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bookmark-plus"></i>
                <div data-i18n="Analytics">Booking</div>&nbsp;
                <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-danger"><?php echo $count; ?></span>
            </a>
        </li>
        <li class="menu-item">
            <a href="booking_off.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar-x"></i>
                <div data-i18n="">Hours Of Availability</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="calendar.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar"></i>
                <div data-i18n="">calendar</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="report.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-arrow-from-top"></i>
                <div data-i18n="">Report</div>
            </a>
        </li>

    </ul>
</aside>
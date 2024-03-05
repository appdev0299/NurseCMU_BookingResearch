<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;
    $selected_status_user = isset($_GET['status_user']) ? $_GET['status_user'] : null;
    $selected_title = isset($_GET['title']) ? $_GET['title'] : null;

    require_once 'connect.php';

    $sql = "SELECT id, booking_id, date, timeslot, title, name, email, tel, meeting, manutitle, status_user, service, note, dateCreate FROM booking WHERE 1=1";

    if (!empty($start_date)) {
        $sql .= " AND date >= ?";
    }

    if (!empty($end_date)) {
        $sql .= " AND date <= ?";
    }

    if (!empty($selected_status_user)) {
        $sql .= " AND status_user = ?";
    }

    if (!empty($selected_title)) {
        $sql .= " AND title = ?";
    }

    $stmt = $mysqli->prepare($sql);

    if (!empty($start_date)) {
        $stmt->bind_param('s', $start_date);
    }

    if (!empty($end_date)) {
        $stmt->bind_param('s', $end_date);
    }

    if (!empty($selected_status_user)) {
        $stmt->bind_param('s', $selected_status_user);
    }

    if (!empty($selected_title)) {
        $stmt->bind_param('s', $selected_title);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $results = array();
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }

    if (empty($results)) {
        $sql = "SELECT id, booking_id, date, timeslot, title, name, email, tel, meeting, manutitle, status_user, service, note, dateCreate FROM booking WHERE 1=1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
    }

    if (!empty($results)) {
        require_once '../vendor/autoload.php';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $columns = ['Booking_id', 'Date', 'Timeslot', 'Title', 'Name', 'Email', 'Tel', 'Meeting', 'Manutitle', 'Status', 'Service', 'Note', 'DateCreate'];
        $col = 'A';

        foreach ($columns as $column) {
            $sheet->setCellValue($col . '1', $column);
            $col++;
        }
        $row = 2;
        $totalAmount = 0;

        foreach ($results as $result) {
            $col = 'A';
            $sheet->setCellValue($col . $row, $result['booking_id']);
            $col++;
            $sheet->setCellValue($col . $row, $result['date']);
            $col++;
            $sheet->setCellValue($col . $row, $result['timeslot']);
            $col++;
            $sheet->setCellValue($col . $row, $result['title']);
            $col++;
            $sheet->setCellValue($col . $row, $result['name']);
            $col++;
            $sheet->setCellValue($col . $row, $result['email']);
            $col++;
            $sheet->setCellValue($col . $row, $result['tel']);
            $col++;
            $sheet->setCellValue($col . $row, $result['meeting']);
            $col++;
            $sheet->setCellValue($col . $row, $result['manutitle']);
            $col++;
            $sheet->setCellValue($col . $row, $result['status_user']);
            $col++;
            $sheet->setCellValue($col . $row, $result['service']);
            $col++;
            $sheet->setCellValue($col . $row, $result['note']);
            $col++;
            $sheet->setCellValue($col . $row, $result['dateCreate']);
            $row++;
        }

        $filename = 'BookingReport_' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    } else {
        echo "No data found.";
    }
}

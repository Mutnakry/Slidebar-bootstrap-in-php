<?php
// Headers for download
// include "../connection.php";

// header("Content-Type: application/vnd.ms-excel");
// header("Content-Disposition: attachment; Filename = Data.xls");

// require './productbetween.php';
?>
<?php

session_start();
include "../../connection.php";

$start_date = $_GET['start_date'] ?? '';
$date_to = $_GET['date_to'] ?? '';
// Check if the dates are set
if (!empty($start_date) && !empty($date_to)) {
    // Set headers for Excel file download
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Data.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Fetch data from the database
    $sql = "SELECT * FROM product WHERE created_at BETWEEN '$start_date' AND '$date_to' ORDER BY id DESC";
    $query = mysqli_query($conn, $sql);

    // Output the table headers
    echo "<table border='1'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Sale Price</th>
                    <th>Quantity</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>";

    // Fetch each row and output it
    if ($query) {
        while ($row = mysqli_fetch_array($query)) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['names']}</td>
                    <td>{$row['sale_price']}</td>
                    <td>{$row['qty']}</td>
                    <td>{$row['created_at']}</td>
                  </tr>";
        }
    }
    echo "</tbody></table>";
    exit();
} else {
    echo "No data available for the selected date range.";
}
?>

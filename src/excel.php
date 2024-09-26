<?php
session_start();
include_once "includes/header.php";
include "../connection.php";

if (isset($_POST['submit'])) {
    $file = $_FILES['file']['tmp_name'];

    if (($handle = fopen($file, "r")) !== FALSE) {
        // Skip the first row (header)
        fgetcsv($handle);

        // Loop through the CSV rows
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $name = $data[0];
            $sale_price = $data[1];
            $quantity = $data[2];
            $created_at = $data[3];

            // Insert into the database
            $sql = "INSERT INTO product (names, sale_price, qty, created_at) VALUES ('$name', '$sale_price', '$quantity', '$created_at')";
            mysqli_query($conn, $sql);
        }
        fclose($handle);
        echo "Data successfully imported!";
    } else {
        echo "Failed to open the file.";
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.dataTables.css">
</head>

<body>
    <div class="card">
            <form enctype="multipart/form-data" action="import.php" method="POST">
                <input type="file" name="file" accept=".xls" required>
                <button type="submit" name="submit">Import</button>
            </form>
    </div>
    <?php include_once "./includes/footer.php"; ?>
    <script src="../assets/js/dashboard.js"></script>


</html>
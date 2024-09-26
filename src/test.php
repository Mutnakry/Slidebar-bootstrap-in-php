<?php
session_start();
include_once "includes/header.php";
include "../connection.php";

$start_date = '';
$date_to = '';

if (isset($_POST['save'])) {
    $start_date = $_POST['start_date'];
    $date_to = $_POST['date_to'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>

<body>
    <div class="card">
        <div class="container">
            <form action="" method="post">
                <div class="form-group">
                    <label for=""> Start Date</label>
                    <input type="date" class="form-control" name="start_date" required>
                </div>
                <div class="form-group">
                    <label for=""> Date to</label>
                    <input type="date" class="form-control" name="date_to" required>
                </div>
                <button name="save" class="btn btn-info">Between</button>
            </form>
        </div>

        <table class="table table-bordered">
        <a href="./exporttoexcel.php"> <button type="button" name="button">Export To Excel</button> </a>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Sale Price</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($start_date) && !empty($date_to)) {
                    // Prepare the SQL statement
                    $sql = "SELECT * FROM product WHERE created_at BETWEEN '$start_date' AND '$date_to' ORDER BY id DESC";
                    $query = mysqli_query($conn, $sql);
                    $i = 1;
                    if ($query) {
                        while ($row = mysqli_fetch_array($query)) {
                ?>
                            <tr>
                                <td class="px-6"><?php echo $i++; ?></td>
                                <td><?php echo $row['names'] ?></td>
                                <td><?php echo $row['sale_price'] ?></td>
                                <td><?php echo $row['qty'] ?></td>
                                <td><?php echo $row['created_at'] ?></td>
                            </tr>
                <?php
                        }
                    } else {
                        echo "<tr><td colspan='5'>No records found.</td></tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php include_once "./includes/footer.php"; ?>
    <script src="../assets/js/dashboard.js"></script>
</body>

</html>
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
        <div class="p-4">
            <form action="" method="post">
                <div class="row">
                    <!-- Start Date -->
                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                        <label for="start_date">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <!-- Date To -->
                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                        <label for="date_to">Date To</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" required>
                    </div>
                    <!-- Button -->
                    <div class="form-group col-12 col-sm-12 col-md-4 col-lg-2 d-flex align-items-center justify-content-center">
                        <button name="save" class="btn btn-info mt-3 mt-md-0">Between</button>
                    </div>
                </div>
            </form>


        </div>

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Sale Price</th>
                    <th>Quantity</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <div class="row p-4 grid gap-3">
                    <?php if (!empty($start_date) && !empty($date_to)): ?>
                        <div class="g-col-2 mx-3">
                            <button class="btn btn-info">Print</button>
                        </div>
                        <div class="g-col-2">
                            <a href="export/exporttoexcel.php?start_date=<?= $start_date; ?>&date_to=<?= $date_to; ?>" class="btn btn-success">Export To Excel</a>
                        </div>
                    <?php endif; ?>
                </div>


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

        <!-- Link to export to Excel -->

    </div>
    <?php include_once "./includes/footer.php"; ?>
    <script src="../assets/js/dashboard.js"></script>
</body>

</html>
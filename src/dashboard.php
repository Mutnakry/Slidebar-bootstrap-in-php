<?php
session_start();
include_once "includes/header.php";
include "../connection.php";

// 1today dolla
$sumtodayDolla = mysqli_query($conn,"SELECT SUM(`total_dollar`) AS total_today FROM `customer` WHERE DATE(created_at) = CURDATE()");
$query = mysqli_fetch_assoc($sumtodayDolla);
$totalTodayDolla = $query['total_today'];
// 1today KHR
$sumtodayKH = mysqli_query($conn,"SELECT SUM(`total_kh`) AS total_today FROM `customer` WHERE DATE(created_at) = CURDATE()");
$query = mysqli_fetch_assoc($sumtodayKH);
$totalTodayKH = $query['total_today'];
// 1 week doola
$sumweekdolla = mysqli_query($conn,"SELECT SUM(total_dollar) AS total_sum FROM customer WHERE DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)   AND DATE(created_at) < CURDATE()");
$query = mysqli_fetch_assoc($sumweekdolla);
$totalweekdolla = $query['total_sum'];
// 1 week KHR
$sumweekdLHR = mysqli_query($conn,"SELECT SUM(total_kh) AS total_sum FROM customer WHERE DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)   AND DATE(created_at) < CURDATE()");
$query = mysqli_fetch_assoc($sumweekdLHR);
$totalweekKHR = $query['total_sum'];

// 1 month dolla
$monthdolla = mysqli_query($conn," SELECT SUM(total_dollar) AS total_sum FROM customer WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND created_at < CURDATE();");
$query = mysqli_fetch_assoc($monthdolla);
$totalmonthD = $query['total_sum'];

// 1 month KHR
$monthKHR = mysqli_query($conn," SELECT SUM(total_kh) AS total_sum FROM customer WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND created_at < CURDATE();");
$query = mysqli_fetch_assoc($monthKHR);
$totalmonthKHR = $query['total_sum'];

//  product qty
$saleQTY = mysqli_query($conn,"SELECT SUM(`qty`) as qty  FROM `order` WHERE DATE(created_at) = CURDATE()");
$query = mysqli_fetch_assoc($saleQTY);
$totalQTY = $query['qty'];
// count  product 
$countproduct = mysqli_query($conn,"SELECT count(`names`) as names  FROM `product`");
$query = mysqli_fetch_assoc($countproduct);
$totalcount = $query['names'];


// ចំពាយក្នុងថ្ងៃនេះ purchase
$saveing = mysqli_query($conn,"SELECT SUM(`total`) as total  FROM `purchase` WHERE DATE(created_at) = CURDATE()");
$query = mysqli_fetch_assoc($saveing);
$totalsaveing = $query['total'];


// ចំពាយក្នុងថ្ងៃនេះ [product]]
$saveingproduct = mysqli_query($conn,"SELECT SUM(`original_price`) as original_price  FROM `product` WHERE DATE(created_at) = CURDATE()");
$query = mysqli_fetch_assoc($saveingproduct);
$totalsaveingproduct = $query['original_price'];




?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    .inner-flex {
      display: flex;
      gap: 25px;
      align-items: center;
    }
  </style>

</head>

<body>
  <div class="card">
    <div class="card-header text-center">
      <h1>Welcome, <?php echo $_SESSION['names']; ?>!</h1>
      <p>Your role: <?php echo $_SESSION['rol']; ?></p>
    </div>
    <div class="card-body">

      <div class="row">

        <div class="col-sm-3 col-md-6 col-lg-4">
          <div class="small-box bg-gradient-success">
            <div class="inner inner-flex">
              <div>
                <h4>$ <?php echo number_format($totalTodayDolla, 2); ?></h4>
                <h4>1 Today</h4>
              </div>
              <div>
                <h4><?php echo number_format($totalTodayKH, 2); ?> ៛</h4>
                <h4 >1 Today</h4>
              </div>
            </div>
            <div class="icon">
              <i class="fas fa-credit-card"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-sm-3 col-md-6 col-lg-4">
          <div class="small-box bg-info">
            <div class="inner inner-flex">
              <div>
                <h4>$ <?php echo number_format($totalweekdolla, 2); ?></h4>
                <h4>1 Week</h4>
              </div>
              <div>
                <h4><?php echo number_format($totalweekKHR, 2); ?> ៛</h4>
                <h4 >1 Week</h4>
              </div>
            </div>
            <div class="icon">
              <i class="fas fa-credit-card"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-sm-3 col-md-6 col-lg-4">
          <div class="small-box bg-gray">
            <div class="inner inner-flex">
              <div>
                <h4>$ <?php echo number_format($totalmonthD, 2); ?></h4>
                <h4>1 Month</h4>
              </div>
              <div>
                <h4><?php echo number_format($totalmonthKHR, 2); ?> ៛</h4>
                <h4 >1 Week</h4>
              </div>
            </div>
            <div class="icon">
              <i class="fas fa-credit-card"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-sm-3 col-md-6 col-lg-4">
          <div class="small-box bg-pink">
            <div class="inner">
              <h3>ផលិតផលលក់ថ្ងៃនេះ : <?php echo number_format($totalQTY); ?></h3>
              <h3>ផលិតផលមាន​ : <?php echo number_format($totalcount); ?></h3>
              <p>ថ្ងៃនេះលក់ផលិតផលបានចំនួន</p>
            </div>
            <div class="icon">
              <i class="fas fa-user-plus"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-sm-3 col-md-6 col-lg-4">
          <div class="small-box bg-danger">
            <div class="inner">
            <h3>$ <?php echo number_format($totalsaveing + $totalsaveingproduct); ?></h3>
              <h3>$pur <?php echo number_format($totalsaveing ); ?></h3>
              <h3>$ pro <?php echo number_format($totalsaveingproduct ); ?></h3>
              <p>ថ្ងៃនេះចំណាយអស់</p>
            </div>
            <div class="icon">
              <i class="fas fa-shopping-cart"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-sm-3 col-md-6 col-lg-4">
          <div class="small-box bg-gradient-success">
            <div class="inner">
              <h3>4400000000000</h3>
              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="fas fa-cogs"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
<?php include_once "includes/footer.php"; ?>

<script src="../assets/js/dashboard.js"></script>
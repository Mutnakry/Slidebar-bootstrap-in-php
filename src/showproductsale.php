<?php
include_once "includes/header.php";
include "../connection.php"; // Make sure this is the correct connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_qty = $_POST['product_qty'];
        $discount = $_POST['discount'];

        // Fetch the available quantity from the database
        $sql = "SELECT qty FROM product WHERE id = $product_id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $available_qty = $row['qty'];




        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        if (isset($_SESSION['cart'][$product_id])) {
            $current_qty = $_SESSION['cart'][$product_id]['qty'];
            if ($current_qty + $product_qty <= $available_qty) {
                $_SESSION['cart'][$product_id]['qty'] += $product_qty;
            } else {
                $_SESSION['toastr'] = array(
                    'type' => 'error',
                    'message' => 'Product limit reached!',
                    'title' => 'Error'
                );
            }
        } else {
            if ($product_qty <= $available_qty) {
                $_SESSION['cart'][$product_id] = array(
                    'id' => $product_id,
                    'name' => $product_name,
                    'price' => $product_price,
                    'qty' => $product_qty,
                    'discount' => $discount
                );
            } else {
                $_SESSION['toastr'] = array(
                    'type' => 'error',
                    'message' => 'Product limit reached!',
                    'title' => 'Error'
                );
            }
        }
    }

    if (isset($_POST['remove_from_cart'])) {
        $product_id = $_POST['product_id'];
        unset($_SESSION['cart'][$product_id]);
    }


    if (isset($_POST['update_cart'])) {
        $product_id = $_POST['product_id'];
        $new_qty = $_POST['new_qty'];

        $sql = "SELECT qty FROM product WHERE id = $product_id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $available_qty = $row['qty'];

        if ($new_qty > 0 && $new_qty <= $available_qty) {
            $_SESSION['cart'][$product_id]['qty'] = $new_qty;
        } elseif ($new_qty > $available_qty) {
            $_SESSION['toastr'] = array(
                'type' => 'error',
                'message' => 'Product limit reached!',
                'title' => 'Error'
            );
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    if (isset($_POST['remove_from_cart'])) {
        $product_id = $_POST['product_id'];
        unset($_SESSION['cart'][$product_id]);
    }


    //  add to database table orde add customer
    // insert success 1
    // if (isset($_POST['place_order'])) {
    //     if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    //         $dula = $_POST['dula'];

    //         // Calculate totals
    //         $amountTotal = 0;
    //         $total = 0;
    //         $amountdiscount = 0;

    //         foreach ($_SESSION['cart'] as $item) {
    //             $discounted_price = $item['price'] - ($item['price'] * ($item['discount'] / 100));
    //             $subtotal = ($discounted_price * $item['qty']);
    //             $total += $subtotal;
    //             $amountTotal += ($item['price'] * $item['qty']);
    //             $amountdiscount += (($item['price'] * $item['qty']) - $subtotal);
    //         }

    //         // Insert order into `customer` table
    //         $sql = "INSERT INTO customer (amount_total, total_dollar, total_kh, total_dis, one_dollar, pay_by) 
    //             VALUES (?, ?, ?, ?, ?, ?)";
    //         $stmt = $conn->prepare($sql);
    //         $stmt->bind_param('ddddss', $amountTotal, $total, $total, $amountdiscount, $dula, $_POST['pay_by']);

    //         if ($stmt->execute()) {
    //             $customer_id = $conn->insert_id;

    //             // Insert order details into `order` table
    //             $sql = "INSERT INTO `order` (customer_id, product_id, qty, price, discount, total) 
    //                 VALUES (?, ?, ?, ?, ?, ?)";
    //             $stmt = $conn->prepare($sql);

    //             foreach ($_SESSION['cart'] as $item) {
    //                 $product_id = $item['id'];
    //                 $product_qty = $item['qty'];
    //                 $product_price = $item['price'];
    //                 $product_discount = $item['discount'];
    //                 $discounted_price = $product_price - ($product_price * ($product_discount / 100));
    //                 $subtotal = $discounted_price * $product_qty;

    //                 $stmt->bind_param('iiiddd', $customer_id, $product_id, $product_qty, $product_price, $product_discount, $subtotal);
    //                 if (!$stmt->execute()) {
    //                     $_SESSION['toastr'] = array(
    //                         'type' => 'error',
    //                         'message' => 'Order details could not be inserted. Please try again later.',
    //                         'title' => 'Error'
    //                     );
    //                     error_log("MySQL Error: " . $stmt->error);
    //                     exit;
    //                 }
    //             }

    //             unset($_SESSION['cart']); // Clear the cart after placing the order
    //             $_SESSION['toastr'] = array(
    //                 'type' => 'success',
    //                 'message' => 'Order placed successfully!',
    //                 'title' => 'Success'
    //             );
    //         } else {
    //             $_SESSION['toastr'] = array(
    //                 'type' => 'error',
    //                 'message' => 'Order could not be placed. Please try again later.',
    //                 'title' => 'Error'
    //             );
    //             error_log("MySQL Error: " . $stmt->error);
    //         }
    //         $stmt->close();
    //     } else {
    //         $_SESSION['toastr'] = array(
    //             'type' => 'error',
    //             'message' => 'Your cart is empty!',
    //             'title' => 'Error'
    //         );
    //     }
    // }
    // insert success 1
    if (isset($_POST['place_order'])) {
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {

            // Calculate totals
            $amountTotal = 0;
            $total = 0;
            $amountdiscount = 0;

            foreach ($_SESSION['cart'] as $item) {
                $discounted_price = $item['price'] - ($item['price'] * ($item['discount'] / 100));
                $subtotal = ($discounted_price * $item['qty']);
                $total += $subtotal;
                $amountTotal += ($item['price'] * $item['qty']);
                $amountdiscount += (($item['price'] * $item['qty']) - $subtotal);
            }

            // Get the username from the session or set to 'Guest' if not logged in
            $userName = isset($_SESSION['names']) ? $_SESSION['names'] : 'Guest';
            $dula = $_POST['dula'];
            $total_kh =  $dula * $total;

            // Insert order into `customer` table
            $sql = "INSERT INTO customer (amount_total, total_dollar, total_kh, total_dis, one_dollar, pay_by, userNote) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ddddsss', $amountTotal, $total, $total_kh, $amountdiscount, $dula, $_POST['pay_by'], $userName);

            if ($stmt->execute()) {
                $customer_id = $conn->insert_id;

                // Insert order details into `order` table
                $sql = "INSERT INTO `order` (customer_id, product_id, qty, price, discount, total) 
                    VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                foreach ($_SESSION['cart'] as $item) {
                    $product_id = $item['id'];
                    $product_qty = $item['qty'];
                    $product_price = $item['price'];
                    $product_discount = $item['discount'];
                    $discounted_price = $product_price - ($product_price * ($product_discount / 100));
                    $subtotal = $discounted_price * $product_qty;

                    // Bind parameters for the `order` table
                    $stmt->bind_param('iiiddd', $customer_id, $product_id, $product_qty, $product_price, $product_discount, $subtotal);
                    if (!$stmt->execute()) {
                        $_SESSION['toastr'] = array(
                            'type' => 'error',
                            'message' => 'Order details could not be inserted. Please try again later.',
                            'title' => 'Error'
                        );
                        error_log("MySQL Error: " . $stmt->error);
                        exit;
                    }
                }

                unset($_SESSION['cart']); // Clear the cart after placing the order
                $_SESSION['toastr'] = array(
                    'type' => 'success',
                    'message' => 'Order placed successfully!',
                    'title' => 'Success'
                );
            } else {
                $_SESSION['toastr'] = array(
                    'type' => 'error',
                    'message' => 'Order could not be placed. Please try again later.',
                    'title' => 'Error'
                );
                error_log("MySQL Error: " . $stmt->error);
            }
            $stmt->close();
        } else {
            $_SESSION['toastr'] = array(
                'type' => 'error',
                'message' => 'Your cart is empty!',
                'title' => 'Error'
            );
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- sccript tostr -->
    <script>
        $(document).ready(function() {
            <?php
            if (isset($_SESSION['toastr'])) {
                echo "toastr." . $_SESSION['toastr']['type'] . "('" . $_SESSION['toastr']['message'] . "', '" . $_SESSION['toastr']['title'] . "');";
                unset($_SESSION['toastr']);
            }
            ?>
        });
    </script>
    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-center",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    </script>

    <style>
        .card-img-top {
            width: 100%;
            height: auto;
        }

        .card {
            max-width: 18rem;
            margin: auto;
        }
    </style>

    <!-- add to cart -->
    <script>
        function addToCart(productId, productName, productPrice, availableQty, discount) {
            const form = document.createElement('form');
            form.method = 'post';
            form.action = '';

            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'product_id';
            idInput.value = productId;
            form.appendChild(idInput);

            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'product_name';
            nameInput.value = productName;
            form.appendChild(nameInput);

            const priceInput = document.createElement('input');
            priceInput.type = 'hidden';
            priceInput.name = 'product_price';
            priceInput.value = productPrice;
            form.appendChild(priceInput);

            const discountInput = document.createElement('input');
            discountInput.type = 'hidden';
            discountInput.name = 'discount';
            discountInput.value = discount;
            form.appendChild(discountInput);


            const qtyInput = document.createElement('input');
            qtyInput.type = 'hidden';
            qtyInput.name = 'product_qty';
            qtyInput.value = 1;
            form.appendChild(qtyInput);

            const addAction = document.createElement('input');
            addAction.type = 'hidden';
            addAction.name = 'add_to_cart';
            form.appendChild(addAction);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</head>

<body>
    <div class="mt-2">
        <form method="post" action="">
            <div class="row">
                <div class="col-lg-8  col-md-12">
                    <div class="row">
                        <?php
                        if (isset($_GET['idcat'])) {
                            $IDcat = intval($_GET['idcat']);
                            $sql = "SELECT pro.*, pro.id as proid, cat.names as cat_names FROM product as pro INNER JOIN categories as cat ON pro.category_id = cat.id WHERE pro.category_id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param('i', $IDcat);
                            $stmt->execute();
                            $result = $stmt->get_result();
                        ?>
                            <?php
                            while ($row = $result->fetch_assoc()) {
                            ?>
                                <div class="col-lg-2 mb-3">
                                    <div class="card h-100" style="cursor: pointer;" onclick="addToCart('<?php echo $row['proid']; ?>', '<?php echo $row['names']; ?>', '<?php echo $row['sale_price']; ?>', '<?php echo $row['qty']; ?>','<?php echo $row['discount']; ?>')">
                                        <div class="d-flex flex-column align-items-center">
                                            <img src="./uploads/<?php echo htmlspecialchars(basename($row['image']), ENT_QUOTES, 'UTF-8'); ?>"
                                                alt="Product Image"
                                                class="img-fluid"
                                                style="max-width: 100%; height: auto; max-height: 90px; width: auto;">
                                            <div class="px-2 py-2 text-center">
                                                <h5 class="card-title"><?php echo $row["names"]; ?></h5>
                                                <h5 class="card-text">$ <?php echo $row["sale_price"]; ?></h5>
                                                <p class="card-text text-danger">Dis: <?php echo $row["discount"]; ?></p>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            <?php
                            }
                            ?>
                        <?php
                            $stmt->close();
                        } else {
                            echo "<p>Please select a category to view products.</p>";
                        }
                        ?>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 overflow-x-auto" style="max-height: auto; overflow-x: auto; overflow-y: auto;">
                    <div>
                        <h2>Shopping Cart</h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Dis</th>
                                    <th>Subtotal</th>
                                    <th>Actions</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                $amountdiscount = 0;
                                $amountTotal = 0;
                                $i = 1;
                                if (isset($_SESSION['cart'])) {
                                    foreach ($_SESSION['cart'] as $item) {
                                        $discounted_price = $item['price'] - ($item['price'] * ($item['discount'] / 100));
                                        $subtotal = ($discounted_price * $item['qty']);
                                        $total += $subtotal;
                                        $amount = ($item['price'] * $item['qty']);
                                        $amountTotal += $amount;

                                        $totaldis = (($item['price'] * $item['qty']) - $subtotal);

                                        $amountdiscount +=  $totaldis

                                ?>
                                        <tr>
                                            <td><?php echo $i++ ?></td>
                                            <td><?php echo $item['name']; ?></td>
                                            <td>$ <?php echo $item['price']; ?></td>
                                            <td style="text-wrap: nowrap;">
                                                <form method="post" action="" style="display:inline;">
                                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                                    <input type="hidden" name="new_qty" value="<?php echo max(1, $item['qty'] - 1); ?>">
                                                    <button type="submit" name="update_cart" class="btn btn-sm btn-outline-secondary">-</button>
                                                </form>
                                                <?php echo $item['qty']; ?>
                                                <form method="post" action="" style="display:inline;">
                                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                                    <input type="hidden" name="new_qty" value="<?php echo $item['qty'] + 1; ?>">
                                                    <button type="submit" name="update_cart" class="btn btn-sm btn-outline-secondary">+</button>
                                                </form>
                                            </td>
                                            <td><?php echo $item['discount']; ?></td>
                                            <td>$ <?php echo number_format($subtotal, 2); ?></td>
                                            <td>
                                                <form method="post" action="">
                                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                                    <button type="submit" name="remove_from_cart" class="btn btn-sm btn-danger">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="5">Your cart is empty</td></tr>';
                                }
                                ?>
                                <tr>
                                    <td colspan="4">សរុបរង</td>
                                    <td colspan="2">$ <?php echo number_format($amountTotal, 2); ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="4">សរុប</td>សរុបជាប្រាក់រៀល
                                    <td colspan="2">$ <?php echo number_format($total, 2); ?></td>

                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3">1$ តម្លៃ</td>
                                    <td colspan="2">
                                        <select name="dula" class="form-control" id="dula" onchange="convertToKHR()">
                                            <option value="39">39</option>
                                            <option value="40">40</option>
                                            <option value="41">41</option>
                                            <option value="42" selected>42</option>
                                        </select>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3">លក់តាម</td>
                                    <td colspan="2">
                                        <select name="pay_by" class="form-control">
                                            <option value="payment" selected>Payment All</option>
                                            <option value="cash">Cash</option>
                                            <option value="card">Card</option>
                                        </select>
                                    </td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td colspan="4">សរុបជាប្រាក់រៀល</td>
                                    <td colspan="2"><span id="totalInKHR"><?php echo number_format($total * 42, 2); ?></span> KHR</td>

                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="4">សន្សំ</td>
                                    <td colspan="2"><span class="text-danger">$ <?php echo number_format($amountdiscount, 2); ?></span></td>

                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="5">
                                        <button type="submit" name="place_order" class="btn btn-sm btn-primary">Place Order</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php include_once "includes/footer.php"; ?>
</body>
<script>
    function convertToKHR() {
        var total = <?php echo $total; ?>;
        var dula = document.getElementById('dula').value;
        var totalInKHR = total * dula;

        // Update both the spans where total in KHR is displayed
        document.getElementById('totalInKHR').innerText = totalInKHR.toFixed(2);
    }
</script>

</html>
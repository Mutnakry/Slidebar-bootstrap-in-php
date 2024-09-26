<?php
session_start();
include_once "includes/header.php";
include "../connection.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <!-- Include any necessary CSS files here -->

    <!-- alter -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->
    <!-- modal bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


</head>

<body>
    <div class="card">
        <div class="card-header text-start">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addproduct"><img src="./icon/icons8-add-60.png" alt="" height="24"></button>

        </div>
        <div class="card-body">


            <?php
            if (isset($_SESSION['success'])):
            ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });

                        Toast.fire({
                            icon: "success",
                            title: "<?php echo $_SESSION['success']; ?>"
                        });

                        const audio = new Audio('./mp3/success-1-6297.mp3');
                        audio.play();
                    });
                </script>
            <?php
                unset($_SESSION['success']);
            elseif (isset($_SESSION['error'])): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });

                        Toast.fire({
                            icon: "error",
                            title: "<?php echo $_SESSION['error']; ?>"
                        });

                        const audio = new Audio('./mp3/error-8-206492.mp3'); // Replace with your error sound file path
                        audio.play();
                    });
                </script>
            <?php
                unset($_SESSION['error']);
            endif;
            ?>


            <table class="table table-sm" id="tbl">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3">#</th>
                        <th scope="col" class="px-6 py-3">Product name</th>
                        <th scope="col" class="px-6 py-3">QTY</th>
                        <th scope="col" class="px-6 py-3">Price</th>
                        <th scope="col" class="px-6 py-3">Total</th>
                        <th scope="col" class="px-6 py-3">Expiry</th>
                        <th scope="col" class="px-6 py-3">Photo</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT pur.*,pro.names ,pro.image FROM purchase as pur inner join product as pro on pur.product_id=pro.id  ORDER BY id ASC";
                    $i = 1;
                    $query = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_array($query)) {
                    ?>
                        <tr>
                            <td class="px-6"><?php echo $i++; ?></td>
                            <td><?php echo $row['names']; ?></td>
                            <td><?php echo $row['qty']; ?></td>
                            <td><?php echo $row['price']; ?></td>
                            <td><?php echo $row['total']; ?></td>
                            <td><?php echo $row['expiry']; ?></td>
                            <td>
                                <img src="./uploads/<?php echo htmlspecialchars(basename($row['image']), ENT_QUOTES, 'UTF-8'); ?>" alt="Category Image" class="rounded" height="40" width="40">
                            </td>
                            <td class="space-x-4 flex">
                                <button class=" btn-primary border-0 rounded-circle" data-bs-toggle="modal" data-bs-target="#update<?php echo $row['id']; ?>"><img src="./icon/edit.png" alt="" height="16"></button>
                                <button class="bg-red-400 border-0 btn-success rounded-circle" data-bs-toggle="modal" data-bs-target="#delete<?php echo $row['id']; ?>"><img src="./icon/delete.png" alt="" height="16"></button>
                            </td>
                        </tr>
                        <!-- edit -->
                        <!-- <div class="modal fade" id="update<?php echo $row['id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <form action="./crud/insert.php" method="POST" enctype="multipart/form-data">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title fs-3" id="staticBackdropLabel">បន្ងែម</h4>
                                            <img src="./icon/icons8-close-50.png" data-bs-dismiss="modal" aria-label="Close" height="24" alt="">
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="form-label">Name Category</label>
                                                <select name="productnamesup" id="catnames1" class="form-control" required>
                                                    <option value="">-----------------Select Values-----------------</option>
                                                    <?php
                                                    $sql = "SELECT * FROM product";
                                                    $qu = mysqli_query($conn, $sql);
                                                    while ($rows = mysqli_fetch_array($qu)) {
                                                    ?>
                                                        <option value="<?php echo $rows['id']; ?>" <?php if ($rows['id'] == $row['product_id']) echo 'selected'; ?>>
                                                            <?php echo $rows['names']; ?>
                                                        </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="detail">ចំនួន</label>
                                                <input type="number" id="qty" name="qty" min='1' value="<?php echo $row['qty']; ?>" onchange="convertTo()" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="detail">ថ្ងៃផុតកំណត់</label>
                                                <?php
                                                $today = date('Y-m-d');
                                                ?>
                                                <input type="date" id="expiry" name="expiry" value="<?php echo $row['expiry']; ?>" class="form-control" min="<?php echo $today; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="detail">តម្លៃ</label>
                                                <input type="number" id="productPrice" name="price" value="<?php echo $row['price']; ?>" readonly class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="detail">សរុប</label>
                                                <input type="number" id="total" readonly name="total1" value="<?php echo $row['total']; ?>" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="saveProduct" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>  -->

                        <!-- Edit Modal -->
                        <div class="modal fade" id="update<?php echo $row['id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <form action="./crud/update.php" method="POST" enctype="multipart/form-data">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title fs-3" id="staticBackdropLabel">Update</h4>
                                            <img src="./icon/icons8-close-50.png" data-bs-dismiss="modal" aria-label="Close" height="24" alt="">
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="form-label">Product Name</label>
                                                <select name="productnames" id="catnames1<?php echo $row['id']; ?>" class="form-control" required>
                                                    <option value="">-----------------Select Values-----------------</option>
                                                    <?php
                                                    $sql = "SELECT * FROM product";
                                                    $qu = mysqli_query($conn, $sql);
                                                    while ($rows = mysqli_fetch_array($qu)) {
                                                    ?>
                                                        <option value="<?php echo $rows['id']; ?>" <?php if ($rows['id'] == $row['product_id']) echo 'selected'; ?>>
                                                            <?php echo $rows['names']; ?>
                                                        </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <img src="" id="category_image<?php echo $row['id']; ?>" alt="Category Image" class="rounded mt-2 object-scale-down" height="80" style="display: none;">
                                            </div>
                                            <div class="form-group">
                                                <label for="detail">Quantity</label>
                                                <input type="number" id="qty" name="qty" min='1' value="<?php echo $row['qty']; ?>" onchange="convertTo()" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="detail">Expiry Date</label>
                                                <?php
                                                $today = date('Y-m-d');
                                                ?>
                                                <input type="date" id="expiry" name="expiry" value="<?php echo $row['expiry']; ?>" class="form-control" min="<?php echo $today; ?>" required>
                                            </div>
                                            <div class="form-group">
                                            <input type="hidden" name="purchaseid" value="<?php echo $row['id']; ?>">
                                                <label for="detail">Price</label>
                                                <input type="number" id="productPrice<?php echo $row['id']; ?>" name="price" value="<?php echo $row['price']; ?>" readonly class="form-control" required>
                                            </div>
                                            <!-- <div class="form-group">
                                                <label for="detail">Total</label>
                                                <input type="number" id="total" readonly name="total1" value="<?php echo $row['total']; ?>" class="form-control" required>
                                            </div> -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="saveProduct" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="delete<?php echo $row['id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title fs-3" id="staticBackdropLabel">តើអ្នកពិតជាលុបទិន្ន័យនេះមែនទេ ​? <span class="text-danger"><?php echo $row['names']; ?></span></h4>
                                        <img src="./icon//icons8-close-50.png" data-bs-dismiss="modal" aria-label="Close" height="24" alt="">
                                    </div>
                                    <div class="modal-footer">
                                        <a type="button" data-bs-dismiss="modal" aria-label="Close" class="btn btn-primary">បោះបង់</a>
                                        <a href="./crud/delete.php?deletepurchase=<?php echo $row['id']; ?>" class="btn btn-danger">លុបទិន្ន័យ</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php
                    }
                    ?>
                </tbody>
                <!-- Modal -->
                <div class="modal fade" id="addproduct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <form action="./crud/insert.php" method="POST" enctype="multipart/form-data">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-3" id="staticBackdropLabel">បន្ងែម</h4>
                                    <img src="./icon/icons8-close-50.png" data-bs-dismiss="modal" aria-label="Close" height="24" alt="">
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="form-label">Name Category</label>
                                        <select name="productnames" id="catnames" class="form-control" required>
                                            <option value="">-----------------Select Values-----------------</option>
                                            <?php
                                            $sql = "SELECT * FROM product";
                                            $qu = mysqli_query($conn, $sql);
                                            while ($rows = mysqli_fetch_array($qu)) {
                                            ?>
                                                <option value="<?php echo $rows['id'] ?>"><?php echo $rows['names'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <img src="" id="category_image" alt="Category Image" class="rounded mt-2 object-scale-down" height="80">
                                    </div>
                                    <div class="form-group">
                                        <label for="detail">ចំនួន</label>
                                        <input type="number" id="qty" name="qty" min='1' value="1" onchange="convertTo()" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="detail">ថ្ងៃផុតកំណត់</label>
                                        <?php
                                        $today = date('Y-m-d');
                                        ?>
                                        <input type="date" id="expiry" name="expiry" class="form-control" min="<?php echo $today; ?>" value="<?php echo $today; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="detail">តម្លៃ</label>
                                        <input type="number" id="productPrice" name="price" readonly class="form-control" required>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label for="detail">សរុប</label>
                                        <input type="number" id="total" readonly name="total" class="form-control" required>
                                    </div> -->
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="saveProduct" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </table>
        </div>
    </div>
    <?php include_once "includes/footer.php"; ?>
    <script src="../assets/js/dashboard.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script>
        // $(document).ready(function() {
        //     $('#catnames').change(function() {
        //         var productId = $(this).val();
        //         if (productId) {
        //             $('#category_image').hide(); // Hide the image initially
        //             $('#productPrice').val(''); // Clear the price field

        //             $.ajax({
        //                 url: 'get_product_image.php', // Your PHP file
        //                 type: 'POST',
        //                 data: {
        //                     id: productId
        //                 },
        //                 beforeSend: function() {
        //                     $('#category_image').attr('src', 'loading.gif'); // Optional loading image
        //                 },
        //                 success: function(response) {
        //                     var data = JSON.parse(response); // Parse the JSON response
        //                     if (data.image) {
        //                         $('#category_image').attr('src', './uploads/' + data.image).show();
        //                     } else {
        //                         $('#category_image').hide();
        //                     }
        //                     if (data.sale_price) {
        //                         $('#productPrice').val(data.sale_price); // Set the sale price in the input field
        //                     } else {
        //                         $('#productPrice').val(''); // Clear if no price is found
        //                     }
        //                 },
        //                 error: function() {
        //                     alert('Failed to fetch product details. Please try again.');
        //                 }
        //             });
        //         } else {
        //             // Clear the image and price if no product is selected
        //             $('#category_image').attr('src', '').hide();
        //             $('#productPrice').val('');
        //         }
        //     });
        // });

        $(document).ready(function() {
            // For adding new products
            $('#catnames').change(function() {
                var productId = $(this).val();
                fetchProductDetails(productId, '#category_image', '#productPrice');
            });

            // For updating products
            $('[id^=catnames1]').change(function() {
                var productId = $(this).val();
                var rowId = $(this).attr('id').replace('catnames1', ''); // Get the specific row ID
                fetchProductDetails(productId, '#category_image' + rowId, '#productPrice' + rowId);
            });

            function fetchProductDetails(productId, imageSelector, priceSelector) {
                if (productId) {
                    $(imageSelector).hide(); // Hide the image initially
                    $(priceSelector).val(''); // Clear the price field

                    $.ajax({
                        url: 'get_product_image.php', // Your PHP file
                        type: 'POST',
                        data: {
                            id: productId
                        },
                        beforeSend: function() {
                            $(imageSelector).attr('src', 'loading.gif'); // Optional loading image
                        },
                        success: function(response) {
                            var data = JSON.parse(response); // Parse the JSON response
                            if (data.image) {
                                $(imageSelector).attr('src', './uploads/' + data.image).show();
                            } else {
                                $(imageSelector).hide();
                            }
                            if (data.sale_price) {
                                $(priceSelector).val(data.sale_price); // Set the sale price in the input field
                            } else {
                                $(priceSelector).val(''); // Clear if no price is found
                            }
                        },
                        error: function() {
                            alert('Failed to fetch product details. Please try again.');
                        }
                    });
                } else {
                    // Clear the image and price if no product is selected
                    $(imageSelector).attr('src', '').hide();
                    $(priceSelector).val('');
                }
            }
        });


        function convertTo() {
            var qty = document.getElementById('qty').value; // Get quantity
            var price = document.getElementById('productPrice').value; // Get product price
            var totalInKHR = qty * price; // Calculate total

            // Update the total input field
            document.getElementById('total').value = totalInKHR.toFixed(2); // Set total value, formatted to 2 decimal places
        }
    </script>
</body>

</html>
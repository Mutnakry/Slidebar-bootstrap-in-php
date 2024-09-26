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
                        <th scope="col" class="px-6 py-3">Category</th>
                        <th scope="col" class="px-6 py-3">QTY</th>
                        <th scope="col" class="px-6 py-3">Sale Price</th>
                        <th scope="col" class="px-6 py-3">Originale Price</th>
                        <th scope="col" class="px-6 py-3">Profit</th>
                        <th scope="col" class="px-6 py-3">Discount</th>
                        <th scope="col" class="px-6 py-3">Expice</th>
                        <th scope="col" class="px-6 py-3">Image</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT pro.*,pro.id as proid,cat.names as cat_names FROM product as pro inner join categories as cat on pro.category_id = cat.id ORDER BY pro.id ASC";
                    $i = 1;
                    $query = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_array($query)) {
                    ?>
                        <tr>
                            <td class="px-6"><?php echo $i++; ?></td>
                            <td><?php echo $row['names']; ?></td>
                            <td><?php echo $row['cat_names']; ?></td>
                            <td><?php echo $row['qty']; ?></td>
                            <td><?php echo $row['sale_price']; ?></td>
                            <td><?php echo $row['original_price']; ?></td>
                            <td><?php echo $row['profit']; ?></td>
                            <td><?php echo $row['discount']; ?></td>
                            <td><?php echo $row['expice']; ?></td>
                            <td>
                                <img src="./uploads/<?php echo htmlspecialchars(basename($row['image']), ENT_QUOTES, 'UTF-8'); ?>"  class="rounded-circle" height="40" width="40">
                            </td>

                            <td class="space-x-4 flex">
                                <button class=" btn-primary border-0 rounded-circle" data-bs-toggle="modal" data-bs-target="#update<?php echo $row['proid']; ?>"><img src="./icon/edit.png" alt="" height="16"></button>
                                <button class="bg-red-400 border-0 btn-success rounded-circle" data-bs-toggle="modal" data-bs-target="#delete<?php echo $row['proid']; ?>"><img src="./icon/delete.png" alt="" height="16"></button>

                            </td>
                        </tr>
                        <!-- edit -->
                        <div class="modal fade" id="update<?php echo $row['proid']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <form action="./crud/update.php" method="POST" enctype="multipart/form-data">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title fs-3" id="staticBackdropLabel">កែប្រែទិន្ន័យ</h4> <img src="./icon//icons8-close-50.png" data-bs-dismiss="modal" aria-label="Close" height="24" alt="">
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="names">Name Product</label>
                                                        <input type="text" id="pronames" name="proid" hidden value="<?php echo $row['proid']; ?>" class="form-control" required>
                                                        <input type="text" id="pronames" name="pronames" value="<?php echo $row['names']; ?>" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="detail">Original Proce</label>
                                                        <input type="number" id="original_price" name="original_price" value="<?php echo $row['original_price']; ?>" value="0" min="0" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="names">QTY</label>
                                                        <input type="number" id="qty" name="qty"  min="1" value="<?php echo $row['qty']; ?>" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="detail">Discount</label>
                                                        <input type="number" id="discount" name="discount"  min="0" value="<?php echo $row['discount']; ?>" max="100" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="detail">Expice</label>
                                                        <input type="date" id="expice" name="expice" value="<?php echo $row['expice']; ?>" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="form-label">Name Category</label>
                                                        <select name="catnames" id="" class="form-control" required>
                                                            <option value="">-----------------Select Values-----------------</option>
                                                            <?php
                                                            $sql = "SELECT * FROM categories";
                                                            $qu = mysqli_query($conn, $sql);
                                                            while ($rows = mysqli_fetch_array($qu)) {
                                                                $selected = ($row['category_id'] == $rows['id']) ? 'selected' : ''; 
                                                                echo "<option value='" . $rows['id'] . "' " . $selected . ">" . $rows['names'] . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="detail">Sale price</label>
                                                        <input type="number" id="sale_price" name="sale_price" value="<?php echo $row['sale_price']; ?>" value="0" min="0" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="detail">Date</label>
                                                        <input type="date" id="detail" name="dob" value="<?php echo $row['dob']; ?>" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="image">Image</label>
                                                        <input type="file" id="image" name="image" class="form-control-file border">
                                                        <img src="./uploads/<?php echo htmlspecialchars(basename($row['image']), ENT_QUOTES, 'UTF-8'); ?>" alt="Category Image" class="rounded mt-2" height="80" width="80">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="saveproduct" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Delete Modal -->
                        <div class="modal fade" id="delete<?php echo $row['proid']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title fs-3" id="staticBackdropLabel">Did you really delete this data? <span class="text-danger"><?php echo $row['names']; ?></span></h4>
                                        <img src="./icon//icons8-close-50.png" data-bs-dismiss="modal" aria-label="Close" height="24" alt="">
                                    </div>
                                    <div class="modal-footer">
                                        <a type="button" data-bs-dismiss="modal" aria-label="Close" class="btn btn-primary">Cencel</a>
                                        <a href="./crud/delete.php?deleteproduct=<?php echo $row['proid']; ?>" class="btn btn-danger">Delete</a>
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
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-3" id="staticBackdropLabel">Add Product</h4>
                                    <img src="./icon/icons8-close-50.png" data-bs-dismiss="modal" aria-label="Close" height="24" alt="">
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="names">Name Product</label>
                                                <input type="text" id="pronames" name="pronames" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="detail">Original Proce</label>
                                                <input type="number" id="original_price" name="original_price" value="0" min="0" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="names">QTY</label>
                                                <input type="number" id="qty" name="qty" value="1" min="1" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="detail">Discount</label>
                                                <input type="number" id="discount" name="discount" value="0" min="0" max="100" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="detail">Expice</label>
                                                <input type="date" id="expice" name="expice" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="form-label">Name Category</label>
                                                <select name="catnames" id="" class="form-control" required>
                                                    <option value="">-----------------Select Values-----------------</option>
                                                    <?php
                                                    $sql = "SELECT * FROM categories";
                                                    $qu = mysqli_query($conn, $sql);
                                                    while ($rows = mysqli_fetch_array($qu)) {
                                                    ?>
                                                        <option value="<?php echo $rows['id'] ?>"><?php echo $rows['names'] ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="detail">Sale price</label>
                                                <input type="number" id="sale_price" name="sale_price" value="0" min="0" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="detail">Date</label>
                                                <input type="date" id="detail" name="dob" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="image">Image</label>
                                                <input type="file" id="image" name="image" class="form-control-file border">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="saveproduct" class="btn btn-primary">Save</button>
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
</body>

</html>
<?php
session_start();
include('./connection.php');

// Get the username from the session or set to 'Guest' if not logged in
$userName = isset($_SESSION['names']) ? $_SESSION['names'] : 'Guest';

if (isset($_POST['savecategory'])) {
    // Get the form data
    $names = $_POST['names'];
    $detail = $_POST['detail'];

    // Handle the file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $imageName = basename($image['name']);
        $imageTemp = $image['tmp_name'];
        $imageSize = $image['size'];
        $imageType = $image['type'];

        // Validate file type and size
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (in_array($imageType, $allowedTypes) && $imageSize <= $maxSize) {
            // Create the upload directory if it doesn't exist
            $targetDirectory = '../uploads/';
            if (!is_dir($targetDirectory)) {
                mkdir($targetDirectory, 0777, true);
            }

            // Create a unique name for the image and move it to the desired directory
            $targetFile = $targetDirectory . uniqid() . '_' . $imageName;

            if (move_uploaded_file($imageTemp, $targetFile)) {
                // Use a prepared statement to prevent SQL injection
                $stmt = $conn->prepare("INSERT INTO categories (names, detail, userNote, image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param('ssss', $names, $detail, $userName, $targetFile);

                if ($stmt->execute()) {
                    $_SESSION['success'] = "Category added successfully!";
                } else {
                    $_SESSION['error'] = "Failed to add category.";
                }

                $stmt->close();
            } else {
                $_SESSION['error'] = "Failed to move uploaded file.";
            }
        } else {
            $_SESSION['error'] = "Invalid file type or size.";
        }
    } else {
        $_SESSION['error'] = "No file uploaded or upload error.";
    }

    // Redirect to another page with a success or error message
    header('Location: ../category.php');
    exit;
}


// if (isset($_POST['saveproduct'])) {
//     // Get the form data
//     $pronames = $_POST['pronames'];
//     $catnames = $_POST['catnames'];
//     $discount = $_POST['discount'];
//     $expice = $_POST['expice'];
//     $original_price = $_POST['original_price'];
//     $qty = $_POST['qty'];
//     $sale_price = $_POST['sale_price'];
//     $dob = $_POST['dob'];
//     $profit = $_POST['sale_price'] - $_POST['original_price'];

//     // Handle the file upload
//     if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
//         $image = $_FILES['image'];
//         $imageName = basename($image['name']);
//         $imageTemp = $image['tmp_name'];
//         $imageSize = $image['size'];
//         $imageType = $image['type'];

//         // Validate file type and size
//         $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
//         $maxSize = 2 * 1024 * 1024; // 2MB

//         if (in_array($imageType, $allowedTypes) && $imageSize <= $maxSize) {
//             // Create the upload directory if it doesn't exist
//             $targetDirectory = '../uploads/';
//             if (!is_dir($targetDirectory)) {
//                 mkdir($targetDirectory, 0777, true);
//             }
//             $targetFile = $targetDirectory . uniqid() . '_' . $imageName;
//             if (move_uploaded_file($imageTemp, $targetFile)) {
//                 $stmt = $conn->prepare("INSERT INTO product (`names`, `expice`, `category_id`, `qty`, `original_price`, `sale_price`, `profit`, `image`, `dob`, `userNote`, `discount`) 
//                                        VALUES ( '$pronames','$expice','$catnames','$qty','$original_price',$sale_price,'$profit','$targetFile','$dob','$userName','$discount')");
//                 if ($stmt->execute()) {
//                     $_SESSION['success'] = "Category added successfully!";
//                 } else {
//                     $_SESSION['error'] = "Failed to add category.";
//                 }
//                 $stmt->close();
//             } else {
//                 $_SESSION['error'] = "Failed to move uploaded file.";
//             }
//         } else {
//             $_SESSION['error'] = "Invalid file type or size.";
//         }
//     } else {
//         $_SESSION['error'] = "No file uploaded or upload error.";
//     }

//     // Redirect to another page with a success or error message
//     header('Location: ../product.php');
//     exit;
// }

/// save product

if (isset($_POST['saveproduct'])) {
    // Get the form data
    $pronames = $_POST['pronames'];
    $catnames = $_POST['catnames'];
    $discount = $_POST['discount'];
    $expice = $_POST['expice'];
    $original_price = $_POST['original_price'];
    $qty = $_POST['qty'];
    $sale_price = $_POST['sale_price'];
    $dob = $_POST['dob'];
    $profit = $_POST['sale_price'] - $_POST['original_price'];

    // Initialize variables for image handling
    $targetFile = null; // Set to null initially

    // Handle the file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $imageName = basename($image['name']);
        $imageTemp = $image['tmp_name'];
        $imageSize = $image['size'];
        $imageType = $image['type'];

        // Validate file type and size
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (in_array($imageType, $allowedTypes) && $imageSize <= $maxSize) {
            // Create the upload directory if it doesn't exist
            $targetDirectory = '../uploads/';
            if (!is_dir($targetDirectory)) {
                mkdir($targetDirectory, 0777, true);
            }
            $targetFile = $targetDirectory . uniqid() . '_' . $imageName;
            if (!move_uploaded_file($imageTemp, $targetFile)) {
                $_SESSION['error'] = "Failed to move uploaded file.";
                header('Location: ../product.php');
                exit;
            }
        } else {
            $_SESSION['error'] = "Invalid file type or size.";
            header('Location: ../product.php');
            exit;
        }
    }
    if ($targetFile) {
        $stmt = $conn->prepare("INSERT INTO product (`names`, `expice`, `category_id`, `qty`, `original_price`, `sale_price`, `profit`, `image`, `dob`, `userNote`, `discount`) 
                                VALUES ('$pronames','$expice','$catnames','$qty','$original_price','$sale_price','$profit','$targetFile','$dob','$userName','$discount')");
    } else {
        $stmt = $conn->prepare("INSERT INTO product (`names`, `expice`, `category_id`, `qty`, `original_price`, `sale_price`, `profit`, `dob`, `userNote`, `discount`) 
                                VALUES ('$pronames','$expice','$catnames','$qty','$original_price','$sale_price','$profit','$dob','$userName','$discount')");
    }
    if ($stmt->execute()) {
        $_SESSION['success'] = "Product added successfully!";
    } else {
        $_SESSION['error'] = "Failed to add product.";
    }

    $stmt->close();
    header('Location: ../product.php');
    exit;
}

// dave purchase
if (isset($_POST['saveProduct'])) {
    // Get the form data
    $pronames = $_POST['productnames'];
    $qty = $_POST['qty'];
    $price = $_POST['price'];
    $total = $qty * $price;
    $expice = $_POST['expiry'];

    $sql = "INSERT INTO purchase (product_id,qty,price,total,expiry) VALUES (' $pronames','$qty',' $price','$total','$expice')";
     // Execute the query
     if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Product added successfully!";
    } else {
        $_SESSION['error'] = "Failed to add product." . mysqli_error($conn);
    }
    header('Location: ../purchase.php');
}
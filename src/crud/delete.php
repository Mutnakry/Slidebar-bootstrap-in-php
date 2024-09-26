<?php
session_start(); // Start the session
// connect to database
// $conn = mysqli_connect('localhost', 'root', '', 'pos_minimart');

// Get the username from the session or set to 'Guest' if not logged in
$userName = isset($_SESSION['names']) ? $_SESSION['names'] : 'Guest';

include('./connection.php');

// Delete category and its image
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Retrieve the image path from the database
    $query = "SELECT image FROM categories WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $imagePath = $row['image'];

    // Delete the category from the database
    $query = "DELETE FROM categories WHERE id = $id";
    $result = mysqli_query($conn, $query);
    if ($result) {
        // Delete the image file from the server
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        $_SESSION['success'] = "Category deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete category.";
    }

    // Redirect to another page with a success or error message
    header('Location: ../category.php');
    exit;
}


// Delete category and its image
if (isset($_GET['deleteproduct'])) {
    $id = $_GET['deleteproduct'];

    // Retrieve the image path from the database
    $query = "SELECT image FROM product WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $imagePath = $row['image'];

    // Delete the category from the database
    $query = "DELETE FROM product WHERE id = $id";
    $result = mysqli_query($conn, $query);
    if ($result) {
        // Delete the image file from the server
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        $_SESSION['success'] = "Category deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete category.";
    }

    // Redirect to another page with a success or error message
    header('Location: ../product.php');
    exit;
}


// Delete category
if (isset($_GET['deletepurchase'])) {
    $id = $_GET['deletepurchase'];

    // Delete the category from the database
    $deleteQuery = "DELETE FROM purchase WHERE id = $id";
    if (mysqli_query($conn, $deleteQuery)) {
        $_SESSION['success'] = "Category deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete category: " . mysqli_error($conn);
    }

    // Redirect to another page with a success or error message
    header('Location: ../purchase.php');
    exit;
}

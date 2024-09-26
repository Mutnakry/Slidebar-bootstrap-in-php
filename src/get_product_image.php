<?php
include "../connection.php";

// if (isset($_POST['id'])) {
//     $productId = $_POST['id'];

//     // Fetch the product image based on the selected product ID
//     $sql = "SELECT * FROM product WHERE id = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("i", $productId);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($row = $result->fetch_assoc()) {
//         // Output the image name/path
//         echo htmlspecialchars(basename($row['image']), ENT_QUOTES, 'UTF-8');
        
//     } else {
//        echo json_encode(['image' => '', 'price' => '']);
//     }

//     $stmt->close();
//     $conn->close();
// }

?>
<?php
include "../connection.php";

if (isset($_POST['id'])) {
    $productId = $_POST['id'];

    // Fetch the product image and sale price based on the selected product ID
    $sql = "SELECT image, sale_price FROM product WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            'image' => htmlspecialchars(basename($row['image']), ENT_QUOTES, 'UTF-8'),
            'sale_price' => htmlspecialchars($row['sale_price'], ENT_QUOTES, 'UTF-8')
        ]);
    } else {
        echo json_encode(['image' => '', 'sale_price' => '']);
    }

    $stmt->close();
    $conn->close();
}
?>





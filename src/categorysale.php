<?php
include_once "includes/header.php";
include "../connection.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <style>
        .scrolling-wrapper {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            padding: 2px;
        }
        .scrolling-wrapper::-webkit-scrollbar{
            width: 0;
        }
    </style>
</head>

<body>
    <div class="">
        <div class="row">
            <div class="col-lg-12">
                <div class="row scrolling-wrapper">
                    <?php
                    $sql = "SELECT * FROM categories ORDER BY id DESC";
                    $query = mysqli_query($conn, $sql);
                    while ($rows = mysqli_fetch_array($query)) {
                    ?>
                        <tr class="row overflow-x-scroll wrapper">
                            <a href="?idcat=<?php echo $rows['id']; ?>">
                                <div class="card mx-2" style="cursor: pointer;">
                                    <img src="./uploads/<?php echo htmlspecialchars(basename($rows['image']), ENT_QUOTES, 'UTF-8'); ?>" alt="Category Image" height="60" width="80">
                                    <div class="badge text-bg-primary text-wrap">
                                        <h5 class=""><?php echo $rows["names"] ?></h5>
                                    </div>
                                </div>
                            </a>
                        </tr>
                    <?php
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>
    <script src="../assets/js/dashboard.js"></script>
</body>

</html>
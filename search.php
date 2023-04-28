<?php 
session_start();

# If search key is not set or empty
if (!isset($_GET['key']) || empty($_GET['key'])){
    header("Location: index.php");
    exit;
}
$key = $_GET['key'];
if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];
}
# Database Connection File
include "db_conn.php";

# Book helper function
include "php/func-book.php";
$books = search_books($conn, $key);

// # authors helper function
// include "php/func-author.php";
// $authors = get_all_author($conn);

# Category helper function
include "php/func-category.php";
$categories = get_all_categories($conn);

include "php/func-discount.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./font/fontawesome-free-6.2.0-web/fontawesome-free-6.2.0-web/css/all.min.css">

    <title>Book Store</title>
</head>
<body>
        <nav class="navbar navbar-expand-lg bg-light" style="padding: 0 7%; width: 100%; border-radius: 0;">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Online Book Store</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent" style="font-size: 20px;">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Store</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">About</a>
                        </li>
                        <li class="nav-item">
                            <?php if (isset($_SESSION['username'])&&$role=='staff') {?>
                                <a class="nav-link" href="admin.php">Admin</a>
                            <?php }else if (isset($_SESSION['username'])&&$role=='customer'){ ?>
                                <a class="nav-link" href="logout.php">Logout</a>
                            <?php } else { ?>
                                <a class="nav-link" href="login.php">Login</a>
                            <?php } ?>
                        </li>
                        <?php if (isset($_SESSION['role'])&&$role=='customer') { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="add-cart.php">
                                <i style="font-weight:700; margin-left: 100%;" class="fa-solid fa-cart-shopping"></i>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav><br>
    <div class="container">
        Search result for <b><?=$key?></b>

        <div class="d-flex pt-3">
            <?php if ($books == 0){ ?>
                <div class="alert alert-warning p-5 text-center pdf-list" 
                 role="alert">
                 <img src="img/nothing-icon.jpg" 
                      width="100">
                <br>
                The key <b>"<?=$key?>"</b> didn't match to any record in the database
            </div>
            <?php }else{?>
                <div class="pdf-list d-flex flex-wrap">
                    <?php foreach ($books as $book) { ?>
                        <form action="add-cart.php?add=<?=$book['BookID']?>" method="POST">
                        <div class="card m-1">
                            <img src="uploads/cover/<?=$book['cover']?>"
                                class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title"><?=$book['Title']?></h5>
                                <p class="card-text">
                                    <i><b>By: <?=$book['Author']?><br></b></i>
                                    <?=$book['des']?><br>
                                    <i><b>Category: <?=get_category_by_BookID($conn,$book['BookID'])['C_Name']?><br></b></i>
                                </p>
                                <p style="font-size: 20px;">
                                        <b>$ <?=$book['List_price']?></b>
                                    </p>
                                    <?php if (get_dis_by_book($conn, $book['BookID'])){?>
                                        <p style="font-size: 20px;">
                                            <b style="color: red;">Sale <?=get_dis_by_book($conn, $book['BookID'])['Percents']*100?>%</b>
                                        </p> 
                                    <?php } ?>
                                <a href="uploads/cover/<?=$book['cover']?>"
                                class="btn btn-success" style="width: 49%;">Open</a>

                                <a href="uploads/cover/<?=$book['cover']?>"
                                class="btn btn-primary" style="width: 49%;"
                                download=<?=$book['Title']?>>Download</a>
                                <br>
                                    <?php if(isset($_SESSION['role'])&&$role=='staff') {?>
                                        <!-- Do nothing -->
                                    <?php } else {?>
                                        <input type="text"
                                            name="bookid"
                                            value="<?=$book['BookID']?>"
                                            hidden>
                                        <button type="submit" class="btn btn-info mt-1 w-100">Add to cart</button>
                                    <?php } ?>
                            </div>
                        </div>
                        </form>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
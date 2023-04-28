<?php 
session_start();

# Database Connection File
include "db_conn.php";
// $username = $_SESSION['username'];
# Book helper function
include "php/func-book.php";
$books = get_all_books($conn);
// $booksincart = get_book_cart($conn, $username);

# authors helper function
include "php/func-author.php";
$authors = get_all_author($conn);

# Category helper function
include "php/func-category.php";
$categories = get_all_categories($conn);

// if(isset($_GET['role'])){
//     $role = $_GET['role'];
// }
include "php/func-discount.php";


if(isset($_SESSION['role'])){
    $role=$_SESSION['role'];
}else {
    $role='';
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./font/fontawesome-free-6.2.0-web/fontawesome-free-6.2.0-web/css/all.min.css">

    <title>Book Store</title>
</head>
<body>
    <div>
        <nav class="navbar navbar-expand-lg bg-light" style="padding: 0 7%; position: fixed; z-index: 999; width: 100%; border-radius: 0;">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php" style="font-size: 20px;">Online Book Store</a>
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
                        <?php if ($role=='customer') { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="add-cart.php">
                                <i style="font-weight:700;" class="fa-solid fa-cart-shopping"></i>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner" style="max-height: 500px;">
                <div class="carousel-item active">
                    <img src="img/library.jpg" class="d-block w-100 ">
                </div>
                <div class="carousel-item">
                    <img src="img/library2.jpg" class="d-block w-100 ">
                </div>
                <div class="carousel-item">
                    <img src="img/lb3.jfif" class="d-block w-100 ">
                </div>
                <div class="carousel-item">
                    <img src="img/lb4.jpeg" class="d-block w-100 ">
                </div>
            </div>
            <!-- <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button> -->
        </div>
    <div class="container">

        <form action="search.php"
              method="GET"
              style="width: 100%;">

            <div class="input-group my-5">
                <input type="text" 
                       class="form-control" 
                       name="key"
                       placeholder="Search Book..." 
                       aria-label="Search Book..." 
                       aria-describedby="basic-addon2">

                <button class="input-group-text btn btn-primary" id="basic-addon2">
                    <img src="img/search.png"
                         width="20">
                </button>
            </div>
        </form>
        <div class="d-flex pt-3">
            <?php if ($books == 0){ ?>
            <div class="alert alert-warning p-5 text-center" 
                 role="alert">
                 <img src="img/nothing-icon.jpg" 
                      width="100">
                <br>
                There is no book in the database
            </div>
            
            <?php }else{?>
                <div class="pdf-list d-flex flex-wrap">
                    <?php foreach ($books as $book) { ?>
                        <form action="add-cart.php?add=<?=$book['BookID']?>" method="POST">
                            <div class="card m-1" style="min-height: 800px;">
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
                                            <b style="color: red;">Sale off <?=get_dis_by_book($conn, $book['BookID'])['Percents']*100?>%</b>
                                        </p> 
                                    <?php } ?>
                                    <div style=" bottom: 0;">
                                    <a href="uploads/cover/<?=$book['cover']?>"
                                    class="btn btn-success" style="width: 49%;">Open</a>

                                    <a href="uploads/cover/<?=$book['cover']?>"
                                    class="btn btn-primary" style="width: 49%;"
                                    download=<?=$book['Title']?>>Download</a>
                                    <br>
                                    <?php if($role=='staff') {?>
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
                            </div>
                        </form>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="category">
                <!-- List of categories -->
                <div class="list-group">
                    <?php if ($categories==0){
                        // Do nothing
                    }else{ ?>
                    <a href="#"
                       class="list-group-item list-group-item-action active"
                       >Category</a>
                    <?php foreach ($categories as $category) {?>
                        <a href="category.php?id=<?=$category['ID']?>"
                           class="list-group-item list-group-item-action"
                           ><?=$category['C_Name']?></a>
                    <?php } } ?>
                </div>

                <!-- List of authors -->
                <div class="list-group mt-5">
                    <?php if ($authors==0){
                        // Do nothing
                    }else{ ?>
                    <a href="#"
                       class="list-group-item list-group-item-action active"
                       >Author</a>
                    <?php foreach ($authors as $author) {?>
                        <a href="author.php?author=<?=$author['Author']?>"
                           class="list-group-item list-group-item-action"
                           ><?=$author['Author']?></a>
                    <?php } } ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
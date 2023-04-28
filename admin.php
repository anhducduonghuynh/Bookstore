<?php 
session_start();

#If the admin is logged in
if (isset($_SESSION['username'])&&
    isset($_SESSION['password'])){

    # Database Connection File
    include "db_conn.php";

    # Book helper function
    include "php/func-book.php";
    $books = get_all_books($conn);
    
    // # authors helper function
    // include "php/func-author.php";
    // $authors = get_all_author($conn);

    # Category helper function
    include "php/func-category.php";
    $categories = get_all_categories($conn);

    include "php/func-discount.php";
    $discounts = get_all_discount($conn);

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
    <title>ADMIN</title>
</head>
<body>
        <nav class="navbar navbar-expand-lg bg-light" style="padding: 0 7%; width: 100%; border-radius: 0;">
            <div class="container-fluid">
                <a class="navbar-brand" href="admin.php">Admin</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent" style="font-size: 20px;">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="index.php?role=staff">Store</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add-book.php">Add Book</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add-category.php">Add Category</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add-program.php">Add Discount Program</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
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
        <?php if(isset($_GET['success'])){ ?>
            <div class="alert alert-success" 
                 role="alert">
                <?=htmlspecialchars($_GET['success']); ?>
            </div>
        <?php } ?>

        <?php if ($books == 0) { ?>
            <div class="alert alert-warning p-5 text-center" 
                 role="alert">
                 <img src="img/nothing-icon.jpg" 
                      width="100">
                <br>
                There is no book in the database
            </div>
        <?php }else {?>

            <!-- List of all books -->
            <h4 class="mt-5">All Books</h4>
            <table class="table table-bordered shadow">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Publication</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>List Price</th>
                        <th>Discount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $i = 0;
                        foreach ($books as $book) {
                            $i++;
                        ?>
                        <tr>
                            <td><?=$i?></td>
                            <td>
                                <img width="100"
                                     src="uploads/cover/<?=$book['cover']?>" alt="no image">
                                <p><b><?=$book['Title']?></b></p>
                                <i>Author: <?=$book['Author']?></i>
                            </td>
                            <td>
                                <?= $book['Publisher']?>-<?= $book['Year_publication']?>
                            </td>
                            <td style="max-width: 1180px"><?=$book['des']?></td>
                            <td>
                                <?php 
                                    if($categories == 0){
                                        echo "Undefined";}
                                    else{
                                        $BookID=$book['BookID'];
                                        $sql = "SELECT C_Name, BookID 
                                                FROM BELONG 
                                                INNER JOIN categories 
                                                ON BELONG.ID = categories.ID
                                                WHERE BELONG.BOOKID = $BookID";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $result = $stmt->fetch();
                                        echo ($result['C_Name']);
                                    }
                                ?>
                            </td>
                            <td style="min-width: 100px;">
                                $ <?=$book['List_price']?>
                            </td>
                            <td>
                                    <?php
                                    if($discounts == 0){?>
                                        <!-- DO notthing -->
                                    <?php }else{
                                        $BookID=$book['BookID'];
                                        $sql = "SELECT D_Name
                                                FROM dis_program 
                                                JOIN applies
                                                ON dis_program.ID = applies.ID
                                                WHERE applies.BOOKID = $BookID";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $results = $stmt->fetchAll();
                                        foreach ($results as $result) {?>
                                            <?= $result['D_Name']?> <br>
                                        <?php } ?>
                                    <?php } ?>
                            </td>
                            <td>
                                <a href="edit-book.php?id=<?=$book['BookID']?>" 
                                class="btn btn-warning">
                                Edit</a>
                                <a href="php/delete-book.php?id=<?=$book['BookID']?>" 
                                class="btn btn-danger">
                                Delete</a>
                                <a href="add-discount.php?id=<?=$book['BookID']?>" 
                                class="btn btn-info mt-1" style="width: 90%">
                                Add discount</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php }?>

        <?php if ($categories == 0) { ?>
            empty
        <?php }else {?>

            <!-- List of all books -->
            <h4 class="mt-5">All Category</h4>
            <table class="table table-bordered shadow">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Number</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $i = 0;
                        foreach ($categories as $category) {
                            $i++;
                        ?>
                        <tr>
                            <td><?=$i?></td>
                            <td>
                                <?= $category['C_Name']?>
                            </td>
                            <td>
                                <?php
                                    $category_ID=$category['ID'];
                                    $sql = "SELECT TOTAL_BOOK('$category_ID') a";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $result = $stmt->fetch();
                                    echo ($result['a']);
                                ?>
                            </td>
                            <td>
                                <a href="edit-category.php?id=<?=$category['ID']?>" 
                                class="btn btn-warning">
                                Edit</a>
                                <a href="#" 
                                class="btn btn-danger">
                                Delete</a>
                            </td>
                            
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php }?>
    </div>
</body>
</html>

<?php }else{
    header("Location: login.php");
    exit;
} ?>
<?php 
session_start();

#If the admin is logged in
if (isset($_SESSION['username'])&&
    isset($_SESSION['password'])){

    # If book ID is not set
    if (!isset($_GET['id'])){
        # Redirect to admin.php page
        header("Location: admin.php");
        exit;
    }

    $id = $_GET['id'];

    # Database Connection File
    include "db_conn.php";

    # Book helper function
    include "php/func-book.php";
    $book = get_book($conn, $id);

    # If the ID is invalid
    if($book == 0){
        # Redirect to admin.php page
        header("Location: admin.php");
        exit;
    }

    # Category helper function
    include "php/func-category.php";
    $categories = get_all_categories($conn);
    $category_current_Book = get_category_by_BookID($conn, $id);

    # authors helper function
    // include "php/func-author.php";
    // $authors = get_all_author($conn);

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
    <title>Edit Book</title>
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="admin.php">Admin</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="index.php">Store</a>
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
        <form action="php/edit-book.php"
              method="POST"
              enctype="multipart/form-data"
              class="shadow p-4 rounded mt-5"
              style="windows: 90%; max-width: 50rem; margin:auto">
            <h1 class="text-center pb-5 display-4 fs-3">
                Edit Book
            </h1>
            <?php if (isset($_GET['error'])){ ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($_GET['error']); ?>
            </div>
            <?php } ?>
            <?php if (isset($_GET['success'])){ ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($_GET['success']); ?>
            </div>
            <?php } ?>
            <div class="mb-3">
                <label class="form-label">Book ID</label>
                <input type="text" 
                       class="form-control" 
                       value="<?=$book['BookID']?>"
                       name="book_id">
            </div>

            <div class="mb-3">
                <label class="form-label">Book Title</label>
                <input type="text" 
                       hidden 
                       value="<?=$book['BookID']?>"
                       name="book_id">
                
                <input type="text" 
                       class="form-control" 
                       value="<?=$book['Title']?>"
                       name="book_title">
            </div>

            <div class="mb-3">
                <label class="form-label">Year Publication</label>
                <input type="text" 
                       class="form-control" 
                       value="<?=$book['Year_publication']?>"
                       name="book_publication">
            </div>

            <div class="mb-3">
                <label class="form-label">Publisher</label>
                <input type="text" 
                       class="form-control" 
                       value="<?=$book['Publisher']?>"
                       name="book_publisher">
            </div>

            <div class="mb-3">
                <label class="form-label">Book Description</label>
                <input type="text" 
                       class="form-control" 
                       value="<?=$book['des']?>"
                       name="book_description">
            </div>

            <div class="mb-3">
                <label class="form-label">Book Author</label>
                <input type="text" 
                       class="form-control" 
                       value="<?=$book['Author']?>"
                       name="book_author">
            </div>

            <div class="mb-3">
                <label class="form-label">List Price</label>
                <input type="text" 
                       class="form-control" 
                       value="<?=$book['List_price']?>"
                       name="book_price">
            </div>

            <div class="mb-3">
                <label class="form-label">Book Category</label>
                <select name="book_category"
                        class="form-control">
                        <option value="">
                            Select category
                        </option>
                        <?php 
                        if ($categories == 0) {
                            # Do nothing
                        }else{
                        foreach ($categories as $category) { 
                            if($category_current_Book['ID'] == $category['ID']){
                            ?>
                            <option 
                                selected
                                value="<?= $category['C_Name']?>">
                                <?= $category['C_Name']?>
                            </option>
                            <?php }else{ ?>
                            <option 
                                value="<?= $category['C_Name']?>">
                                <?= $category['C_Name']?>
                            </option>
                        <?php } } }?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Book Cover</label>
                <input type="file" 
                       class="form-control" 
                       name="book_cover">
                    
                <input type="text" 
                       hidden 
                       value="<?=$book['cover']?>"
                       name="current_cover">
                <a href="uploads/cover/<?=$book['cover']?>"
                   class="link-dark">Current Cover</a>
            </div>

            <button type="submit" 
                    class="btn btn-primary">
                    Update</button>
        </form>
    </div>
</body>
</html>

<?php }else{
    header("Location: login.php");
    exit;
} ?>
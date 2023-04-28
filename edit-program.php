<?php 
session_start();

#If the admin is logged in
if (isset($_SESSION['user_id'])&&
    isset($_SESSION['user_email'])){

    # If author ID is not set
    if (!isset($_GET['id'])){
        # Redirect to admin.php page
        header("Location: admin.php");
        exit;
    }

    $id = $_GET['id'];

    # Database Connection File
    include "db_conn.php";

    # authors helper function
    include "php/func-author.php";
    $author = get_author($conn, $id);

    # If the ID is invalid
    if($author == 0){
        # Redirect to admin.php page
        header("Location: admin.php");
        exit;
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
    <title>Edit Author</title>
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
                            <a class="nav-link" href="add-author.php">Add Author</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <form action="php/edit-author.php"
              method="POST"
              class="shadow p-4 rounded mt-5"
              style="windows: 90%; max-width: 50rem;">
            <h1 class="text-center pb-5 display-4 fs-3">
                Edit Author
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
                <label class="form-label">Author Name</label>
                <input type="text" 
                       value="<?=$author['id']?>"
                       hidden
                       name="author_id">

                <input type="text" 
                       class="form-control" 
                       value="<?=$author['name']?>"
                       name="author_name">
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
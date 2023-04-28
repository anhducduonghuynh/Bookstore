<?php 
session_start();

#If the admin is logged in
if (isset($_SESSION['username'])&&
    isset($_SESSION['password'])){

    # Database Connection File
    include "../db_conn.php";

    # func-book helper
    include "func-book.php";

    // check if the book ID set
    if (isset($_GET['id'])) {
        // Get data form GET request
        // and store it in var
        $id = $_GET['id'];

        #simple form validation
        if (empty($id)) {
            $em = "Error Occurred!";
            header("Location: ../admin.php?error=$em");
            exit;
        }else {
            # delete the current book_cover
            $book = get_book($conn, $id);
            $cover = $book['cover'];
            $c_b_c = "../uploads/cover/$cover";
            unlink($c_b_c);


            # Delete the book from Database
            $sql = "CALL DELETE_BOOK('$id')";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $sm = "Successfully removed!";
            header("Location: ../admin.php?success=$sm");
            exit;
        }
    }else {
        header("Location: ../admin.php");
        exit;
    }



}else{
    header("Location: ../login.php");
    exit;
} ?>
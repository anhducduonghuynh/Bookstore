<?php 
session_start();

#If the admin is logged in
if (isset($_SESSION['username'])&&
    isset($_SESSION['password'])){

    # Database Connection File
    include "../db_conn.php";
    
    // check if category name is submitted
    
        // Get data form POST request
        // and store it in var
        $username = $_SESSION['username'];
        $sql = "CALL DELETE_ALL ('$username')";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $sm = 'Successfully pay';
        header("Location: ../add-cart.php?success=$sm");
        exit;

        // #simple form validation
        // if (empty($name)) {
        //     $em = "The category name is required";
        //     header("Location: ../edit-category.php?error=$em&id=$id");
        //     exit;
        // }else {
        //     $sql = "SELECT Category_exist ('$name') a";
        //     $stmt = $conn->prepare($sql);
        //     $stmt->execute();
        //     $res = $stmt->fetch();

        //     # Update the Database

        //     if($res['a']){
        //         $em = "Category is already exist in database";
        //         header("Location: ../edit-category.php?error=$em&id=$id");
        //         exit;
        //     }else {
        //         $sql = "CALL UPDATE_CATEGORY('$id', '$name')";
        //         $stmt = $conn->prepare($sql);
        //         $stmt->execute();
        //         # Success message
        //         $sm = "Successfully updated!";
        //         header("Location: ../edit-category.php?success=$sm&id=$id");
        //         exit;
        //     }
            /**
             * If there is no error while 
             * updating the data
             */
            // if($res){
            //     # Success message
            //     $sm = "Successfully updated!";
            //     header("Location: ../edit-category.php?success=$sm&id=$id");
            //     exit;
            // }else {
            //     # Error message
            //     $em = "Unknown Error Occured!";
            //     header("Location: ../edit-category.php?error=$em&id=$id");
            //     exit;
            // }
    //     }
    // }else {
    //     header("Location: ../admin.php");
    //     exit;
    // }
}else{
    header("Location: ../login.php");
    exit;
} ?>
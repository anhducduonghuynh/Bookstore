<?php 
session_start();

#If the admin is logged in
if (isset($_SESSION['username'])&&
    isset($_SESSION['password'])){

    # Database Connection File
    include "../db_conn.php";

    // check if category name is submitted
    if (isset($_POST['category_name'])) {
        // Get data form POST request
        // and store it in var
        $name = $_POST['category_name'];

        #simple form validation
        if (empty($name)) {
            $em = "The category name is required";
            header("Location: ../add-category.php?error=$em");
            exit;
        }else {
            # Check if category existed?
            $sql = "SELECT Category_exist('$name') a";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $res = $stmt->fetch();
            if($res['a']){
                # Error message
                $em = "This category is existed";
                header("Location: ../add-category.php?error=$em");
                exit;
            }else{
                # Insert Into Database
                $sql = "CALL ADD_CATEGORY('$name')";
                // $sql = "INSERT INTO categories (name)
                //         VALUES (?)";
                $stmt = $conn->prepare($sql);
                $res = $stmt->execute();
                # Success message
                $sm = "Successfully created!";
                header("Location: ../add-category.php?success=$sm");
                exit;
            }
            // if($res){
            //     # Success message
            //     $sm = "Successfully created!";
            //     header("Location: ../add-category.php?success=$sm");
            //     exit;
            // }else {
            //     # Error message
            //     $em = "Unknown Error Occured!";
            //     header("Location: ../add-category.php?error=$em");
            //     exit;
            // }
        }
    }else {
        header("Location: ../admin.php");
        exit;
    }



}else{
    header("Location: ../login.php");
    exit;
} ?>
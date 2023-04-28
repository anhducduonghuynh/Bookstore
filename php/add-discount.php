<?php 
session_start();

#If the admin is logged in
if (isset($_SESSION['username'])&&
    isset($_SESSION['password'])){

    # Database Connection File
    include "../db_conn.php";
    $bookid = $_GET['bookid'];
    include "func-discount.php";
    $current_apply = get_dis_by_book($conn, $bookid);
    // check if category name is submitted
    if (isset($_POST['discountID']) &&
        isset($_POST['startdate']) &&
        isset($_POST['enddate'])) {
        // Get data form POST request
        // and store it in var
        $discountid = $_POST['discountID'];
        echo ($discountid);
        $startdate = $_POST['startdate'];
        $enddate = $_POST['enddate'];

        #simple form validation
        if ($discountid=="") {
            $em = "The discount program is required";
            header("Location: ../add-discount.php?error=$em");
            exit;
        }else if (empty($startdate)){
            $em = "The start date is required";
            header("Location: ../add-discount.php?error=$em");
            exit;
        }else if (empty($enddate)){
            $em = "The end date is required";
            header("Location: ../add-discount.php?error=$em&id=$bookid");
            exit;
        }else{
            # Check if category existed?
            if($current_apply==0){
                $sql = "CALL ADD_APPLY('$discountid','$bookid','$startdate','$enddate')";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $sm = "Successfully applied!";
                header("Location: ../add-discount.php?success=$sm&id=$bookid");
                exit;
            } else {
                $sql = "CALL DELETE_APPLY('$current_apply[ID]', '$bookid')";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                
                $sql = "CALL ADD_APPLY('$discountid', '$bookid', '$startdate', '$enddate')";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $sm = "Successfully applied!";
                header("Location: ../add-discount.php?success=$sm&id=$bookid");
                exit;
            }
            // $sql = "SELECT Apply_exist('$discountid', '$bookid') a";
            // $stmt = $conn->prepare($sql);
            // $stmt->execute();
            // $res = $stmt->fetch();
            // if($res['a']){
            //     # Error message
            //     $em = "This discount program is already applied";
            //     header("Location: ../add-category.php?error=$em");
            //     exit;
            // }else{
            //     # Insert Into Database
            //     $sql = "CALL ADD_CATEGORY('$name')";
            //     // $sql = "INSERT INTO categories (name)
            //     //         VALUES (?)";
            //     $stmt = $conn->prepare($sql);
            //     $res = $stmt->execute();
            //     # Success message
            //     $sm = "Successfully created!";
            //     header("Location: ../add-category.php?success=$sm");
            //     exit;
            // }
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
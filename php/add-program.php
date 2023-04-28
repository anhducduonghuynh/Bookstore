<?php 
session_start();

#If the admin is logged in
if (isset($_SESSION['username'])&&
    isset($_SESSION['password'])){

    # Database Connection File
    include "../db_conn.php";

    // check if author name is submitted
    if (isset($_POST['program']) &&
        isset($_POST['description']) &&
        isset($_POST['percent'])) {
        // Get data form POST request
        // and store it in var
        $program = $_POST['program'];
        $description = $_POST['description'];
        $percent = $_POST['percent'];

        #simple form validation
        if (empty($program)) {
            $em = "The discount program name is required";
            header("Location: ../add-program.php?error=$em");
            exit;
        } else if (empty($description)){
            $em = "The description is required";
            header("Location: ../add-program.php?error=$em");
            exit;
        } else if (empty($percent)) {
            $em = "The percent is required";
            header("Location: ../add-program.php?error=$em");
            exit;
        }else {
            # Insert Into Database
            $sql = "SELECT Program_exist('$program') a";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $res = $stmt->fetch();
            $res = $res['a'];
            /**
             * If there is no error while 
             * inserting the data
             */
            if($res){
                # Error message
                $em = "The program is already existed";
                header("Location: ../add-program.php?error=$em");
                exit;
            }else {
                $sql = "CALL ADD_PROGRAM('$program', '$description', '$percent')";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                # Success message
                $sm = "Successfully added!";
                header("Location: ../add-program.php?success=$sm");
                exit;
            }
        }
    }else {
        header("Location: ../admin.php");
        exit;
    }



}else{
    header("Location: ../login.php");
    exit;
} ?>
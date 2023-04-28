<?php
session_start();

if (isset($_POST['username']) && 
    isset($_POST['password'])) {

    #Database Connection File
    include "../db_conn.php";

    #validation helper function
    include "func-validation.php";
    
    /**Get data from POST request
       and store them in var
    **/

    $username = $_POST['username'];
    $password = $_POST['password'];

    # simple form validation
    $text = "Email";
    $location = "../login.php";
    $ms = "error";
    is_empty($username, $text, $location, $ms, "");

    $text = "Passsword";
    $location = "../login.php";
    $ms = "error";
    is_empty($password, $text, $location, $ms, "");

    # call function LOGIN_CUSTOMER(Username, Password)
    $sql = "SELECT LOGIN_CUSTOMER ('$username', '$password') a";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $result = $result['a'];
    if($result){ // login for customer
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        $_SESSION['role'] = 'customer';
        header("Location: ../index.php");
        exit;
    }
    else{
        $sql = "SELECT LOGIN_STAFF('$username','$password') a";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        $result = $result['a'];
        if($result){ // login for staff
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            $_SESSION['role'] = 'staff';
            header("Location: ../admin.php");
        }else{
            $em = "Incorrect User name or password";
            header("Location: ../login.php?error=$em");
            $_SESSION['role'] = '';
        }
    }
    // $result = mysqli_query($conn, $sql);
    
    # search for the email
//     $sql = "SELECT * FROM admin 
//             WHERE email=?";
//     $stmt = $conn->prepare($sql);
//     $stmt->execute([$email]);

//     # if the email is exist
//     if($stmt->rowCount()===1){
//         $user = $stmt->fetch();

//         $user_id = $user['id'];
//         $user_email = $user['email'];
//         $user_password = $user['password'];
//         if($email === $user_email) {
//             if(password_verify($password, $user_password)) {
//                 $_SESSION['user_id'] = $user_id;
//                 $_SESSION['user_email'] = $user_email;
//                 header("Location: ../admin.php");
//             }else {
//                 # Error message
//                 $em = "Incorrect User name or password";
//                 header("Location: ../login.php?error=$em");
//             }
//         }else {
//             # Error message
//             $em = "Incorrect User name or password";
//             header("Location: ../login.php?error=$em");
//         }
//     }else{
//         $em = "Incorrect User name or password";
//         header("Location: ../login.php?error=$em");
//     }

// }else {
//     # redirect to "../login.php"
//     header("Location: ../login.php");
}
?>
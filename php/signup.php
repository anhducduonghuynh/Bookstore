<?php
// session_start();

if (isset($_POST['fullname']) &&
    isset($_POST['username']) && 
    isset($_POST['password']) &&
    isset($_POST['DOB'])      &&
    isset($_POST['role'])){

    #Database Connection File
    include "../db_conn.php";

    #validation helper function
    include "func-validation.php";
    
    /**Get data from POST request
       and store them in var
    **/
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $DOB = $_POST['DOB'];
    $role = $_POST['role'];

    $user_input = '&fullname='.$fullname.'&un'.$username.'&DOB='.$DOB.'&role='.$role;

    # simple form validation
    $text = "Fullname";
    $location = "../signup.php";
    $ms = "error";
    is_empty($fullname, $text, $location, $ms, $user_input);

    $text = "Email";
    $location = "../signup.php";
    $ms = "error";
    is_empty($username, $text, $location, $ms, $user_input);

    $text = "Passsword";
    $location = "../signup.php";
    $ms = "error";
    is_empty($password, $text, $location, $ms, $user_input);

    $text = "DOB";
    $location = "../signup.php";
    $ms = "error";
    is_empty($DOB, $text, $location, $ms, $user_input);

    $text = "Role";
    $location = "../signup.php";
    $ms = "error";
    is_empty($role, $text, $location, $ms, $user_input);

    # 
    if($role == 'customer'){
        /**
         * call function check if account is already in database by username
         */
        $sql = "SELECT Customer_exist('$username') a";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        $result = $result['a'];
        if($result){
            $em = "Username is already existed. Please try another!";
            header("Location: ../signup.php?error=$em&fullname=$fullname&DOB=$DOB&role=$role");
            exit;
        }else{
            /**
             * Insert new account into database
             */
            $sql = "CALL ADD_CUSTOMER('$fullname', '$username', '$password', '$DOB')";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            # Error message
            $sm = "Successfully Sign up";
            header("Location: ../signup.php?success=$sm");
            exit;
        }
    }
    // else if($role == 'staff'){
    //     /**
    //      * call function check if account is already in database by username
    //      */
    //     $sql = "SELECT Staff_exist('$username') a";
    //     $stmt = $conn->prepare($sql);
    //     $stmt->execute();
    //     $result = $stmt->fetch();
    //     $result = $result['a'];
    //     if($result){
    //         $em = "Username is already existed. Please try another!";
    //         header("Location: ../signup.php?error=$em&fullname=$fullname&DOB=$DOB&role=$role");
    //         exit;
    //     }else{
    //         /**
    //          * Insert new account into database
    //          */
    //         $sql = "CALL ADD_CUSTOMER('$fullname', '$username', '$password', '$DOB')";
    //         $stmt = $conn->prepare($sql);
    //         $stmt->execute();
    //         # Error message
    //         $sm = "Successfully Sign up";
    //         header("Location: ../signup.php?success=$sm");
    //         exit;
    //     }
    // }
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
}else {
        # redirect to "../signup.php"
        header("Location: ../signup.php");
}
?>
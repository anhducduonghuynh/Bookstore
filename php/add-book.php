<?php 
session_start();

#If the admin is logged in
if (isset($_SESSION['username'])&&
    isset($_SESSION['password'])){

    # Database Connection File
    include "../db_conn.php";

    # Validation helper function
    include "func-validation.php";

    # File Upload helper function
    include "func-file-upload.php";

    /**
     * If all Input field 
     * are filled
     */
    if (isset($_POST['book_id'])       && 
        isset($_POST['book_title'])       && 
        isset($_POST['book_publication']) &&
        isset($_POST['book_publisher'])      &&
        isset($_POST['book_description'])    &&
        isset($_POST['book_author'])    &&
        isset($_POST['book_price'])    &&
        isset($_POST['book_category'])    &&
        isset($_FILES['book_cover'])){
        // Get data form POST request
        // and store them in var
        $id = $_POST['book_id'];
        $title = $_POST['book_title'];
        $publication = $_POST['book_publication'];
        $publisher = $_POST['book_publisher'];
        $description = $_POST['book_description'];
        $author = $_POST['book_author'];
        $listprice = $_POST['book_price'];
        $category = $_POST['book_category'];
        # making URL data format
        $user_input = '&BookID='.$id.'&title='.$title.'&category_name='.$category. 
                      '&desc='.$description.'&author='.$author.'&publication='.$publication.
                      '&publisher='.$publisher.'&listprice='.$listprice;

        #simple form validation
        
        $text = "Book ID";
        $location = "../add-book.php";
        $ms = "error";
        is_empty($id, $text, $location, $ms, $user_input);

        $text = "Book title";
        $location = "../add-book.php";
        $ms = "error";
        is_empty($title, $text, $location, $ms, $user_input);

        $text = "Publication";
        $location = "../add-book.php";
        $ms = "error";
        is_empty($publication, $text, $location, $ms, $user_input);

        $text = "Publisher";
        $location = "../add-book.php";
        $ms = "error";
        is_empty($publisher, $text, $location, $ms, $user_input);

        $text = "Book description";
        $location = "../add-book.php";
        $ms = "error";
        is_empty($description, $text, $location, $ms, $user_input);

        $text = "Book author";
        $location = "../add-book.php";
        $ms = "error";
        is_empty($author, $text, $location, $ms, $user_input);

        $text = "List Price";
        $location = "../add-book.php";
        $ms = "error";
        is_empty($listprice, $text, $location, $ms, $user_input);

        $text = "Book category";
        $location = "../add-book.php";
        $ms = "error";
        is_empty($category, $text, $location, $ms, $user_input);

        # book cover Uploading
        $allowed_image_exs = array("jpg", "jpeg", "png", "jfif");
        $path = "cover";
        $book_cover = upload_file($_FILES['book_cover'], $allowed_image_exs, $path);

        /**
         * If error occurred while 
         * uploading the book cover
         */
        
        if($book_cover['status'] == "error"){
            $em = $book_cover['data'];
            /**
             * Redirect to '../add-book.php' 
             * and passing error message & user_input
             */
            header("Location: ../add-book.php?error=$em&$user_input");
            exit;
        }else{
                $book_cover_URL = $book_cover['data'];
                $sql = "SELECT Book_exist($id) a";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $res = $stmt->fetch();
                if($res['a']){
                    # Book is already existed
                    $em = "Book is already existed!";
                    header("Location: ../add-book.php?error=$em");
                    exit;
                }else {
                    $sql = "CALL ADD_BOOK('$id', '$title', '$author', '$publication', '$publisher', '$listprice', '$book_cover_URL', '$description', '$category')";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    # Success message
                    $sm = "The book successfully created!";
                    header("Location: ../add-book.php?success=$sm");
                    exit;
                }
            

                // /**
                //  * If there is no error while 
                //  * inserting the data
                //  */
                // if($res){
                //     # Success message
                //     $sm = "The book successfully created!";
                //     header("Location: ../add-book.php?success=$sm");
                //     exit;
                // }else {
                //     # Error message
                //     $em = "Unknown Error Occured!";
                //     header("Location: ../add-book.php?error=$em");
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
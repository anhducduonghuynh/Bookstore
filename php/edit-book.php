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

    # category helper function
    include "func-category.php";

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
        isset($_FILES['book_cover'])      &&
        isset($_POST['current_cover'])){
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

        /** 
         * Get current cover & current file
         * from POST request and store them in var
         */

        $current_cover = $_POST['current_cover'];

        #simple form validation

        $text = "Book ID";
        $location = "../add-book.php";
        $ms = "id=$id&error";
        is_empty($id, $text, $location, $ms, "");

        $text = "Book title";
        $location = "../edit-book.php";
        $ms = "id=$id&error";
        is_empty($title, $text, $location, $ms, "");

        $text = "Publication";
        $location = "../add-book.php";
        $ms = "id=$id&error";
        is_empty($publication, $text, $location, $ms, "");

        $text = "Publisher";
        $location = "../add-book.php";
        $ms = "id=$id&error";
        is_empty($publisher, $text, $location, $ms, "");

        $text = "Book description";
        $location = "../edit-book.php";
        $ms = "id=$id&error";
        is_empty($description, $text, $location, $ms, "");

        $text = "Book author";
        $location = "../edit-book.php";
        $ms = "id=$id&error";
        is_empty($author, $text, $location, $ms, "");

        $text = "List Price";
        $location = "../add-book.php";
        $ms = "id=$id&error";
        is_empty($listprice, $text, $location, $ms, "");

        $text = "Book category";
        $location = "../edit-book.php";
        $ms = "id=$id&error";
        is_empty($category, $text, $location, $ms, "");

        $current_category = get_category_by_name($conn, $category);
        $current_category_ID = $current_category['ID'];
        /**
         * If the admin try to 
         * update the book cover
         */
        if(!empty($_FILES['book_cover']['name'])){
            # book cover Uploading
            // $allowed_image_exs = array("jpg", "jpeg", "png", "jfif");
            // $path = "cover";
            // $book_cover = upload_file($_FILES['book_cover'], $allowed_image_exs, $path);
            /**
             * If the admin try to 
             * update both
             */

            # book cover Uploading
            $allowed_image_exs = array("jpg", "jpeg", "png", "jfif");
            $path = "cover";
            $book_cover = upload_file($_FILES['book_cover'], $allowed_image_exs, $path);

            # file cover Uploading
            // $allowed_file_exs = array("pdf", "docx", "pptx");
            // $path = "files";
            // $file = upload_file($_FILES['file'], $allowed_file_exs, $path);

            /**
            * If error occurred while 
            * uploading
            */
            if($book_cover['status'] == "error"){

                $em = $book_cover['data'];
                /**
                * Redirect to '../edit-book.php' 
                 * and passing error message & the id
                 */
                header("Location: ../edit-book.php?error=$em&id=$id");
                exit;
            }else{
                $book_cover_URL = $book_cover['data'];
                # current book cover location
                $c_p_book_cover = "../uploads/cover/$current_cover";
                    
                # current file location
                // $c_p_file = "../uploads/files/current_file";

                # Delete from the server
                unlink($c_p_book_cover);
                // unlink($c_p_file);

                /**
                 * Get the new file name
                 * and the book cover name
                 */
                // $file_URL = $file['data'];
                // $book_cover_URL = $book_cover['data'];

                # update just the data
                $sql = "CALL UPDATE_BOOK('$id', '$title', '$author', '$publication', '$publisher', '$listprice', '$book_cover_URL', '$description', '$current_category_ID')";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                # Success message
                $sm = "Successfully updated!";
                header("Location: ../edit-book.php?success=$sm&id=$id");
                exit;
            }
        }else {
            /**
             * Update with no change in cover
             */
            $sql = "CALL UPDATE_BOOK('$id', '$title', '$author', '$publication', '$publisher', '$listprice', '$current_cover', '$description', '$current_category_ID')";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            # Success message
            $sm = "Successfully updated!";
            header("Location: ../edit-book.php?success=$sm&id=$id");
            exit;
        }
        /** 
         * If the admin try to 
         * update just the file
         */
        // else if (!empty($_FILES['file']['name'])){
        //     # update just the file here
        // }else {
        //     # update just the data
        //     $sql = "UPDATE books
        //             SET title=?,
        //                 author_id=?,
        //                 description=?,
        //                 category_id=?
        //             WHERE id=?";
        //     $stmt = $conn->prepare($sql);
        //     $res = $stmt->execute([$title, $author, $description, $category, $id]);
        //     /**
        //      * If there is no error while 
        //      * updating the data
        //      */
        //     if($res){
        //         # Success message
        //         $sm = "Successfully updated!";
        //         header("Location: ../edit-book.php?success=$sm&id=$id");
        //         exit;
        //     }else {
        //         # Error message
        //         $em = "Unknown Error Occured!";
        //         header("Location: ../edit-book.php?error=$em&id=$id");
        //         exit;
        //     }
        // }

    }else {
        header("Location: ../admin.php");
        exit;
    }



}else{
    header("Location: ../login.php");
    exit;
} ?>
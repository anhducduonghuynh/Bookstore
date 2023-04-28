<?php

#Get All books function
function get_all_books($con){
    $sql = "SELECT * FROM book ORDER BY BookID DESC";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $books = $stmt->fetchAll();
    }else {
        $books = 0;
    }

    return $books;
}


#Get book by ID function
function get_book($con, $id){
    $sql = "SELECT * FROM book WHERE BookID =?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$id]);

    if($stmt->rowCount() > 0) {
        $book = $stmt->fetch();
    }else {
        $book = 0;
    }

    return $book;
}


#Search books function
function search_books($con, $key){
    # creating simple search algorithm :))
    $key = "%{$key}%";
    $sql = "SELECT * FROM book 
            WHERE Title LIKE ? 
            OR des LIKE ?
            OR Author LIKE ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$key, $key, $key]);

    if($stmt->rowCount() > 0) {
        $books = $stmt->fetchAll();
    }else {
        $books = 0;
    }

    return $books;
}

# get_book_by_category
function get_book_by_category($con, $id){
    $sql = "SELECT * FROM BOOK 
            WHERE BookID IN 
            (SELECT BookID FROM belong JOIN categories ON belong.ID = categories.ID WHERE categories.ID=?)";
    $stmt = $con->prepare($sql);
    $stmt->execute([$id]);

    if($stmt->rowCount() > 0) {
        $books = $stmt->fetchAll();
    }else {
        $books = 0;
    }

    return $books;
}

# get book by author name
function get_book_by_author($con, $author){
    $sql = "SELECT * FROM BOOK 
            WHERE Author =?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$author]);

    if($stmt->rowCount() > 0) {
        $books = $stmt->fetchAll();
    }else {
        $books = 0;
    }

    return $books;
}

# get books in cart of user by username
function get_book_cart($con, $username){
    $sql = "SELECT * FROM BOOK
            WHERE BOOKID IN (SELECT BOOKID FROM (CART JOIN CONTAIN ON CART.CODE=CONTAIN.CODE) JOIN CUSTOMER ON Cart.CustomerID=CUSTOMER.CustomerID WHERE username=?)";
    $stmt = $con->prepare($sql);
    $stmt->execute([$username]);

    if($stmt->rowCount() > 0) {
        $books = $stmt->fetchAll();
    }else {
        $books = 0;
    }

    return $books;
}

# if book in cart return the number else return 0
function is_book_contain($con, $bookid, $username){
    $sql = "SELECT EXIST_BOOK('$bookid', '$username') a";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $res = $stmt->fetch();
    $res = $res['a'];
    return $res;
}

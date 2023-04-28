<?php
#Get All discount function
function get_all_discount($con){
    $sql = "SELECT * FROM dis_program";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $discounts = $stmt->fetchAll();
    }else {
        $discounts = 0;
    }

    return $discounts;
}

function get_dis_by_book($con, $bookid){
    $sql = "SELECT * FROM dis_program LEFT JOIN applies ON dis_program.ID = applies.ID WHERE applies.BookID = $bookid";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $discount = $stmt->fetch();
    }else {
        $discount = 0;
    }

    return $discount;
}
?>
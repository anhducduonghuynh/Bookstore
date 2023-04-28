<?php # if book in cart return the number else return 0
function total_bill($con, $username){
    $sql = "SELECT TOTAL_ALL('$username') a";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $res = $stmt->fetch();
    $res = $res['a'];
    return $res;
}
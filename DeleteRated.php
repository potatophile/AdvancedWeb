<?php
include "mysqlconn.php";
if (isset($_GET['ratingid'])) {
    //echo $_GET['id'];
    $id = $_GET['ratingid'];
    $query = "DELETE FROM `tb_ratings` WHERE ratingID = '$id'";
    $res = Conn($query) or exit(mysqli_error($Conn));
    if ($res) {
        //echo '<script>alert( $id " deleted")</script>';
        header('location:user.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

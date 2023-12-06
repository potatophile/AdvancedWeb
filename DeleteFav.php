<?php
include "mysqlconn.php";
if (isset($_GET['favlistid'])) {
     //echo $_GET['id'];
     $id = $_GET['favlistid'];
     $query = "DELETE FROM `tb_movielist` WHERE listID = '$id'";
     $res = Conn($query) or exit(mysqli_error($Conn));
     if ($res) {
          //echo '<script>alert( $id " deleted")</script>';
          header('location:user.php');
     } else {
          echo "Error: " . mysqli_error($conn);
     }
}

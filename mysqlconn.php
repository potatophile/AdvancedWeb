<?php

      $link = new mysqli('localhost','root','','test');
      // var_dump($link);

      if($link->connect_error){
       switch($link->connect_error){
           case 1045 : echo " 访问被拒绝，可能用户名或者密码错位";
           break;
           case 1049 : echo " 数据库名称错位";
           break;
           default:break;
        }        
      }else{

        echo "connection success " . date("h:i:sa");
        $link->query("SET NAMES utf8");
   
        //close connection
        mysqli_close($link);
      }
    ?>
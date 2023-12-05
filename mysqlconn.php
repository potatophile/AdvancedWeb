<?php

      $link = new mysqli('localhost','root','','movie');
      // var_dump($link);

      if($link->connect_error){
       switch($link->connect_error){
           case 1045 : echo "Connection Failed";
           break;
           case 1049 : echo "Failed to connect";
           break;
           default:break;
        }        
      }else{

        $link->query("SET NAMES utf8");
   
        //close connection
        mysqli_close($link);
      }
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<title>Homepage</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
  integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<link rel="stylesheet" href="mystyle.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
  integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

<?php

function Conn($sql)
{
  $res = null;
  $link = new mysqli('localhost', 'root', '', 'movie'); //change to your db accordingly
  if ($link->connect_error) { // see if link sucessful
    switch ($link->connect_error) {
      case 1045:
        echo "Connection declined, check passowrd";
        break;
      case 1049:
        echo "Check db name";
        break;
      default:
        break;
    }
  } else {
    $link->query("SET NAMES utf8"); //set char set
    $res = $link->query($sql); //res is inside this function, increasing the visiblity
  }

  mysqli_close($link);

  return $res;
}

?>



<body>
  <div class='container-fluid my-5 align-content-start text-center'>

    <div>
      <h2>Movie Rater</h2>
    </div>
    <div class='container-fluid col-lg-12'>
    <?php
    //Beginning the session.
    //https://www.w3docs.com/snippets/php/how-to-expire-a-php-session.html
    session_start();

    //if user is not logged in, redirect to login page
    if (!isset($_SESSION["last_action"])) {
      header("location: login.php");
      exit;
    }
    //Expiring the session in case the user is inactive for 30
    //minutes or more.
    $expireAfter = 30;

    //Test to make sure if our "last action" session
    //variable was set.
    if (isset($_SESSION['last_action'])) {
      //Find out how many seconds have already passed
      //since the user was active last time.
      echo "<div class='d-flex'>";
      echo "<h3 class='d-flex justify-content-start col-sm-4'>Welcome：" . $_SESSION['userName'] . "</h3>";
      echo "<a class='col-sm-4 btn btn-light' href=user.php>User Profile</a>";
      echo "<form method='post' class='d-flex form-group col-sm-4 justify-content-end'>";
      echo "<input class='btn btn-secondary' type='submit' name='buttonKillSession' value='Log Out'/>";
      echo "</form>";
      echo "</div>";

      $secondsInactive = time() - $_SESSION['last_action'];

      //Converting the minutes into seconds.
      $expireAfterSeconds = $expireAfter * 60;

      //Test to make sure if they have not been active for too long.
      if ($secondsInactive >= $expireAfterSeconds) {
        // The user has not been active for too long.
        //Killing the session.
        session_unset();
        session_destroy();
      }
    } else {
      echo "<a class='d-flex justify-content-end' href='login.php'>Login</a>";
    }

    //Assigning the current timestamp as the user's
    // the latest action
    $_SESSION['last_action'] = time();

    //get values
    
    //echo "<h3>Welcome：" . $_SESSION['userName'] . "</h3>";
    //Warning: Undefined array key "userName" in /Applications/XAMPP/xamppfiles/htdocs/movierater/index.php on line 47
    //uncatched expection when logged out/not login
    
    ?>
    </div>



    <?php

    //This function log out the user by destroying the session
    
    if (isset($_POST['buttonKillSession'])) {
      session_unset();
      session_destroy();

      echo "You have been logged out";
    }

    ?>
    <?php

    // search function
// search box and button
// list all category as dropdown
// search display
    
    // load genre
// make search query
// $getGenreSql = 'SELECT DISTINCT genre FROM tb_movie';
    
    ?>

    <form method="post" class='d-flex form-group mt-3'>
      <input class = 'form-control' type="text" name="searchName" placeholder="Search here...">
      <input class = 'm-3 btn btn-primary' type="submit" name="button" value="Search">

    </form>

    <?php

    echo '<br>';

    if (isset($_POST['button'])) {
      $searchName = $_POST['searchName'];

      // possible problem with search query
    
      // $getMovieSql = 'SELECT * FROM tb_movie WHERE movieName LIKE "%$searchName%"';
      $getMovieSql = "SELECT * FROM tb_movie WHERE movieName LIKE '%$searchName%'";
      // use Conn to read data
      $res = Conn($getMovieSql) or exit(mysqli_error($conn));

      if (mysqli_num_rows($res) < 1) {
        echo 'no movies avalible';
      } else {
        while ($row = mysqli_fetch_array($res)) {
          $movieId = $row['movieID'];
          $movieName = stripslashes($row['movieName']);
          $movieDesc = $row['description'];
          $movieGenre = stripslashes($row['genre']);
          $movieYear = stripslashes($row['year']);
          $movieRun = $row['runtime'];
          $meanRating = $row['meanRating'];
          $nRatings = $row['numberOfRatings'];
          $movieCover = $row['cover'];

          // echo "<li>$movieName</li>";
          
          echo "<li><a href=\"movie.php?movieId=$movieId&movieName=$movieName&movieDesc=$movieDesc&movieGenre=$movieGenre&movieYear=$movieYear&movieRun=$movieRun&meanRating=$meanRating&nRatings=$nRatings&movieCover=$movieCover\">$movieName ($movieYear)</a></li>";
          echo "</div>";
        }
      }
    }
    ?>


    <?php
    // display movie catalog with 5 random movies
    
    // display movie catalog with 5 random movies
    $randomSectionWelcomeText = "<h4 class='d-flex mt-2 p-2'>Don't know what to watch?</h4>";

    echo $randomSectionWelcomeText;
    // Make the sql query
    
    //Make the sql query
    $getMovieSql = "SELECT * FROM tb_movie ORDER BY rand()   LIMIT 5";
    //use Conn to read data
    $res = Conn($getMovieSql) or die(mysqli_error($conn));

    if (mysqli_num_rows($res) < 1) {
      echo "no movies avalible";
    } else {
      while ($row = mysqli_fetch_array($res)) {
        $movieId = $row['movieID'];
        $movieName = stripslashes($row['movieName']);
        $movieDesc = $row['description'];
        $movieGenre = stripslashes($row['genre']);
        $movieYear = stripslashes($row['year']);
        $movieRun = $row['runtime'];
        $meanRating = $row['meanRating'];
        $nRatings = $row['numberOfRatings'];
        $movieCover = $row['cover'];

        //echo "<li>$movieName</li>";
        
        echo "<div class='container-fluid d-flex border mt-4 justify-content-around col-lg'>";
        echo "<div class='mr-4 p-2'>";
        echo "<a class='mt-4 p-4 d-flex ' href=\"movie.php?movieId=$movieId&movieName=$movieName&movieDesc=$movieDesc&movieGenre=$movieGenre&movieYear=$movieYear&movieRun=$movieRun&meanRating=$meanRating&nRatings=$nRatings&movieCover=$movieCover\">$movieName ($movieYear)</a>";
        echo "<img class='mt-4 p-4' style='height:400px' src='images/$movieCover'>";
        echo "</div>";
        echo "</div";
      }
    }

    ?>

  </div>

</body>

</html>
<?php

$connection = mysqli_connect('localhost', 'root', '', 'movie');

$movieId = mysqli_real_escape_string($connection, $_GET['movieId']);

$movieName = mysqli_real_escape_string($connection, $_GET['movieName']);

$movieDesc = mysqli_real_escape_string($connection, $_GET['movieDesc']);

$movieGenre = mysqli_real_escape_string($connection, $_GET['movieGenre']);

$movieYear = mysqli_real_escape_string($connection, $_GET['movieYear']);

$movieRun = mysqli_real_escape_string($connection, $_GET['movieRun']);

$meanRating = mysqli_real_escape_string($connection, $_GET['meanRating']);

$nRatings = mysqli_real_escape_string($connection, $_GET['nRatings']);

$movieCover = mysqli_real_escape_string($connection, $_GET['movieCover']);

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
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php echo "$movieName"; ?>
  </title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="stylesheet" href="mystyle.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
    integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V"
    crossorigin="anonymous"></script>
</head>

<body>
  <div class="container border text-center my-4 d-flex justify-content-center">
    <div class="row col-lg-8">

      <?php
      echo "<div class='col-md-12'>";
      $imgpath = "images/" . $movieCover;
      echo '<img src="' . $imgpath . '" style="width:300px;height:426px">';
      echo "</div>";
      echo "<div class='my-4 col-md-12 text-center d-flex justify-content-center'>";
      echo "<h1>$movieName ($movieYear)</h1>";
      echo "</div>";
      echo "<div class='col-md-12 text-center d-flex justify-content-center'>";
      echo "$movieGenre";
      echo "</div>";
      echo "<span class='col-md-12 text-center d-flex justify-content-center'>";
      echo "$movieRun";
      echo "</span>";
      echo "<span class='col-md-12 text-center d-flex justify-content-center'>";
      echo "$meanRating/10 ($nRatings Reviews)";
      echo "</span>";
      echo "<div class='my-4 col-lg-10 text-center d-flex justify-content-center border'>";
      $txtpath = "desc/" . $movieDesc;
      $myfile = fopen("$txtpath", "r") or die("Unable to open file!");
      echo fread($myfile, filesize("$txtpath"));
      fclose($myfile);
      echo "</div>";


      //Beginning the session.
      //https://www.w3docs.com/snippets/php/how-to-expire-a-php-session.html
      session_start();

      //Expiring the session in case the user is inactive for 30
      //minutes or more.
      $expireAfter = 30;

      //Test to make sure if our "last action" session
      //variable was set.
      if (isset($_SESSION['last_action'])) {
        //Find out how many seconds have already passed
        //since the user was active last time.
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
      }

      //Assigning the current timestamp as the user's
      // the latest action
      $_SESSION['last_action'] = time();

      //get values
      
      echo "<h3>Welcome：" . $_SESSION['userName'] . "</h3>";
      //Warning: Undefined array key "userName" in /Applications/XAMPP/xamppfiles/htdocs/movierater/index.php on line 47
      //uncatched expection when logged out/not login
      ?>
    </div>
    <div class='row'>
      <h4 class='d-flex justify-content-end'>Reviews<h4>
      <div class='row'>
        <div class='border' style='height:600px'>
        </div>
      </div>  
          <div class="input-group my-3 input-group-lg">
            <textarea type="text" rows='6' columns='50' class="form-control" placeholder='Write a review...'></textarea>
            <input type='submit' value='submit' class='btn'>
          </div>
          

    </div>

</body>

</html>
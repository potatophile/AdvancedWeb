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
  <div class="container text-center my-4 d-flex justify-content-center">
    <div class="row col-lg-8">
      <script>
        var hidden = false;
        function action() {
          hidden = !hidden;
          if (hidden) {
            document.getElementById('toggler').innerHTML = 'hidden'.style = 'Remove From Favorites';
          } else {
            document.getElementById('toggler').innerHTML = 'visible'.style = 'Add To Favorites';
          }
        }
      </script>

      <?php
      echo "<div class='col-md-12'>";
      $imgpath = "images/" . $movieCover;
      echo '<img src="' . $imgpath . '" style="width:300px;height:426px">';
      echo "<br>";
      echo "<form method='post'>";
      echo "<button id='toggler' name='add' onClick='action();' class='btn btn-primary col-sm-4 mt-2 p-2'>Add to Favourite</button>";
      echo "</form>";
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
      $userFavID = $_SESSION["id"];

      if (isset($_POST['add'])) {
        $selectFavSql = "SELECT * FROM tb_user";

        $res = Conn($selectFavSql);

        $addFavSql = "INSERT INTO tb_movielist (movieID, userID, listName) 
        values ('$movieId', '$userFavID', 'Favourites')" or die('Please try again.');

        $res = Conn($addFavSql);
        header("location: user.php");
      }

      if (isset($_POST['review'])) {
        $stars = $_POST['stars'];
        $review = $_POST['review'];
        if (!empty($stars) && !empty($review)) {
          $selectReviews = "SELECT * FROM tb_ratings";

          $res = Conn($selectReviews);
          INSERT INTO `tb_ratings`(`movieID`, `ratingID`, `userID`, `Stars`, `Review`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]')
          $addReviewSql = "INSERT INTO `tb_ratings` (`movieID`, `userID`, `Stars`, `Review`) 
    values ('$movieId', '$userFavID', $stars, $review)" or die('Please try again.');

          $res = Conn($addReviewSql);
        } else {
          echo "Please fill out all the fields before submitting a review.";
        }
      }
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
      
      echo "<h3>User:" . $_SESSION['userName'] . "</h3>";
      //Warning: Undefined array key "userName" in /Applications/XAMPP/xamppfiles/htdocs/movierater/index.php on line 47
      //uncatched expection when logged out/not login
      ?>
    </div>
    <div class='row'>
      <h4 class='d-flex justify-content-end'>Reviews<h4>
          <div class='row'>
            <div class='border' style='height:600px' data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">
              <?php
              $getReviewsSql = "SELECT * FROM tb_ratings WHERE movieID LIKE '%$movieId' ORDER BY rand()   LIMIT 5";
              //use Conn to read data
              $res = Conn($getReviewsSql) or die(mysqli_error($conn));

              if (mysqli_num_rows($res) < 1) {
                echo "<h5>No reviews at this time</h5>";
              } else {
                while ($row = mysqli_fetch_array($res)) {
                  $reviews = $row["Review"];
                  //echo "<li>$movieName</li>";
              
                  echo "<div class='border small d-flex pt-1' style='font-size:16px;'><pre>$reviews</pre></div>";
                }
              }
              ?>
            </div>
          </div>
          <form class="input-group my-3 input-group-lg form-group" method='post'>
            <div class='container col-sm-2'>
              <input name='stars' class='d-flex col-sm-1 form-control'> / 10</input>
            </div>
            <textarea type="text" rows='6' columns='50' class="form-control" placeholder='Write a review...'></textarea>
            <input style='width:100px' name='review' type='submit' value='Submit' class='btn'>
          </form>

    </div>

</body>

</html>
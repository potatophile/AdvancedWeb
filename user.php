<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<title>User Profile</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<link rel="stylesheet" href="mystyle.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

<?php

include 'mysqlconn.php'; 

function getMovieLink($movieID)
{
    // display movie catalog with 5 random movies

    // Make the sql query
    $getMovieSql = "SELECT * FROM tb_movie where movieID = '$movieID'";
    // use Conn to read data
    // ignore the 
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

            return "<a href=\"movie.php?movieId=$movieId&movieName=$movieName&movieDesc=$movieDesc&movieGenre=$movieGenre&movieYear=$movieYear&movieRun=$movieRun&meanRating=$meanRating&nRatings=$nRatings&movieCover=$movieCover\">$movieName ($movieYear)</a>";
        }
    }
}

function deleteFav($listid){
    
        $id = $listid;
        $query = "DELETE FROM `tb_movielist` WHERE listID = '$id'";
        $res = Conn($query) or exit(mysqli_error($conn));
        if ($res) {
             echo '<script>alert( $id " deleted")</script>';
             header('location:/advancedweb/user.php');
        } else {
             echo "Error: " . mysqli_error($conn);
        }
   
}

function delFav(){
    
}

?>

<body>
    <div class='container-fluid mt-4 p-4'>
        <h2 class='text-center'>User Profile</h2>

        <?php
        // Beginning the session.
        // https://www.w3docs.com/snippets/php/how-to-expire-a-php-session.html
        session_start();

        // Expiring the session in case the user is inactive for 30
        // minutes or more.
        $expireAfter = 30;

        // Test to make sure if our "last action" session
        // variable was set.
        if (isset($_SESSION['last_action'])) {
            // Find out how many seconds have already passed
            // since the user was active last time.
            $secondsInactive = time() - $_SESSION['last_action'];

            // Converting the minutes into seconds.
            $expireAfterSeconds = $expireAfter * 60;

            // Test to make sure if they have not been active for too long.
            if ($secondsInactive >= $expireAfterSeconds) {
                // The user has not been active for too long.
                // Killing the session.
                session_unset();
                session_destroy();
            }
        }

        // Assigning the current timestamp as the user's
        // the latest action

        // get values
        $_SESSION['last_action'] = time();
        if (isset($_SESSION['userName'])) {
            echo "<div class='container-fluid d-flex col-lg-12 mt-2 p-2'>";
            echo "<h3 class='d-flex justify-content-start col-sm-4'>Welcome：" . $_SESSION['userName'] . "</h3>";
            echo "<a class='col-lg-4 btn btn-light' href='index.php'>Home</a>";
            echo "<form method='post' class='d-flex form-group col-lg-4 justify-content-end'>";
            echo "<input class='btn btn-secondary' type='submit' name='buttonKillSession' value='Log Out'/>";
            echo "</form>";
            echo "</div>";
            // echo "<a href=" . 'user.php' .">User portal</a>";
        } else {
            // echo "<br>";
        }

        ?>

        <?php

        // This function log out the user by destroying the session

        if (isset($_POST['buttonKillSession'])) {
            session_unset();
            session_destroy();

            echo 'You have been logged out';

            // sends user back to homepage when logout
            header('Location: http://localhost/movierater/AdvancedWeb/index.php');
            exit;
        }

        ?>

        <form method="post">
            <input type="submit" name="buttonKillSession" value="Log Out" />
        </form>

        <h6>Update Password</h6>

        <form method="post">

            <input type="text" name="newPassword" placeholder="New password">
            <input type="submit" name="buttonPassword" value="Submit" />

        </form>

        <?php

        echo '<br>';

        // if pressed
        if (isset($_POST['buttonPassword'])) {

            $userID = $_SESSION['id'];

            $newPassword = $_POST['newPassword'];

            $query = "UPDATE tb_user SET pwd = '$newPassword' WHERE id = '$userID'";
            // use Conn to make query
            $res = Conn($query) or exit(mysqli_error($conn));
            echo '<script>alert("Password Updated successfully")</script>';
        }
        ?>

        <?php

        // display Favoried movies
        $userIDHelper = $_SESSION['id'];

        $devideString = ' - ';

        echo '<h5>Favourited Movies  </h5><br>';

        // Make the sql query WHERE userID = ".$_SESSION['userID']."
        //
        // $getMovieSql = "SELECT * FROM tb_movielist WHERE userID = 6";
        $getMovieSql = "SELECT * FROM tb_movielist WHERE userID = '$userIDHelper'";
        // use Conn to read data
        $res = Conn($getMovieSql) or exit(mysqli_error($conn));

        if (mysqli_num_rows($res) < 1) {
            echo "<h6>You haven't rated any movies yet.</h6>";
        } else {
            while ($row = mysqli_fetch_array($res)) {
                $movieID = $row['movieID'];
                $listID = $row['listID'];
                $userID = $row['userID'];

                $listName = stripslashes($row['listName']);

                $movieDetailLink = getMovieLink($movieID);

                $deleteFavLink = "<a href='DeleteFav.php?favlistid=".$listID."' id='buttonDelete'>Delete</a>";

                echo "<li>$movieDetailLink $devideString $listName $devideString $deleteFavLink</li>";
            }
        }

        ?>

        <?php
        // display favourited movies

        echo '<h5>Rated Movies</5><br>';

        $devideString = ' - ';

        

        // Make the sql query WHERE userID = ".$_SESSION['userID']."
        //
        // $getMovieSql = "SELECT * FROM tb_ratings WHERE userID = 6";
        $getMovieSql = "SELECT * FROM tb_ratings WHERE userID = '$userIDHelper'";
        // use Conn to read data
        $res = Conn($getMovieSql) or exit(mysqli_error($conn));

        if (mysqli_num_rows($res) < 1) {
            echo "<H6>You haven't rated any movies yet.</h6>";
        } else {
            while ($row = mysqli_fetch_array($res)) {
                $movieID = $row['movieID'];
                $ratingID = $row['ratingID'];
                $userID = $row['userID'];
                $Stars = $row['Stars'];
                $Review = stripslashes($row['Review']);

                $movieDetailLink = getMovieLink($movieID);

                $deleteFavLink = "<a href='DeleteRated.php?ratingid=".$ratingID."' id='buttonDelete'>Delete</a>";                

                echo "<li>$movieDetailLink $devideString $Stars  $devideString $Review $devideString $deleteFavLink</li>";
            }
        }

        ?>

    </div>

</body>

</html>
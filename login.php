<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<title>Log in</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<link rel="stylesheet" href="mystyle.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

<?php
//function for querying db
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

//login
if ($_POST) { //
    //Get input from form
    $userName = $_POST['userName'];
    $pwd = $_POST['pwd'];
    //See if theres input in username and pwd
    if ($userName && $pwd) {
        //Make the sql query
        $selectSql = "SELECT * FROM tb_user WHERE userName='$userName'";
        //use Conn to read data
        $res = Conn($selectSql);
        //see if rows > 1
        if ($res->num_rows) {
            //read result
            $info = mysqli_fetch_object($res);
            //See if password match
            if ($info->pwd == $pwd) {
                //echo "<script>window.alert('Login successful')</script>";

                //Beginning the session.
                //https://www.w3docs.com/snippets/php/how-to-expire-a-php-session.html

                session_start();

                //Expiring the session in case the user is inactive for 30
                //minutes or more.
                $expireAfter = 5;

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
                //assign value
                $_SESSION['id'] = $info->id;
                $_SESSION['userName'] = $info->userName;
                $_SESSION['pwd'] = $info->pwd;
                $_SESSION['email'] = $info->email;



                header("location:./index.php");
            } else {
                echo "<script>window.alert('Wrong password')</script>";
            }
        } else { //no account
            echo "<script>window.alert('No account under this name')</script>";
        }
    } else { //Empty login fields
        echo "<script>window.alert('Fill in user name and password to login')</script>";
    }
}
?>



<body>
    <div class="container text-center p-5 my-5 border d-flex justify-content-center">
        <div class="row p-5 col-lg-4 d-flex justify-content-center">
            <h1>Log in to your account</h1>
            <div class="row p-5 col-md-12">
                <form action="" method="post" class=".login-form">
                    <span class=".username-login">
                        <input type="text" name="userName" placeholder="Username" class="form-control">
                    </span>
                    <div class=".pass-login my-2 col-md-12">
                        <input type="password" name="pwd" placeholder="Password" class="form-control">
                    </div>
                    <span class="col">
                        <span class="col">
                            <span class="col form-group">
                                <input class="col-md-4 p-1 my-3 btn btn-primary" type="submit" value="Login">
                                <br>
                                <a href="register.php">Sign Up</a>
                            </span>
                        </span>
                    </span>    
                </form>                            
            </div>
        </div>
    </div>
</body>

</html>
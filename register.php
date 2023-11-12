<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<title>Create an Account</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<link rel="stylesheet" href="mystyle.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
    integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V"
    crossorigin="anonymous"></script>


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
    $email = $_POST['email'];
    //See if theres input in username and pwd
    if ($_POST["pwd"] === $_POST["confirmpwd"]) {
        // success!

        if ($userName && $pwd && $email) {

            //search for username
            //if username exists, reject registation
            //else continue

            //Make the sql query
            $selectSql = "SELECT * FROM tb_user WHERE userName='$userName'";
            //use Conn to read data
            $res = Conn($selectSql);
            //see if rows > 1
            if ($res->num_rows) { // Username exists

                echo "<script>window.alert('User name already exists')</script>";

            } else { //no account, register

                //Make the sql query
                $addSql = "INSERT INTO tb_user(userName,pwd,email) VALUES ('$userName','$pwd','$email')";

                //excecue sql query
                //
                $res = Conn($addSql);

                //
                //if (var_dump($res)) {
                echo "<script>window.alert('Registration success')</script>";
                //} else {
                //    echo "<script>window.alert('Registration failed')</script>";
                //}
            }
        } else { //Empty register fields
            echo "<script>window.alert('Fill in all fileds to register')</script>";
        }
    } else {
        echo "<script>window.alert('Passwords do not match')</script>";
    }
}
?>


<body>
    <div class="container text-center p-5 my-5 border d-flex justify-content-center">
        <div class="row p-5 col-lg-4 d-flex justify-content-center">
            <h1>Create an account</h1>
            <div class="row p-5 col-md-12">
                <form action="" method="post">
                    <span>
                        <input type="text" name="fName" placeholder="First Name">
                    </span>
                    <div class="my-2 col-md-12">
                        <input type="text" name="lName" placeholder="Last Name">
                    </div>
                    <div class="my-2 col-md-12">
                        <input type="text" name="userName" placeholder="Username">
                    </div>
                    <div class="my-2 col-md-12">
                        <input type="password" name="pwd" placeholder="Password">
                    </div>
                    <div class="my-2 col-md-12">
                        <input type="password" name="confirmpwd" placeholder="Confirm Password">
                    </div>
                    <div>
                        <input type="text" name="email" placeholder="Email Address">
                    </div>
                    <div class="my-3">
                        <input type="date" name="dob" placeholder="Date of Birth">
                    </div>
                    <span class="col">
                        <span class="col">
                            <span class="col">
                                <input class="col-md-8 p-1 my-3" type="submit" value="Register" class="">
                                <br>
                            </span>
                        </span>
                    </span>
                    <a href="login.php">Already have an account?</a>
                </form>
            </div>
        </div>
</body>


</html>
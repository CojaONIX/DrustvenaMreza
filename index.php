<?php

    session_start();

    if(isset($_SESSION['id'])) {
        header("Location: followers.php");
    }

    require_once "connection.php";
    $loginErr = "";
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $username =  $conn->real_escape_string($_POST["username"]);
        $pass =  $conn->real_escape_string($_POST["pass"]);
        $val = true;
        if(empty($username)) {
            $val = false;
            $loginErr = "Username cannot be left blank!";
        }
        if(empty($pass)) {
            $val = false;
            $loginErr .= "<br>Password cannot be left blank!";
        }

        if($val) {
            $sql = "SELECT * FROM users WHERE username = '$username'";
            $result = $conn->query($sql);
            if($result->num_rows == 0) {
                $loginErr = "This username doesn't exist!";
            } else {
                $row = $result->fetch_assoc();
                $dbPass = $row["pass"];
                if(md5($pass) != $dbPass) {
                    $loginErr .= "<br>Incorect password!";
                } else {
                    $ses_id = $_SESSION["id"] = $row["id"];

                    $sql = "SELECT name, surname FROM profiles WHERE user_id = '$ses_id'";
                    $row = $conn->query($sql)->fetch_assoc();
                    $_SESSION['full_name'] = $row['name'] . " " . $row['surname'];
                    header("Location: followers.php");
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>


    <?php
        require_once "connection.php";
        
        $sql = "SELECT * FROM users;";
        if ($result = $conn->query($sql)) {
            echo "<ul>";
                foreach($result as $row) {
                    echo "<li>" . $row["username"] . "</li>";
                }
            echo "</ul>";
        } else {
            echo "<p>Error: " . $sql . " --- " . $conn->error . "</p>";
        }

        $conn->close();
    ?>   


    <div class="row">
        <div class="col-sm-6 col-md-8">
            <h1>Društvena Mreža</h1>
            <h3>Završni projekat sa obuke IT Bootcamp</h3>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card">
                <div class="card-header"><h4>LOGIN<a class="btn btn-outline-primary float-right" href="register.php">or Register</a></h4></div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">                    
                        <div class="form-group">
                            <label for="username">User Name *</label>
                            <input type="text" class="form-control" name="username" id="username" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="pass">Password *</label>
                            <input type="password" class="form-control" name="pass" id="pass">
                        </div>                
                        <input type="submit" value="Login" class="btn btn-primary form-control">
                        
                    </form>
                </div>
                <div class="card-footer"><span class="error"><?php echo $loginErr; ?></span></div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }

            $('li').on("click", function() {
                $('#username').val($(this).text());
                $('#pass').val('123456');
            });

        });
    </script>
</body>
</html>
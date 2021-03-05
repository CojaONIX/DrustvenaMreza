<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
    require_once "connection.php";
    require_once "Validacija.php";

    $name = new Validacija();
    $surname = new Validacija();
    $gender = new Validacija();
    $dob = new Validacija();
    //$dob->setValid(date("Y-m-d"));
    //$dob->setValid(date("0000-00-00"));
    $username = new Validacija();
    $password = new Validacija();
    $rpassword = new Validacija();


    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $name->validText($_POST["name"], 1, 50, "a-zA-Z ");
        $surname->validText($_POST["surname"], 1, 50, "a-zA-Z ");
        $gender->validRadio($_POST["gender"], array("m", "f", "o"));
        $dob->validDate($_POST["dob"], "1900-01-01", date("Y-m-d"));
        $username->validText($_POST["username"], 5, 50, "a-zA-Z0-9 ?.!_");
        $password->validText($_POST["password"], 5, 25, "a-zA-Z0-9 ?.!_");
        $rpassword->validRetype($_POST["rpassword"], $password->getValid());

        if (Validacija::$isValidForm) {
            $un = $conn->real_escape_string($username->getValid());
            $pass = $conn->real_escape_string($password->getValid());
            $sql = "INSERT INTO users VALUES (null, '$un', MD5('$pass'));";
            echo $sql;
            if($conn->query($sql)) {
                echo "<p class='success'>Podaci su dodati u bazu.</p>";
                $last_id = $conn->insert_id;
                $n = $conn->real_escape_string($name->getValid());
                $s = $conn->real_escape_string($surname->getValid());
                $g = $conn->real_escape_string($gender->getValid());
                $d = $conn->real_escape_string($dob->getValid());
                $sql = "INSERT INTO profiles VALUES (null, '$n', '$s', '$g', '$d', $last_id)";
                echo $sql;
                if($conn->query($sql)) {
                    echo "<p class='success'>Podaci su dodati u bazu.</p>";
                } else {
                    echo "<p class='error'>Podaci nisu dodati u bazu. $conn->error</p>";
                }
            } else {
                echo "<p class='error'>Podaci nisu dodati u bazu. $conn->error</p>";
                if($conn->errno == 1062) {
                    $username->setMsg("<span class='error'>Vec postoji korisnik sa tim imenom!</span>");
                }
            }
        }
    }
?>    

    <div class="row">
        <div class="col-md-6 col-lg-4">
            <h1>Društvena Mreža</h1>
            <h3>Završni projekat sa obuke IT Bootcamp</h3>
        </div>
        <div class="col-md-6 col-lg-8">
            <div class="card">
                <div class="card-header"><h4>REGISTER<a class="btn btn-outline-primary float-right" href="index.php">or Login</a></h4></div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="username">User Name <span class="error">* </span></label>
                                <input type="text" class="form-control" name="username" id="username" value="<?php echo $username->getValid();?>" autofocus>
                                <?php echo $username->getMsg();?>
                            </div>

                            <div class="form-group">
                                <label for="password">Password <span class="error">* </span></label>
                                <input type="password" class="form-control" name="password" id="password">
                                <?php echo $password->getMsg();?>
                            </div>

                            <div class="form-group">
                                <label for="rpassword">Retype Password <span class="error">* </span></label>
                                <input type="password" class="form-control" name="rpassword" id="rpassword">
                                <?php echo $rpassword->getMsg();?>
                            </div>
                        </div>

                        <div class="col-sm-7">
                            <div class="form-group">
                                <label for="name" title="<?php echo $name->getOriginal();?>">Name: <span class="error">* </span></label>
                                <input type="text" class="form-control" name="name" id="name" value="<?php echo $name->getValid();?>">
                                <?php echo $name->getMsg();?>
                            </div>

                            <div class="form-group">
                                <label for="surname">Surname: <span class="error">* </span></label>
                                <input type="text" class="form-control" name="surname" id="surname" value="<?php echo $surname->getValid();?>">
                                <?php echo $surname->getMsg();?>
                            </div>

                            <div class="row">
                            <div class="col-5">
                            <div class="form-group">
                                <?php
                                    $n = "gender";
                                    $options = ["Male", "Female", "Other"];
                                    // Podrazumevano selektovani Radio, bez sledeceg bloka za nista
                                    $defaultOp = $options[2];
                                    if(empty($gender->getValid())) {
                                        $gender->setValid(lcfirst(substr($defaultOp, 0, 1)));
                                    }
                                    
                                    echo "<label>" . ucfirst($n) . ":</label>";
                                    foreach($options as $o) {
                                        // values kao mala pocetna slova iz $options
                                        $val = lcfirst(substr($o, 0, 1));
                                        $ch = $gender->getValid() == $val ? " checked" : "";
                                        echo "<div class='form-check'>";
                                        echo "<input type='radio' class='form-check-input' name='$n' id='$n$o' value='$val'$ch>\n";
                                        echo "<label class='form-check-label' for='$n$o'>$o</label>\n";
                                        echo "</div>";
                                    }
                                ?>
                                <?php echo $gender->getMsg();?> 
                            </div>
                            </div>                     

                            <div class="col-7">
                            <div class="form-group">
                                <label for="dob">DateOfBirth:</label>
                                <input type="date" class="form-control" name="dob" id="dob" value="<?php echo $dob->getValid();?>">
                                <?php echo $dob->getMsg();?>
                            </div>
                            </div>
                            </div>
                        </div>

                        <input type="submit" value="Register" class="btn btn-primary form-control">
                    </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <script>
        $(document).ready(function(){
            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }

        });
    </script>
    
</body>
</html>
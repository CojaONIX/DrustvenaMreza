
<?php
    require_once "header.php";
    require_once "connection.php";

    require_once "Validacija.php";

    $passwordOld = new Validacija();
    $password = new Validacija();
    $rpassword = new Validacija();


    if(isset($_SESSION['id'])) {
        $profile_id = $_SESSION['id'];
    } else {
        header("Location: index.php");
    }


    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $sql = "SELECT pass FROM users WHERE id=$profile_id;";
        if($result = $conn->query($sql)) {
            $passMD5 = md5($_POST["passwordOld"]);
            $passDB = $result->fetch_assoc()["pass"];
            if($passMD5 != $passDB) {
                $passwordOld->setMsg("<span class='error'>Wrong Password!</span>");
                Validacija::$isValidForm = false;
            } else {
                $password->validText($_POST["password"], 5, 25, "a-zA-Z0-9 ?.!_");
                $rpassword->validRetype($_POST["rpassword"], $password->getValid());
            }
        }

        if (Validacija::$isValidForm) {
            $pass = $conn->real_escape_string($password->getValid());
            $sql = "UPDATE users
                    SET pass = MD5('$pass')
                    WHERE id = $profile_id;";

            if($result = $conn->query($sql)) {
                //echo "<p class='success'>Podaci su dodati u bazu.</p>";
                header("Location: followers.php");
            } else {
                echo "<p class='error'>Podaci nisu dodati u bazu. $conn->error</p>";
            }
        }
    }

?>

<div class="row">
        <div class="col-md-6">
            <h1>Društvena Mreža</h1>
            <h3>Završni projekat sa obuke IT Bootcamp</h3>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h4>Change Password</h4></div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                        <div class="form-group">
                            <label for="passwordOld">Old Password <span class="error">* </span></label>
                            <input type="password" class="form-control" name="passwordOld" id="passwordOld" autofocus>
                            <?php echo $passwordOld->getMsg();?>
                        </div>

                        <div class="form-group">
                            <label for="password">New Password <span class="error">* </span></label>
                            <input type="password" class="form-control" name="password" id="password">
                            <?php echo $password->getMsg();?>
                        </div>

                        <div class="form-group">
                            <label for="rpassword">Retype New Password <span class="error">* </span></label>
                            <input type="password" class="form-control" name="rpassword" id="rpassword">
                            <?php echo $rpassword->getMsg();?>
                        </div>
                  
                        <input type="submit" value="Change Password" class="btn btn-primary form-control">
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        $(document).ready( function () {



        });
    </script>
</body>
</html>
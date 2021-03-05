
<?php
    require_once "header.php";
    require_once "connection.php";

    require_once "Validacija.php";

    $name = new Validacija();
    $surname = new Validacija();
    $gender = new Validacija();
    $dob = new Validacija();
    $bio = new Validacija();


    if(isset($_SESSION['id'])) {
        $profile_id = $_SESSION['id'];
        $sql = "SELECT name, surname, gender, dob, bio
                FROM profiles
                WHERE user_id=$profile_id;";

        if($result = $conn->query($sql)) {
            $p = $result->fetch_assoc();
            $name->setValid($p['name']);
            $surname->setValid($p['surname']);
            $gender->setValid($p['gender']);
            $dob->setValid($p['dob']);
            $bio->setValid($p['bio']);
        }

    } else {
        header("Location: index.php");
    }


    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $name->validText($_POST["name"], 1, 50, "a-zA-Z ");
        $surname->validText($_POST["surname"], 1, 50, "a-zA-Z ");
        $gender->validRadio($_POST["gender"], array("m", "f", "o"));
        $dob->validDate($_POST["dob"], "1900-01-01", date("Y-m-d"));

        $bio->validText($_POST["bio"], 0, 500, "a-zA-Z 0-9");

        if (Validacija::$isValidForm) {
            $n = $conn->real_escape_string($name->getValid());
            $s = $conn->real_escape_string($surname->getValid());
            $g = $conn->real_escape_string($gender->getValid());
            $d = $conn->real_escape_string($dob->getValid());
            $b = $conn->real_escape_string($bio->getValid());
            $sql = "UPDATE profiles
                    SET name = '$n', surname = '$s', gender = '$g', dob = '$d', bio = '$b'
                    WHERE user_id = $profile_id;";
            //echo $sql;
            if($conn->query($sql)) {
                $_SESSION['full_name'] = $n . " " . $s;
                //echo "<p class='success'>Podaci su dodati u bazu.</p>";
                header("Location: followers.php");
            } else {
                echo "<p class='error'>Podaci nisu dodati u bazu. $conn->error</p>";
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
                <div class="card-header"><h4>Change Profile</h4></div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
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
                                <label id="clearDate">Clear Date</label>
                            </div>
                        </div>
                        </div>

                        <div class="form-group">
                            <label for="bio">Biografija:</label>
                            <textarea class="form-control" name="bio" id="bio" cols="30" rows="3"><?php echo $bio->getValid();?></textarea>
                            <?php echo $bio->getMsg();?>
                        </div>

                        <input type="submit" value="Save new data" class="btn btn-primary form-control">
                    </form>
                </div>

            </div>
        </div>
    </div>


    <script>
        $(document).ready( function () {
            $('#clearDate').click(function() {
                $('#dob').val('0000-00-00');
                //$("input:radio").prop("checked", false);
            });
        });
    </script>
</body>
</html>
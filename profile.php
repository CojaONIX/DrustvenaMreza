
<?php
    require_once "header.php";
    require_once "connection.php";

    $profile_id = $_GET['profile_id'];
    $sql = "SELECT p.name, p.surname, u.username, p.gender, p.dob, p.bio
            FROM profiles AS p
            INNER JOIN users AS u
            ON p.user_id = u.id
            WHERE user_id=$profile_id;";

    $result = $conn->query($sql);
    if($result->num_rows == 1) {
        $p = $result->fetch_assoc();
        $name = $p['name'];
        $surname = $p['surname'];
        $username = $p['username'];
        $gender = $p['gender'];
        switch ($gender) {
            case 'm':
                $gender = "Muski";
                $gClass = "polMuski";
                break;
            case 'f':
                $gender = "Zenski";
                $gClass = "polZenski";
                break;
            default: 
                $gender = "Drugo";
                $gClass = "polDrugo";

        }
        $dob = $p['dob'];
        $bio = $p['bio'];
    } else {
        echo "<p id='badGetId'>Korisnik id = $profile_id ne postoji u bazi.</p>";
        die();
    }

    $conn->close();
?>    

    <h1>Profile <?php echo $username; ?></h1>
    <table class="<?php echo $gClass; ?>">
        <tr>
            <td>First Name</td>
            <td><?php echo $name; ?></td>
        </tr>
        <tr>
            <td>Last Name</td>
            <td><?php echo $surname; ?></td>
        </tr>
        <tr>
            <td>Username</td>
            <td><?php echo $username; ?></td>
        </tr>
        <tr>
            <td>Date of birth</td>
            <td><?php echo $dob; ?></td>
        </tr>
        <tr>
            <td>Gender</td>
            <td><?php echo $gender; ?></td>
        </tr>
        <tr>
            <td>About</td>
            <td><?php echo $bio; ?></td>
        </tr>

    </table>

    <a class="btn btn-info" href="followers.php">Followers</a>

</body>
</html>
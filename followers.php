
<?php
    require_once "header.php";
    require_once "connection.php";

    if(empty($_SESSION["id"])) {
        header("Location: index.php");
    }

    $profile_id = $_SESSION["id"];

    if(isset($_GET['unfollow_id'])) {
        $receiverID = $conn->real_escape_string($_GET["unfollow_id"]);
        $sql = "DELETE FROM followers
                WHERE sender_id=$profile_id AND receiver_id=$receiverID;";
        if(!$result = $conn->query($sql)) {
            die("Greska:" . $conn->error);
        }
    }

    if(isset($_GET['follow_id'])) {
        $receiverID = $conn->real_escape_string($_GET["follow_id"]);
        if($profile_id != $receiverID) {
            $sql = "INSERT INTO followers
                    VALUES ($profile_id, $receiverID);";
            if(!$result = $conn->query($sql)) {
                die("Greska1:" . $conn->error);
            }
        }
    }


    $sql = "SELECT u.id,
                    u.username AS 'Kor. Ime',
                    CONCAT(p.name, ' ', p.surname) AS 'Ime i prezime',
                    (SELECT GROUP_CONCAT(receiver_id) FROM followers WHERE sender_id=$profile_id) AS f_ed,
                    (SELECT GROUP_CONCAT(sender_id) FROM followers WHERE receiver_id=$profile_id) AS f_ers
            FROM profiles AS p
            INNER JOIN users AS u
            ON p.user_id = u.id
            WHERE u.id = $profile_id;";            
    
    if ($result = $conn->query($sql)) {
        $row = $result->fetch_assoc();
        $arrFollowed = explode("," , $row["f_ed"]);
        $arrFollowers = explode("," , $row["f_ers"]);
    } else {
        echo "<p>Error: " . $sql . " --- " . $conn->error . "</p>";
    }

    $sql = 'SELECT u.id,
                    u.username AS "Kor. Ime",
                    CONCAT(p.name, " ", p.surname) AS "Ime i prezime"
            FROM users AS u
            INNER JOIN profiles AS p
            ON p.user_id = u.id
            WHERE u.id != ' . $profile_id . ';';

    if ($result = $conn->query($sql)) {
        echo "<div class='divTable'>";
        echo "<table id='tableFriends' class='display compact'>";
        $zaglavlje = "Nema podataka u tabeli!";
        if($columns = array_keys($result->fetch_assoc())) {
            $zaglavlje = implode("</th><th>", $columns);
        }
        echo "\n\t<thead><tr><th>$zaglavlje</th><th>Akcija</th></tr></thead>";

        echo "\n\t<tbody>";
        foreach($result as $row) {
            $friend_id = $row["id"];
            $text = "Follow"; $un = ""; $aClass = "aFollow";
            if(in_array($friend_id, $arrFollowers)) {
                $text = "Follow back";
            }
            if(in_array($friend_id, $arrFollowed)) {
                $text = "Unfollow";
                $un = "un";
                $aClass = "aUnfollow";
            }
            $a = "<a class='$aClass aAkcija' href='followers.php?senderid=$profile_id&$un" . "follow_id=$friend_id'>$text</a>";
            $rid = $row['id'];
            $un = $row['Kor. Ime'];
            $fn = $row['Ime i prezime'];
            echo "<tr><td>$rid</td><td>$un</td><td><a href='profile.php?profile_id=$rid'>$fn</a></td><td>$a</td></tr>\n";
        }
        echo "</tbody>";
        
        //echo "<tfoot><tr><th></th></tr></tfoot>";
        echo "</table>";
        echo "</div>";
    } else {
        echo "<p>Error: " . $sql . " --- " . $conn->error . "</p>";
    }

    $conn->close();
?>    

    <script>
        $(document).ready( function () {
            $('#tableFriends').DataTable({
                lengthMenu: [10, 20, 2]
            });
        });
    </script>
</body>
</html>
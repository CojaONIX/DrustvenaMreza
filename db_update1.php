
<?php
    require_once "connection.php";
    $sql = "ALTER TABLE profiles
            ADD bio TEXT;";

    if($result = $conn->query($sql)) {
        echo "Dodata je kolona bio";
    } else {
        die("Dodabanje kolone nije uspelo " . $conn-error);
    }
?>
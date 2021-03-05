
<?php
    //error_reporting(E_ERROR | E_PARSE);

    $conn = new mysqli(
                    "localhost",    // servername
                    "admin",        // username
                    "admin123",     // password
                    "mreza"         // database
                );

    if ($conn->connect_error) {
        echo "<p>Connection failed: " . $conn->connect_error . "</p>";
    } else {
        //echo "<p>Connected successfully: $database</p>";
    }

?>
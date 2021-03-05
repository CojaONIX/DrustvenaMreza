<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create DB</title>
</head>
<body>
    
<?php
    $myfile = fopen("mreza.sql", "r") or die("Unable to open file!");
    $sql = fread($myfile,filesize("mreza.sql"));
    fclose($myfile);

    $conn = new mysqli(
                    "localhost",    // servername
                    "admin",        // username
                    "admin123"      // password
                );

    if ($conn->connect_error) {
        echo "<p>Connection failed: " . $conn->connect_error . "</p>";
    } else {
        echo "<p>Connected successfully!</p>";
    }

    if (!$conn->multi_query($sql)) {
        echo "<p>Error: " . $sql . " --- " . $conn->error . "</p>";
    } else {
        echo "<h1>Baza je uspesno kreirana :)</h1>";
        echo "<a href='insert.php'><button>INSERT test podataka iz videoteka_data.sql -> GO!</button></a><hr>";
        echo "<a href='index.php'><button>Back to INDEX</button></a>";
    }
?>

    

</body>
</html>
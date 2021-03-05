<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drustvena Mreza</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>

    <link rel="stylesheet" href="style.css">
</head>
<body>


<?php
    session_start();

    if(!empty($_SESSION)) {
        $profile_id = $_SESSION['id'];
        echo "<h1>Hello, " . $_SESSION['full_name'] . "</h1>";
    }

    require_once "connection.php";
?>


<nav>
    <a class="btn btn-info" href="followers.php">Followers</a>
    <a class="btn btn-info" href="changeProfile.php">Change profile</a>
    <a class="btn btn-info" href="changePass.php">Change password</a>
    <a class="btn btn-info" href="logout.php">Logout</a>
</nav>


<?php
if (isset($_POST["submit"])) {
    $psw = $_POST["psw"];
    $username = $_POST["username"];

    require_once "db.php";
    require_once "functions.php";

    loginUser($conn, $username, $psw);
}
header("Location:../");
exit();

<?php
if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $psw = $_POST["psw"];

    require_once "db.php";
    require_once "functions.php";

    if (searchDb($conn, $email, null, null) !== false) {
        header("Location: ../signup?error=emailinuse");
        exit();
    } elseif (searchDb($conn, null, $username, null) !== false) {
        header("Location: ../signup?error=usernameinuse");
        exit();
    }

    $hashedPsw = password_hash($psw, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username, $email, $hashedPsw]);

    loginUser($conn, $username, $psw);

    header("Location: ../");
    $stmt = null;
    exit();
}

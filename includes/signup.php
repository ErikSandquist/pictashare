<?php
if (isset($_POST["submit"])) {

    require_once "db.php";
    require_once "functions.php";

    foreach ($_POST as $post) {
        $i++;
        $post = cleanInput($post);
        $_POST[$i] = $post;
    }

    $username = $_POST["username"];
    $email = $_POST["email"];
    $psw = $_POST["psw"];

    if (searchDb($conn, $email, null, null) !== false) {
        header("Location: ../signup?error=Email%20already%20in%20use");
        exit();
    } elseif (searchDb($conn, null, $username, null) !== false) {
        header("Location: ../signup?error=Username%20taken");
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

<?php

function searchDb($conn, $email, $username)
{
    if ($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $var = $email;
    } elseif ($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $var = $username;
    }

    $stmt = $conn->prepare($sql);

    $stmt->execute([$var]);

    $row = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

    if ($row) {
        return $row;
    } else {
        return false;
    }

    $stmt = null;
    exit();
}

function loginUser($conn, $username, $psw)
{
    $userInfo = searchDb($conn, null, $username);

    if ($userInfo === false) {
        header("Location: ../login?error=notfound");
        exit();
    }

    $pswHashed = $userInfo['password'];

    if (password_verify($psw, $pswHashed) === false) {
        header("Location: ../login?error=wrongpassword");
        exit();
    }

    session_start();
    $_SESSION["userid"] = $userInfo["id"];
    $_SESSION["username"] = $userInfo["username"];
    $_SESSION["email"] = $userInfo["email"];
}

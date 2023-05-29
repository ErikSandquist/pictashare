<?php

function searchDb($conn, $email, $username, $userId)
{
    if ($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $var = $email;
    } elseif ($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $var = $username;
    } elseif ($userId) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $var = $userId;
    }

    $stmt = $conn->prepare($sql);

    $stmt->execute([$var]);

    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($row) {
        return $row[0];
    } else {
        return false;
    }

    $stmt = null;
    exit();
}

function loginUser($conn, $username, $psw)
{
    $userInfo = searchDb($conn, null, $username, null);

    if ($userInfo === false) {
        header("Location: ../login?error=User%20Not%20found");
        exit();
    }

    $pswHashed = $userInfo['password'];

    if (password_verify($psw, $pswHashed) === false) {
        header("Location: ../login?error=Wrong%20password");
        exit();
    }

    session_start();
    $_SESSION["userid"] = $userInfo["id"];
    $_SESSION["username"] = $userInfo["username"];
    $_SESSION["email"] = $userInfo["email"];
    $_SESSION["admin"] = $userInfo["admin"];
}

function loadPicture($conn, $id, $sort, $userid, $tags)
{
    if ($id != null) {
        $sql = "SELECT * FROM pictures WHERE id = ?";
        $var = $id;
    } elseif ($userid != null) {
        $sql = "SELECT * FROM pictures WHERE userid = ?";
        $var = $userid;
    } elseif ($tags != null) {
        $sql = "SELECT * FROM pictures WHERE tags = ?";
        $var = $tags;
    }
    if ($sort != null) {
        $sql = $sql . " ORDER BY createdate " . $sort;
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute([$var]);
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

    $stmt = null;

    $sql = "UPDATE pictures SET views = views + 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$row["id"]]);

    return $row;
}

function cleanInput($input)
{
    $output = strip_tags($input);
    $output = htmlspecialchars($output, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    return $output;
}

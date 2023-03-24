<?php
session_start();

if (isset($_POST["submit"])) {
    $userid = $_SESSION["userid"];

    if ($_FILES["banner"]["name"] != "") {
        $banner = $_FILES["banner"];
    } else {
        $banner = null;
    }

    if ($_FILES["picture"]["name"] != "") {
        $picture = $_FILES["picture"];
    } else {
        $picture = null;
    }

    $nickname = $_POST["nickname"];
    $username = $_POST["username"];

    $description = $_POST["description"];

    require_once "db.php";
    require_once "functions.php";

    if ($nickname == "") {
        $nickname = null;
    }

    function updateProfile($conn, $userid, $banner, $picture, $nickname, $username, $description)
    {
        $userInfo = searchDb($conn, null, $username);

        if ($userInfo != false and $username != $_SESSION["username"]) {
            header("Location:../profile/?error=usernametaken&edit=true");
            exit();
        } else {
            $_SESSION["username"] = $username;
        }

        if (isset($banner["name"])) {
            $banner = $banner['tmp_name'];
            $banner = file_get_contents($banner);
        } else {
            $banner = $userInfo["banner"];
        }

        if (isset($picture["name"])) {
            $picture = $picture['tmp_name'];
            $picture = file_get_contents($picture);
        } else {
            $picture = $userInfo["picture"];
        }

        $sql = "UPDATE users SET picture = ?, banner = ?, username = ?, nickname = ?, description = ? WHERE id = ?;";
        $stmt = $conn->prepare($sql);

        $stmt->execute([$picture, $banner, $username, $nickname, $description, $userid]);

        $stmt = null;
    }

    updateProfile($conn, $userid, $banner, $picture, $nickname, $username, $description);
}

header("Location:../profile");
exit();

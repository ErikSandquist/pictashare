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

    $userInfo = searchDb($conn, null, $username);

    if ($nickname == "") {
        $nickname = null;
    }

    if ($userInfo != false and $username != $_SESSION["username"]) {
        header("Location:../profile/?error=usernametaken&edit=true");
        exit();
    }

    function updateProfile($conn, $userid, $banner, $picture, $nickname, $username, $description)
    {
        if (isset($banner["name"])) {
            $banner = $banner['tmp_name'];
            $banner = file_get_contents($banner);
        } else {
            $banner = null;
        }

        if (isset($picture["name"])) {
            $picture = $picture['tmp_name'];
            $picture = file_get_contents($picture);
        } else {
            $picture = null;
        }

        if ($banner == null and $picture == null) {
            $sql = "UPDATE users SET username = ?, nickname = ?, description = ? WHERE id = ?;";
            $stmt = $conn->prepare($sql);

            $stmt->execute([$username, $nickname, $description, $userid]);
        } elseif ($banner == null and $picture != null) {
            $sql = "UPDATE users SET picture = ?, username = ?, nickname = ?, description = ? WHERE id = ?;";
            $stmt = $conn->prepare($sql);

            $stmt->execute([$picture, $username, $nickname, $description, $userid]);
        } elseif ($banner != null and $picture == null) {
            $sql = "UPDATE users SET banner = ?, username = ?, nickname = ?, description = ? WHERE id = ?;";
            $stmt = $conn->prepare($sql);

            $stmt->execute([$banner, $username, $nickname, $description, $userid]);
        } else {
            $sql = "UPDATE users SET picture = ?, banner = ?, username = ?, nickname = ?, description = ? WHERE id = ?;";
            $stmt = $conn->prepare($sql);

            $stmt->execute([$picture, $banner, $username, $nickname, $description, $userid]);
        }

        $stmt = null;
    }

    updateProfile($conn, $userid, $banner, $picture, $nickname, $username, $description);
}

header("Location:../profile");
exit();

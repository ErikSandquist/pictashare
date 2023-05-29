<?php
session_start();

require_once('db.php');
require_once('functions.php');
$id = $_GET['id'];

$imageInfo = loadPicture($conn, $id, null, null, null);

if ($imageInfo['userid'] == $_SESSION['userid'] or $_SESSION['admin'] == 1) {
    $sql = "DELETE FROM pictures WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_GET["id"]]);

    header("location:/pictashare/?error=Image%20Deleted");
} else {
    header("location:/pictashare/?error=You%20Don't%20Own%20This%20Image");
}

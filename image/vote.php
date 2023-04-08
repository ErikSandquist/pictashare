<?php

require_once '../includes/db.php';

session_start();

$sql = 'SELECT * FROM votes WHERE userid = ? AND pictureid = ?';
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION["userid"], $_GET["id"]]);
$oldVote = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($oldVote[0])) {
    $oldVote = $oldVote[0];
}

$stmt = null;

if ($_GET["type"] != null) {
    if ($oldVote == null) {
        $sql = "INSERT INTO votes(userid, pictureid, type) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_SESSION["userid"], $_GET["id"], $_GET["type"]]);
    } elseif ($oldVote["type"] != $_GET["type"]) {
        $sql = "UPDATE votes SET type = ? WHERE userid = ? AND pictureid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_GET["type"], $_SESSION["userid"], $_GET["id"]]);
    }

    echo $_GET["type"];
} elseif ($oldVote != null) {
    echo $oldVote["type"];
} else {
    echo null;
}

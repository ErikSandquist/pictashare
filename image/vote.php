<?php

require_once '../includes/db.php';

session_start();

$sql = 'SELECT * FROM votes WHERE userid = ? AND pictureid = ?';
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION["userid"], $_GET["id"]]);
$oldVote = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = null;

if (isset($oldVote[0])) {
    $oldVote = $oldVote[0];
}

$answer = array();

if ($_GET["type"] != null) {
    if ($oldVote == null) {
        $sql = "INSERT INTO votes(userid, pictureid, type) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_SESSION["userid"], $_GET["id"], $_GET["type"]]);

        $answer["response"] = $_GET["type"];
    } elseif ($oldVote["type"] == $_GET["type"]) {
        $sql = "DELETE FROM votes WHERE userid = ? AND pictureid = ?";
        $stmt = $conn->prepare($sql);
        $stmt = $stmt->execute([$_SESSION["userid"], $_GET["id"]]);

        $answer["response"] = null;
    } elseif ($oldVote["type"] != $_GET["type"]) {
        $sql = "UPDATE votes SET type = ? WHERE userid = ? AND pictureid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_GET["type"], $_SESSION["userid"], $_GET["id"]]);

        $answer["response"] = $_GET["type"];
    }
} elseif ($oldVote != null) {
    $answer["response"] = $oldVote["type"];
} else {
    $answer["response"] = null;
}

$pictureVotes = 0;

$sql = 'SELECT * FROM votes where pictureid = ?';
$stmt = $conn->prepare($sql);
$stmt->execute([$_GET["id"]]);
$countVote = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($countVote as $vote) {
    if ($vote["type"] == 1) {
        $pictureVotes += 1;
    } elseif ($vote["type"] == 0) {
        $pictureVotes -= 1;
    }
}

$answer["votes"] = $pictureVotes;

$sql = 'UPDATE pictures SET votes = ? where id = ?';
$stmt = $conn->prepare($sql);
$stmt->execute([$pictureVotes, $_GET["id"]]);

echo json_encode($answer);

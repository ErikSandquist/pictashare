<?php
require_once "../includes/db.php";

$sql = "SELECT id FROM pictures ORDER BY RAND() LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$pictures = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

header("Location:../image/?id=" . $pictures['id']);

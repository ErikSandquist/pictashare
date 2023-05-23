<?php
session_start();

require_once "../../includes/db.php";

if (!isset($_SESSION["admin"]) or $_SESSION["admin"] == 0) {
    echo "no admin";
    exit();
}

$sql = "UPDATE reports SET status = NOT status WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt = $stmt->execute([$_GET["id"]]);

echo json_encode([$_GET["id"]]);

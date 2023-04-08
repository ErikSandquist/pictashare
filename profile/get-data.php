<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$start = intval($_GET["start"]);
$limit = intval($_GET["limit"]);
$user = $_GET["user"];

$userId = searchDb($conn, null, $user, null)["id"];

$sql = "SELECT id, picture FROM pictures where userid = :userid LIMIT :start, :limit";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':userid', $userId, PDO::PARAM_INT);
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$items = array();
foreach ($results as $row) {
    $imageData = base64_encode($row['picture']);
    $src = 'data:image/jpeg;base64,' . $imageData;
    $id = $row['id'];
    $item = '<div class="item"><a href="../image/?id=' . $id . '"><img class="gallery-image w-full h-auto" src="' . $src . '"></a></div>';
    array_push($items, $item);
}

echo json_encode($items);

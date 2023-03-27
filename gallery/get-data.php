<?php
include '../includes/db.php';

$start = $_GET["start"];
$limit = $_GET["limit"];
$sql = "SELECT id, picture FROM pictures LIMIT :start, :limit";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$items = array();
foreach ($results as $row) {
    $imageData = base64_encode($row['picture']);
    $src = 'data:image/jpeg;base64,' . $imageData;
    $id = $row['id'];
    $item = '<div class="item"><a href="../image.php?id=' . $id . '"><img src="' . $src . '"></a></div>';
    array_push($items, $item);
}

echo json_encode($items);

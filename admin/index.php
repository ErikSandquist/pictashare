<?php

include "../nav.php";
include "../adminnav.php";

require_once "../includes/db.php";
require_once "../includes/functions.php";

if ($_SESSION["admin"] == 0) {
    header("Location:/pictashare");
}

$sql = "SELECT COUNT(id) from users";
$stmt = $conn->prepare($sql);
$stmt->execute();
$userCount = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["COUNT(id)"];
$stmt = null;

$sql = "SELECT COUNT(id) from pictures";
$stmt = $conn->prepare($sql);
$stmt->execute();
$postCount = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["COUNT(id)"];
$stmt = null;

$sql = "SELECT COUNT(userid) from comments";
$stmt = $conn->prepare($sql);
$stmt->execute();
$commentCount = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["COUNT(userid)"];
$stmt = null;

$sql = "SELECT COUNT(type) from votes";
$stmt = $conn->prepare($sql);
$stmt->execute();
$votesCount = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["COUNT(type)"];
$stmt = null;
?>

<!-- Just a smaller nav, just a preference and users wont see it anyways -->
<style>
    nav div {
        font-size: 32px !important;
    }

    nav div svg {
        height: 32px;
        width: 32px;
    }
</style>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>

<body>
    <div class="max-w-5xl flex justify-between items-center p-16 m-auto">
        <div class="bg-base-200 rounded-2xl text-4xl w-24 min-w-fit h-24 flex justify-center items-center p-2 flex-col">
            <p class="text-xl">Users:</p>
            <div class="value" number="<?php echo $userCount; ?>">0</div>
        </div>
        <div class="bg-base-200 rounded-2xl text-4xl w-24 min-w-fit h-24 flex justify-center items-center p-2 flex-col">
            <p class="text-xl">Posts:</p>
            <div class="value" number="<?php echo $postCount; ?>">0</div>
        </div>
        <div class="bg-base-200 rounded-2xl text-4xl w-24 min-w-fit h-24 flex justify-center items-center p-2 flex-col">
            <p class="text-xl">Comments:</p>
            <div class="value" number="<?php echo $commentCount; ?>">0</div>
        </div>
        <div class="bg-base-200 rounded-2xl text-4xl w-24 min-w-fit h-24 flex justify-center items-center p-2 flex-col">
            <p class="text-xl">Votes:</p>
            <div class="value" number="<?php echo $votesCount; ?>">0</div>
        </div>
    </div>
</body>

</html>

<script>
    const counters = document.querySelectorAll('.value');
    const speed = 1000;

    function animate() {
        counters.forEach(counter => {
            const value = +counter.getAttribute('number');
            const data = +counter.innerText;

            const time = value / speed;
            if (data < value) {
                counter.innerText = Math.ceil(data + time);
                setTimeout(animate, 1);
            } else {
                counter.innerText = value;
            }
        })
    }

    animate();
</script>
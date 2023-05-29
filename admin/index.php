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

$sql = "SELECT COUNT(type) from votes";
$stmt = $conn->prepare($sql);
$stmt->execute();
$votesCount = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["COUNT(type)"];
$stmt = null;

$sql = "SELECT * from reports ORDER BY id DESC LIMIT 3";
$stmt = $conn->prepare($sql);
$stmt->execute();
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <div class="max-w-2xl flex justify-between items-center p-8 m-auto">
        <div class="bg-base-200 rounded-2xl text-4xl w-24 min-w-fit h-24 flex justify-center items-center p-2 flex-col">
            <p class="text-xl">Users:</p>
            <div class="value" number="<?php echo $userCount; ?>">0</div>
        </div>
        <div class="bg-base-200 rounded-2xl text-4xl w-24 min-w-fit h-24 flex justify-center items-center p-2 flex-col">
            <p class="text-xl">Posts:</p>
            <div class="value" number="<?php echo $postCount; ?>">0</div>
        </div>
        <div class="bg-base-200 rounded-2xl text-4xl w-24 min-w-fit h-24 flex justify-center items-center p-2 flex-col">
            <p class="text-xl">Votes:</p>
            <div class="value" number="<?php echo $votesCount; ?>">0</div>
        </div>
    </div>
    <div class="flex justify-center items-center">
        <table class="mx-8 admintable">
            <tbody>
                <tr class="[&>td]:!h-fit">
                    <td>Id</td>
                    <td>Picture Id</td>
                    <td>User Id</td>
                    <td>Comment</td>
                    <td>Reporter Id</td>
                    <td>Done</td>
                    <td>Show</td>
                </tr>
                <?php foreach ($reports as $report) : ?>
                    <tr class="text-sm">
                        <td><?php echo $report["id"] ?></td>
                        <td><?php echo $report["pictureid"] ?></td>
                        <td><?php echo $report["userid"] ?></td>
                        <td><?php echo $report["comment"] ?></td>
                        <td><?php echo $report["reporterid"] ?></td>
                        <td><input type="checkbox" name="done" class="checkbox form-input w-2 h-2 p-2.5 !border-white" id="report<?php echo $report["id"] ?>" onclick="reportDone(<?php echo $report["id"] ?>)" <?php if ($report["status"] == 1) {
                                                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                                                } ?>></td>
                        <td>
                            <?php if (isset($report["pictureid"])) : ?>
                                <a href="/pictashare/image/?id=<?php echo $report["pictureid"] ?>">
                                    Visit
                                </a>
                            <?php else : ?>
                                <a href="/pictashare/profile/?id=<?php echo $report["userid"] ?>">
                                    Visit
                                </a>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<script>
    const counters = document.querySelectorAll('.value');
    const speed = 10000;

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
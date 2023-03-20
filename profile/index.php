<?php

include "../nav.php";

if (isset($_GET["user"])) {
    $username = $_GET["user"];
} elseif (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    header("Location:../login");
    exit();
}

require_once("../includes/db.php");
require_once("../includes/functions.php");

$userInfo = searchDb($conn, null, $username);

if ($userInfo === false) {
    header("Location:?error=notfound");
    exit();
}

if (isset($_GET["error"])) {
    exit();
}

$date1 = new DateTime($userInfo["createdate"]);
$date2 = new DateTime(date("Y/m/d"));
$days  = $date2->diff($date1)->format('%a');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $userInfo["username"] . "'s - Profile" ?></title>
</head>

<body>
    <main class="mt-40 w-[800px] mx-auto bg-base-200 rounded-3xl">
        <?php
        if ($userInfo["picture"] !== null) {
            echo '<img src="data:image/jpeg;base64,' . base64_encode($userInfo['banner']) . '" alt="" class="w-full h-36 rounded-t-3xl">';
        } else {
            echo '<img src="/pictashare/images/default/banner.jpg" alt="" class="w-full h-36 rounded-t-3xl">';
        }

        if ($userInfo["banner"] !== null) {
            echo '<img src="data:image/jpeg;base64,' . base64_encode($userInfo['picture']) . '" alt="" class="h-32 w-32 rounded-full bg-base-200 p-2 -mt-20 ml-12 inline">';
        } else {
            echo '<img src="/pictashare/images/default/profile.svg" alt="" class="h-32 w-32 rounded-full bg-base-200 p-2 -mt-16 ml-12 inline">';
        }
        ?>
        <div class="px-12 py-4">
            <?php
            if ($userInfo["nickname"] !== null) {
                echo '<h1 class="font-semibold text-2xl">' . $userInfo["nickname"] . '</h1>';
            } else {
                echo '<h1 class="font-semibold text-2xl">' . ucfirst($userInfo["username"]) . '</h1>';
            }
            ?>
            <h2 class="-mt-1 opacity-60">@<?php echo $userInfo["username"] ?></h2>
            <p class="mt-4"><?php echo $userInfo["description"] ?></p>
        </div>
        <div class="flex gap-4 p-4 px-12">
            <div class="w-4/12 flex flex-col gap-4">
                <?php
                for ($i = 0; $i < 3; $i++) {
                    echo '<img src="https://images.unsplash.com/photo-1678880032265-d768532b7454?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=687&q=80" alt="" class="w-full h-auto">';
                    echo '<img src="https://images.unsplash.com/photo-1679214523482-231359cf68f7?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1471&q=80" alt="" class="w-full h-auto">';
                }
                ?>
            </div>
            <div class="w-4/12 flex flex-col gap-4">
                <?php
                for ($i = 0; $i < 3; $i++) {
                    echo '<img src="https://images.unsplash.com/photo-1679214523482-231359cf68f7?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1471&q=80" alt="" class="w-full h-auto">';
                    echo '<img src="https://images.unsplash.com/photo-1678880032265-d768532b7454?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=687&q=80" alt="" class="w-full h-auto">';
                }
                ?>
            </div>
            <div class="w-4/12 flex flex-col gap-4">
                <?php
                for ($i = 0; $i < 3; $i++) {
                    echo '<img src="https://images.unsplash.com/photo-1678880032265-d768532b7454?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=687&q=80" alt="" class="w-full h-auto">';
                    echo '<img src="https://images.unsplash.com/photo-1679214523482-231359cf68f7?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1471&q=80" alt="" class="w-full h-auto">';
                }
                ?>
            </div>
        </div>
    </main>
</body>

</html>
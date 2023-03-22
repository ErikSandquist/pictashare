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

if (isset($_GET["edit"]) and $_SESSION["username"] == $username) {
    $editMode = $_GET["edit"];
} else {
    $editMode = "false";
}

require_once("../includes/db.php");
require_once("../includes/functions.php");

$userInfo = searchDb($conn, null, $username);

if ($userInfo === false) {
    header("Location:?error=notfound");
    exit();
}

if (isset($_GET["error"]) and $_GET["error"] == "notfound") {
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
        if (isset($editMode) and $editMode == "false") {
            if ($userInfo["banner"] !== null) {
                echo '<img src="data:image/jpeg;base64,' . base64_encode($userInfo['banner']) . '" alt="" class="w-full h-36 rounded-t-3xl object-cover">';
            } else {
                echo '<img src="/pictashare/images/default/banner.jpg" alt="" class="w-full h-36 rounded-t-3xl object-cover">';
            }

            if ($userInfo["picture"] !== null) {
                echo '<img src="data:image/jpeg;base64,' . base64_encode($userInfo['picture']) . '" alt="" class="h-32 w-32 rounded-full bg-base-200 p-2 -mt-16 ml-12 inline object-cover">';
            } else {
                echo '<img src="/pictashare/images/default/profile.svg" alt="" class="h-32 w-32 rounded-full bg-base-200 p-2 -mt-16 ml-12 inline object-cover">';
            }
        } elseif (isset($editMode) and $editMode == "true") {
            echo '<form action="/pictashare/includes/saveprofile.php" method="post" enctype="multipart/form-data">';
            echo '<label class="cursor-pointer">
            <input type="file" class="hidden" name="banner"/>';
            if ($userInfo["banner"] !== null) {
                echo '<div class="file-upload bg-base-200 w-full h-36 rounded-t-3xl overflow-hidden flex items-center"><img src="data:image/jpeg;base64,' . base64_encode($userInfo['banner']) . '" alt="" class="object-cover"></div>';
            } else {
                echo '<div class="file-upload bg-base-200 w-full h-36 rounded-t-3xl overflow-hidden flex items-center"><img src="/pictashare/images/default/banner.jpg" alt="" class="object-cover"></div>';
            }
            echo '</label>';

            echo '<label class="h-32 w-32 -mt-16 ml-12 block relative cursor-pointer">
                    <input type="file" class="hidden" name="picture"/>';
            if ($userInfo["picture"] !== null) {
                echo '<div class="file-upload rounded-full bg-base-200 p-2 overflow-hidden w-full h-full flex items-center"><img src="data:image/jpeg;base64,' . base64_encode($userInfo['picture']) . '" alt="" class="rounded-full"></div>';
            } else {
                echo '<div class="file-upload rounded-full bg-base-200 p-2 overflow-hidden w-full h-full flex items-center"><img src="/pictashare/images/default/profile.svg" alt="" class="rounded-full"></div>';
            }
            echo '</label>';
        }
        ?>
        <div class="flex px-12 p-4">
            <div class="w-full">
                <?php
                if (isset($editMode) and $editMode == "true") {
                    echo
                    '<input type="text" name="nickname" placeholder="Nickname" value="' . $userInfo["nickname"] . '" class="form-input profile-input">
                        <input type="text" name="username" placeholder="Nickname" value="' . $userInfo["username"] . '" class="form-input profile-input">
                        <textarea name="description" cols="30" rows="10" class="form-input profile-input">' . $userInfo["description"] . '</textarea>';
                } else {
                    if ($userInfo["nickname"] !== null) {
                        echo '<h1 class="font-semibold text-2xl">' . $userInfo["nickname"] . '</h1>';
                    } else {
                        echo '<h1 class="font-semibold text-2xl">' . ucfirst($userInfo["username"]) . '</h1>';
                    }

                    echo '<h2 class="-mt-1 opacity-60">@' . $userInfo["username"] . '</h2>';
                    echo '<p class="mt-4">' . $userInfo["description"] . '</p>';
                }
                ?>
            </div>
            <?php
            if (isset($_SESSION["username"]) and $_SESSION["username"] == $username) {
                if (isset($editMode) and $editMode == "true") {
                    echo '<div class="w-full flex justify-end items-start gap-2">
                            <a class="button outline" href="?edit=false">Cancel</a>
                            <button type="submit" class="button" name="submit">Save profile</button>
                        </div>';
                } else {
                    echo '<div class="w-full flex justify-end items-start">
                    <a href="?edit=true" class="button outline">Edit profile</a>
                </div>';
                }
            }
            ?>
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
        <?php
        if (isset($editMode) and $editMode == "true") {
            echo "</form>";
        }
        ?>
    </main>
</body>

</html>
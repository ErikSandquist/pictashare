<?php
include "../nav.php";

// Figure out if the user is visiting their own profile or someone elses.
if (isset($_GET["user"])) {
    $username = $_GET["user"];
} elseif (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    header("Location:../login");
    exit();
}

//Turns off edit mode if the user is not logged in to the profile they are visiting. If not then edit mode is disabled.
if (isset($_GET["edit"]) and $_SESSION["username"] == $username) {
    $editMode = $_GET["edit"];
} else {
    $editMode = "false";
}

require_once("../includes/db.php");
require_once("../includes/functions.php");

//Get the user's profile information using the username
$userInfo = searchDb($conn, null, $username, null);

//If the user information is not found then display the error message
if ($userInfo === false) {
    header("Location:?error=notfound");
    exit();
}

//set the picture and banner which will be used according to if they have set another picture than the default. I do this in the code rather than having the default picture saved on every user minimizing the size of the database
if ($userInfo["picture"] == null) {
    $picture = '/pictashare/images/default/profile.svg';
} else {
    $picture = 'data:image/jpeg;base64,' . base64_encode($userInfo['picture']);
}

if ($userInfo["banner"] == null) {
    $banner = '/pictashare/images/default/banner.jpg';
} else {
    $banner = 'data:image/jpeg;base64,' . base64_encode($userInfo['banner']);
}

//Just check if the error is notfound because then it shouldnt load the rest of the page, but if the error is other than that it should load the page with the error code on display for example: Username taken, when trying to change username in editmode
if (isset($_GET["error"]) and $_GET["error"] == "notfound") {
    exit();
}

//Quick calculation on how "old" the user is, want this to be displayed on the profile as a "badge" of sorts
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
    <main class="mt-40 w-[800px] mx-auto bg-base-200 rounded-2xl">
        <?php
        if (isset($editMode) and $editMode == "false") {
            echo '<img src=' . $banner . ' alt="" class="w-full h-36 rounded-t-2xl object-cover">
                  <img src=' . $picture . ' alt="" class="h-32 w-32 rounded-full bg-base-200 p-2 -mt-16 ml-12 inline object-cover">';
        } elseif (isset($editMode) and $editMode == "true") {
            echo '<form action="/pictashare/includes/saveprofile.php" method="post" enctype="multipart/form-data">';

            echo '<label class="cursor-pointer">
                    <input type="file" class="hidden" name="banner" id="image1" accept"image/apng, image/avif, image/gif, image/jpeg, image/png, image/webp"/>
                    <div id="preview1" class="file-upload bg-base-200 w-full h-36 rounded-t-2xl overflow-hidden flex items-center">
                        <img src=' . $banner . ' alt="" class="object-cover">
                    </div>
                 </label>';

            echo '<label class="h-32 w-32 -mt-16 ml-12 block relative cursor-pointer">
                    <input type="file" class="hidden" name="picture" id="image2" accept"image/apng, image/avif, image/gif, image/jpeg, image/png, image/webp"/>
                    <div id="preview2" class="file-upload rounded-full bg-base-200 p-2 overflow-hidden w-full h-full flex items-center">
                        <img src=' . $picture . ' alt="" class="rounded-full object-cover w-full h-full">
                    </div>
                 </label>';
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
                    echo '<div class="w-full flex justify-end items-start gap-2">
                            <a href="?edit=true" class="button outline">Edit profile</a>
                            <a class="button outline" href="../upload">Upload pictures</a>
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script>
    function imagePreview(fileInput, number) {
        if (fileInput.files && fileInput.files[0]) {
            var fileReader = new FileReader();
            if (number == 1) {
                fileReader.onload = function(event) {
                    $('#preview1').html('<img src="' + event.target.result + '" class="object-cover"/>');
                };
            } else {
                fileReader.onload = function(event) {
                    $('#preview2').html('<img src="' + event.target.result + '" class="rounded-full object-cover w-full h-full"/>');
                };
            }

            fileReader.readAsDataURL(fileInput.files[0]);
        }
    }

    $("#image1").change(function() {
        imagePreview(this, 1);
    });
    $("#image2").change(function() {
        imagePreview(this, 2);
    });
</script>
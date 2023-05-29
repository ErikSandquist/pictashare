<?php
include "../nav.php";
require_once("../includes/db.php");
require_once("../includes/functions.php");

// Figure out if the user is visiting their own profile or someone elses. and handle if they are using the id tag
if (isset($_GET["id"])) {
    $userInfo = searchDb($conn, null, null, $_GET["id"]);
    if ($userInfo === false) {
        header("Location:?user=" . $_SESSION["username"]);
    } else {
        header("Location:?user=" . $userInfo["username"]);
    }
}

if (isset($_GET["user"])) {
    $username = $_GET["user"];
} elseif (isset($_SESSION["username"])) {
    header("Location:?user=" . $_SESSION["username"]);
} else {
    header("Location:../login");
    exit();
}

//Get the user's profile information using the username
$userInfo = searchDb($conn, null, $username, null);

//If the user information is not found then display the error message
if ($userInfo === false) {
    header("Location:?error=notfound");
    exit();
}

//Turns off edit mode if the user is not logged in to the profile they are visiting. If not then edit mode is disabled.
if (isset($_GET["edit"]) and $_SESSION["username"] == $username) {
    $editMode = $_GET["edit"];
    echo "Edit mode";
} elseif (isset($_GET["edit"]) and $_SESSION["admin"] == 1) {
    $editMode = $_GET["edit"];
    echo "Edit mode";
} else {
    $editMode = "false";
    echo "Edit mode no";
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

if (isset($_POST["comment"])) {

    foreach ($_POST as $post) {
        $i++;
        $post = cleanInput($post);
        $_POST[$i] = $post;
    }

    $sql = "INSERT INTO reports(userid, pictureid, comment, reporterid) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$userInfo["id"], null, $_POST["comment"], $_SESSION["userid"]]);

    header("Location:?user=" . $_GET["user"] . "&error=Report%20sent!");
}

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
    <main class="mt-32 w-full xl:w-[800px] mx-auto xl:bg-base-200 rounded-2xl">
        <?php
        if (isset($editMode) and $editMode == "false") {
            echo '<img src=' . $banner . ' alt="" class="w-full h-36 rounded-t-2xl object-cover">
                  <img src=' . $picture . ' alt="" class="h-32 w-32 rounded-full xl:bg-base-200 p-2 -mt-16 ml-12 inline object-cover">';
        } elseif (isset($editMode) and $editMode == "true") {
            echo '<form action="/pictashare/includes/saveprofile.php" method="post" enctype="multipart/form-data">';

            echo '<label class="cursor-pointer">
                    <input type="file" class="hidden" name="banner" id="image1" accept"image/apng, image/avif, image/gif, image/jpeg, image/png, image/webp"/>
                    <div id="preview1" class="file-upload xl:bg-base-200 w-full h-36 rounded-t-2xl overflow-hidden flex items-center">
                        <img src=' . $banner . ' alt="" class="object-cover">
                    </div>
                 </label>';

            echo '<label class="h-32 w-32 -mt-16 ml-12 block relative cursor-pointer">
                    <input type="file" class="hidden" name="picture" id="image2" accept"image/apng, image/avif, image/gif, image/jpeg, image/png, image/webp"/>
                    <div id="preview2" class="file-upload rounded-full xl:bg-base-200 p-2 overflow-hidden w-full h-full flex items-center">
                        <img src=' . $picture . ' alt="" class="rounded-full object-cover w-full h-full">
                    </div>
                 </label>';
        }
        ?>
        <div class="flex flex-col xl:flex-row px-12 p-4">
            <div class="w-full">
                <?php
                if (isset($editMode) and $editMode == "true") : ?>
                    <input type="text" name="nickname" placeholder="Nickname" value="<?php echo $userInfo["nickname"] ?>" class="form-input profile-input">
                    <input type="text" name="username" placeholder="Nickname" value="<?php echo $userInfo["username"] ?>" class="form-input profile-input">
                    <textarea name="description" cols="30" rows="10" class="form-input profile-input"><?php $userInfo["description"] ?></textarea>
                <?php else : ?>
                    <?php if ($userInfo["nickname"] !== null) : ?>
                        <h1 class="font-semibold text-2xl"><?php echo $userInfo["nickname"] ?></h1>
                    <?php else : ?>
                        <h1 class="font-semibold text-2xl"><?php echo ucfirst($userInfo["username"]) ?></h1>
                <?php endif;
                endif; ?>

                <?php
                echo '<h2 class="-mt-1 opacity-60">@' . $userInfo["username"] . '</h2>';
                echo '<p class="mt-4">' . $userInfo["description"] . '</p>';
                ?>
            </div>
            <div class="xl:w-[800px] w-full flex justify-center xl:justify-end items-center gap-2 h-4 mt-8 xl:mt-0">
                <?php
                if (isset($_SESSION["username"]) and $_SESSION["username"] == $username or $_SESSION["admin"] == 1) {
                    if (isset($editMode) and $editMode == "true") : ?>
                        <a class="button outline" href="?user=<?php echo $_GET["user"] ?>&edit=false">Cancel</a>
                        <button type="submit" class="button" name="submit">Save profile</button>
                    <?php else : ?>
                        <a href="?user=<?php echo $_GET["user"] ?>&edit=true" class="button outline">Edit profile</a>
                        <a class="button outline" href="../upload">Upload</a>
                <?php endif;
                } ?>
                <div class="dropdown dropdown-end">
                    <label tabindex="0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-more-vertical">
                            <circle cx="12" cy="12" r="1"></circle>
                            <circle cx="12" cy="5" r="1"></circle>
                            <circle cx="12" cy="19" r="1"></circle>
                        </svg></label>
                    <ul tabindex="0" class="dropdown-content shadow bg-base-100 rounded-box w-40 mt-4 text-sm flex flex-col">
                        <a onclick="reportModalToggle()">
                            <li class="rounded-t-lg">Report</li>
                        </a>
                    </ul>
                </div>
            </div>

        </div>
        <div class="flex w-full gap-2 xl:gap-4 p-4">
            <div class="container 1 w-full flex flex-col gap-8 h-fit"></div>
            <div class="container 2 w-full flex flex-col gap-8 h-fit"></div>
            <div class="container 3 w-full flex flex-col gap-8 h-fit"></div>
        </div>
        <div class="flex justify-center items-center w-full mt-4 pb-4">
            <button id="load-more" class="button">Load More</button>
            <p id="loadedAllText" class="hidden">There is nothing more to load</p>
        </div>
    </main>
</body>

<div class="fixed w-full h-full top-0 left-0 justify-center items-center bg-base-300 bg-opacity-50 z-50 hidden" id="report-modal">
    <div class="w-96 bg-base-200 rounded-2xl p-4">
        <form action="" method="post" class="flex flex-col justify-between">
            <div>
                <h2 class="text-xl font-bold mb-1">Report</h2>
                <p class="text-sm opacity-80">State a reason to report this user:</p>

                <textarea name="comment" cols="30" rows="5" class="form-input profile-input w-full"></textarea>

            </div>
            <div class="w-full flex justify-end gap-2">
                <a onclick="reportModalToggle()" class="bg-base-100 px-4 py-2 rounded-lg cursor-pointer hover:bg-opacity-50 transition-colors duration-200">Cancel</a>
                <button class="bg-error text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200">Report</a>
            </div>
        </form>
    </div>
</div>

</html>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script>
    let showReportModal = false;
    let reportModal = document.getElementById("report-modal");

    function reportModalToggle() {
        if (showReportModal == false) {
            reportModal.style.display = "flex";
            showReportModal = true;
        } else {
            reportModal.style.display = "none";
            showReportModal = false;
        }
    }

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

    $(document).ready(function() {
        var containers = $('.container');
        var button = $('#load-more');
        var loadedAllText = $('#loadedAllText');
        let searchParams = new URLSearchParams(window.location.search)
        const user = searchParams.get('user')
        var isLoading = false;
        var start = 0;
        var limit = 1;
        let loadedAll = false;

        button.click(loadMore);


        function buttonChange() {
            button.css("display", "none");
            loadedAllText.css("display", "block");
        }

        async function loadMore() {
            if (!isLoading) {
                isLoading = true;
                for (var i = 0; i < 12; i++) {
                    var data = {
                        start: start,
                        limit: limit,
                        user: user
                    };
                    start += limit;
                    try {
                        var response = await $.ajax({
                            url: 'get-data.php',
                            type: 'GET',
                            data: data,
                            dataType: 'json'
                        });
                        if (response.length == 0) {
                            loadedAll = true;
                            buttonChange();
                        } else {
                            var shortestContainer;
                            var shortestHeight = Infinity;
                            $.each(response, function(index, item) {
                                containers.each(function() {
                                    var height = $(this).height();

                                    if (height < shortestHeight) {
                                        shortestContainer = $(this);
                                        shortestHeight = height;
                                    }
                                });

                                shortestContainer.append(item);
                            });
                        }
                    } catch (error) {
                        console.log(error);
                    }
                }
                isLoading = false;
            }
        };
        loadMore();


    });
</script>
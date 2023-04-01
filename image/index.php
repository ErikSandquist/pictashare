<?php
include '../nav.php';

require_once '../includes/db.php';
require_once '../includes/functions.php';

$imageInfo = loadPicture($conn, $_GET["id"], null, null, null);
$userInfo = searchDb($conn, null, null, $imageInfo["userid"]);

if ($userInfo["picture"] == null) {
    $picture = '/pictashare/images/default/profile.svg';
} else {
    $picture = 'data:image/jpeg;base64,' . base64_encode($userInfo['picture']);
}

$imageTagsString = $imageInfo["tags"];
$imageTagsString = str_replace('"', '', $imageTagsString);
$imageTagsString = str_replace('[', '', $imageTagsString);
$imageTagsString = str_replace(']', '', $imageTagsString);
$tags = explode(",", $imageTagsString);
?>

<main class="mt-40 w-[1000px] mx-auto rounded-2xl flex flex-col p-4 gap-8">
    <div class="flex gap-8 relative">
        <div class="flex flex-col gap-4 w-full h-auto">
            <div class="absolute top-0 -left-10 bg-base-200 w-10 h-20 flex flex-col items-center justify-around rounded-l-2xl pl-[2px]">
                <div id="upvote" onclick="vote(1)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hover:stroke-[3px] transition-all duration-200 cursor-pointer">
                        <polyline points="18 15 12 9 6 15"></polyline>
                    </svg>
                </div>
                <div id="downvote" onclick="vote(0)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hover:stroke-[3px] transition-all duration-200 cursor-pointer">
                        <polyline points=" 6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
            </div>
            <img src=" <?php echo 'data:image/jpeg;base64,' . base64_encode($imageInfo["picture"]); ?>" alt="" class="w-full h-auto rounded-2xl rounded-tl-none min-h-[300px] object-cover bg-base-200">
        </div>
        <div class="w-6/12 h-inherit bg-base-200 rounded-2xl p-4 flex flex-col">
            <div class="flex gap-2 text-xl leading-4 tag-container">
                <?php
                echo '<img src=' . $picture . ' alt="" class="rounded-full object-cover w-12 h-12">';
                echo $imageInfo["description"];
                foreach ($tags as $tag) {
                    echo '<p> ' . $tag . '</p>';
                }
                ?>
            </div>
        </div>
    </div>
    <div class="w-full h-auto rounded-2xl bg-base-200 p-4">
        <h1 class="font-semibold text-2xl">Comments</h1>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script>
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const pictureid = urlParams.get('id');

    function vote(type) {
        $.ajax({
            url: 'vote.php',
            method: 'GET',
            data: {
                id: pictureid,
                type: type
            },
            success: function(data) {
                console.log(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Request failed. Status: ' + textStatus + ', error thrown: ' + errorThrown);
            }
        });
    }
</script>
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
            <img src=" <?php echo 'data:image/jpeg;base64,' . base64_encode($imageInfo["picture"]); ?>" alt="" class="w-full h-auto rounded-2xl min-h-[300px] object-cover">
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
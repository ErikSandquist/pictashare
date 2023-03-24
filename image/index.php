<?php
include '../nav.php';

require_once '../includes/db.php';
require_once '../includes/functions.php';

$imageInfo = loadPicture($conn, $_GET["id"], "DESC", null, null);

?>

<main class="mt-40 w-[1000px] mx-auto bg-base-200 rounded-2xl flex p-4">
    <div class="flex flex-col gap-4 w-full">
        <img src=" <?php echo 'data:image/jpeg;base64,' . base64_encode($imageInfo["picture"]); ?>" alt="" class="w-full h-auto rounded-lg">
        <div>
            <h2 class="font-semibold text-2xl">Comments</h2>
        </div>
    </div>
    <div class="w-6/12">
        <?php
        echo $imageInfo["userid"]
        ?>
    </div>
</main>
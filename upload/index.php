<?php
include "../nav.php";

if (isset($_POST["submit"])) {
    require_once "../includes/db.php";
    require_once "../includes/functions.php";

    if ($_FILES["image"]["name"] != "") {
        $image = $_FILES["image"];

        $image = $image['tmp_name'];
        $image = file_get_contents($image);
    } else {
        header("Location:?error=noimage");
        exit();
    }

    $description = $_POST["description"];
    $tags = $_POST["tags"];
    $userid = $_SESSION["userid"];

    function upload($conn, $image, $description, $tags, $userid)
    {
        $sql = "INSERT INTO pictures (picture, description, tags, userid) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$image, $description, $tags, $userid]);

        $stmt = null;

        $sql = "SELECT id FROM pictures WHERE userid = ? ORDER BY createdate DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userid]);
        $result = $stmt->fetch()[0];

        $stmt = null;

        header("Location:../image?id=" . $result);
    }

    upload($conn, $image, $description, $tags, $userid);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
</head>

<?php
if (!isset($_POST["submit"])) : ?>

    <body class="mt-32">
        <main class="mt-40 w-[800px] mx-auto bg-base-200 rounded-2xl p-4">
            <h1 class="font-semibold text-3xl mb-4">Upload picture to gallery</h1>
            <form action="" method="post" enctype="multipart/form-data" class="w-full flex flex-col justify-center mb-0">
                <label for="image" id="imageBox" class="w-full h-auto block min-h-[400px] max-h-[500px] form-input profile-input relative p-0 overflow-hidden">
                    <div id="preview" class="w-full min-h-[400px] flex justify-center items-center">
                        <div class="w-full h-full absolute flex justify-center items-center">
                            <span class="form-input profile-input !border-white">Select file</span>
                        </div>
                    </div>
                </label>
                <input type="file" name="image" id="image" class="hidden">
                <textarea name="description" cols="30" rows="3" placeholder="Description" class="form-input profile-input w-full"></textarea>
                <input type="text" name="tags" placeholder="Tags (separated by commas)" class="form-input profile-input w-full">
                <button type="submit" name="submit" class="button mx-auto">Submit</button>
            </form>
        </main>
    </body>

<?php endif; ?>

</html>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script>
    function imagePreview(fileInput) {
        if (fileInput.files && fileInput.files[0]) {
            var fileReader = new FileReader();
            fileReader.onload = function(event) {
                $('#preview').html('<img src="' + event.target.result + '" class="w-auto h-full max-h-[500px]"/><div class="w-full h-full absolute flex justify-center items-center"><span class="form-input profile-input !border-white">Select file</span></div>');
            };
            fileReader.readAsDataURL(fileInput.files[0]);
        }
    }
    $("#image").change(function() {
        imagePreview(this);
    });
</script>
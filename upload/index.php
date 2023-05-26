<?php
include "../nav.php";

if (isset($_POST["submit"])) {
    require_once "../includes/db.php";
    require_once "../includes/functions.php";

    if ($_FILES["image"]["name"] != "") {
        $image = $_FILES["image"];

        // get the file extension
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);

        // check if the file is an image
        if (in_array(strtolower($ext), array('jpg', 'jpeg', 'png', 'gif', 'avif', 'webp'))) {
            // read the file contents
            $image = $image['tmp_name'];
            $image = file_get_contents($image);
        } else {
            header("Location:?error=Invalid%20image");
            exit();
        }
    } else {
        header("Location:?error=No%20image");
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
            <form action="" method="post" enctype="multipart/form-data" class="w-full flex flex-col justify-center mb-0" onkeydown="return event.key != 'Enter'">
                <label for="image" id="imageBox" class="w-full h-auto block min-h-[400px] max-h-[500px] form-input profile-input relative p-0 overflow-hidden">
                    <div id="preview" class="w-full min-h-[400px] flex justify-center items-center">
                        <div class="w-full h-full absolute flex justify-center items-center">
                            <span class="form-input profile-input !border-white">Select file</span>
                        </div>
                    </div>
                    <input type="file" name="image" id="image" class="hidden" accept"image/apng, image/avif, image/gif, image/jpeg, image/png, image/webp">
                </label>
                <textarea name="description" cols="30" rows="3" placeholder="Description" class="form-input profile-input w-full"></textarea>
                <input type="text" name="" id="tags" placeholder="Tags, press enter to add" class="form-input profile-input w-full" autocomplete="off">
                <input type="text" name="tags" id="hiddenInput" class="hidden">
                <div class="tag-container flex gap-2"></div>
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

    let input, hashtagArray, container, t;

    input = document.querySelector('#tags');
    hiddenInput = document.querySelector('#hiddenInput');
    container = document.querySelector('.tag-container');
    hashtagArray = [];

    input.addEventListener('keyup', () => {
        if (event.which == 13 && input.value.length > 0) {
            event.preventDefault();
            var text = document.createTextNode(input.value);
            var p = document.createElement('p');
            hashtagArray.push(text["wholeText"]);
            container.appendChild(p);
            p.appendChild(text);
            p.classList.add('tag');
            input.value = '';

            let deleteTags = document.querySelectorAll('.tag');

            for (let i = 0; i < deleteTags.length; i++) {
                deleteTags[i].addEventListener('click', () => {
                    container.removeChild(deleteTags[i]);
                    hashtagArray.splice(i, 1);
                    hiddenInput.value = JSON.stringify(hashtagArray);
                });
            }
            hiddenInput.value = JSON.stringify(hashtagArray);
        }
    });
</script>
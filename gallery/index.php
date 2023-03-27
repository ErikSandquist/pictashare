<?php
include "../nav.php";

require_once "../includes/db.php";

$sql = "SELECT * FROM pictures ORDER BY createdate DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$pictures = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
</head>

<body>
    <?php
    foreach ($pictures as $image) {
        echo '<img src="data:image/jpeg;base64,' . base64_encode($image["picture"]) . '" alt="" class="w-40 h-40">';
    }
    ?>
    <div id="container"></div>
    <button id="load-more">Load More</button>
</body>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        var container = $('#container');
        var loadMore = $('#load-more');
        var isLoading = false;
        var start = 0;
        var limit = 2;
        loadMore.click(function() {
            if (!isLoading) {
                isLoading = true;
                var data = {
                    start: start,
                    limit: limit
                };
                $.ajax({
                    url: 'get-data.php',
                    type: 'GET',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        $.each(response, function(index, item) {

                            container.append(item);
                        });
                        start += limit;
                        isLoading = false;
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        console.log(status);
                        console.log(error);
                    }
                });
            }
        });
    });
</script>

</html>
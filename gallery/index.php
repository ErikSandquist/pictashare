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

<body class="mt-32">
    <div class="flex w-full gap-8 p-4">
        <div class="container w-full flex flex-col gap-8"></div>
        <div class="container w-full flex flex-col gap-8"></div>
        <div class="container w-full flex flex-col gap-8"></div>
    </div>
    <button id="load-more">Load More</button>
</body>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        var containers = $('.container');
        var loadMore = $('#load-more');
        var isLoading = false;
        var start = 0;
        var limit = 4;

        loadMore.click(function() {
            if (!isLoading) {
                isLoading = true;
                var data = {
                    start: start,
                    limit: limit
                };
                start += limit;
                $.ajax({
                    url: 'get-data.php',
                    type: 'GET',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        $.each(response, function(index, item) {
                            var shortestContainer = containers.eq(0);
                            containers.each(function() {
                                if ($(this).height() < shortestContainer.height()) {
                                    shortestContainer = $(this);
                                }
                            });
                            shortestContainer += item;
                        });
                        isLoading = false;
                    },
                    error: function(xhr, textStatus, error) {
                        console.log(xhr.responseText);
                        console.log(xhr.statusText);
                        console.log(textStatus);
                        console.log(error);
                    }

                });

            }
        });
    });
</script>

</html>
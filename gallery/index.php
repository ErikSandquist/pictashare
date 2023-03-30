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

<body class="pt-32 pb-8">
    <div class="flex w-full gap-8 p-4">
        <div class="container 1 w-full flex flex-col gap-8 h-fit"></div>
        <div class="container 2 w-full flex flex-col gap-8 h-fit"></div>
        <div class="container 3 w-full flex flex-col gap-8 h-fit"></div>
    </div>
    <div class="flex justify-center items-center w-full mt-4">
        <button id="load-more" class="button">Load More</button>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        var containers = $('.container');
        var button = $('#load-more');
        var isLoading = false;
        var start = 0;
        var limit = 1;

        button.click(loadMore);

        async function loadMore() {
            if (!isLoading) {
                isLoading = true;
                for (var i = 0; i < 16; i++) {
                    var data = {
                        start: start,
                        limit: limit
                    };
                    start += limit;
                    try {
                        var response = await $.ajax({
                            url: 'get-data.php',
                            type: 'GET',
                            data: data,
                            dataType: 'json'
                        });
                        var shortestContainer;
                        var shortestHeight = Infinity;
                        $.each(response, function(index, item) {
                            containers.each(function() {
                                var height = $(this).height();
                                console.log(height);

                                if (height < shortestHeight) {
                                    shortestContainer = $(this);
                                    shortestHeight = height;
                                }
                            });

                            shortestContainer.append(item);
                            console.log(shortestContainer);
                        });
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

</html>
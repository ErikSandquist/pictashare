<?php

include "../../nav.php";
include "../../adminnav.php";

require_once "../../includes/db.php";
require_once "../../includes/functions.php";

if (!isset($_SESSION["admin"]) or $_SESSION["admin"] == 0) {
    header("Location:/pictashare");
}

$sql = "SELECT * FROM reports ORDER BY id DESC";

$stmt = $conn->prepare($sql);

$stmt->execute();

$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Just a smaller nav, just a preference and users wont see it anyways -->
<style>
    nav div {
        font-size: 32px !important;
    }

    nav div svg {
        height: 32px;
        width: 32px;
    }
</style>

<main class="flex justify-center">
    <table class="mx-8 admintable">
        <tbody>
            <tr class="[&>td]:!h-fit">
                <td>Id</td>
                <td>Picture Id</td>
                <td>User Id</td>
                <td>Comment</td>
                <td>Reporter Id</td>
                <td>Done</td>
                <td>Show</td>
            </tr>
            <?php foreach ($reports as $report) : ?>
                <tr class="text-sm">
                    <td><?php echo $report["id"] ?></td>
                    <td><?php echo $report["pictureid"] ?></td>
                    <td><?php echo $report["userid"] ?></td>
                    <td><?php echo $report["comment"] ?></td>
                    <td><?php echo $report["reporterid"] ?></td>
                    <td><input type="checkbox" name="done" class="checkbox form-input w-2 h-2 p-2.5 !border-white" id="report<?php echo $report["id"] ?>" onclick="reportDone(<?php echo $report["id"] ?>)" <?php if ($report["status"] == 1) {
                                                                                                                                                                                                                echo "checked";
                                                                                                                                                                                                            } ?>></td>
                    <td>
                        <?php if (isset($report["pictureid"])) : ?>
                            <a href="/pictashare/image/?id=<?php echo $report["pictureid"] ?>">
                                Visit
                            </a>
                        <?php else : ?>
                            <a href="/pictashare/user/?id=<?php echo $report["userid"] ?>">
                                Visit
                            </a>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script>
    function reportDone(reportId) {
        $.ajax({
            url: 'done.php',
            method: 'GET',
            data: {
                id: reportId,
            },
            type: 'json',
            success: function(data) {

            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Request failed. Status: ' + textStatus + ', error thrown: ' + errorThrown);
            }
        });
    }
</script>
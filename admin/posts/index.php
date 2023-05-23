<?php

include "../../nav.php";
include "../../adminnav.php";

require_once "../../includes/db.php";
require_once "../../includes/functions.php";

if ($_SESSION["admin"] == 0) {
    header("Location:/pictashare");
}

$sql = "SELECT * FROM pictures ORDER BY id DESC";

$stmt = $conn->prepare($sql);

$stmt->execute();

$pictures = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <td>Image</td>
                <td>Id</td>
                <td>Description</td>
                <td>Votes</td>
                <td>Views</td>
                <td>Creation date</td>
                <td>Owner</td>
                <td>Visit</td>
                <td>Edit</td>
                <td>Delete</td>
            </tr>
            <?php foreach ($pictures as $picture) : ?>
                <tr class="text-sm" id="user<?php echo $picture["id"] ?>">
                    <?php
                    $image = 'data:image/jpeg;base64,' . base64_encode($picture['picture']);
                    ?>
                    <td><img src='<?php echo $image ?>' alt="" class="max-w-xs max-h-16 object-cover bg-base-200"></td>
                    <td><?php echo $picture["id"] ?></td>
                    <td><?php echo $picture["description"] ?></td>
                    <td><?php echo $picture["votes"] ?></td>
                    <td><?php echo $picture["views"] ?></td>
                    <td><?php echo $picture["createdate"] ?></td>
                    <td><?php echo $picture["userid"] ?></td>
                    <td>
                        <a href="/pictashare/image/?id=<?php echo $picture["id"] ?>">
                            Visit
                        </a>
                    </td>
                    <td>
                        <a href="/pictashare/image/?id=<?php echo $picture["id"] ?>&edit=true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </a>
                    </td>
                    <td>
                        <a onclick="modalDelete(<?php echo $picture["id"]; ?>)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2">
                                <path d="M3 6h18"></path>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                <line x1="14" x2="14" y1="11" y2="17"></line>
                            </svg>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<div class="fixed w-full h-full top-0 left-0 justify-center items-center bg-base-300 bg-opacity-50 z-50 hidden" id="modalDelete">
    <div class="w-96 h-48 bg-base-200 rounded-2xl p-4 flex flex-col justify-between">
        <div>
            <h2 class="text-xl font-bold mb-1">Are you sure you want to delete the account?</h2>
            <p class="text-sm opacity-80">There is no going back, removing the account will permanently remove the user and all linked data including pictures, comments, votes.</p>
        </div>
        <div class="w-full flex justify-end gap-2">
            <a id="denyButton" class="bg-base-100 px-4 py-2 rounded-lg cursor-pointer hover:bg-opacity-50 transition-colors duration-200">No</a>
            <a id="confirmButton" class="bg-error text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200">Delete</a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script>
    let userId = null;
    let showModalDelete = false;
    const modalButtonDelete = document.getElementById("modalDelete");

    function modalDelete(userId) {
        showModalDelete = true;
        modalButtonDelete.style.display = "flex";

        const confirmButton = document.getElementById('confirmButton');
        confirmButton.addEventListener('click', () => {
            deleteModal(userId);
            closeModal();
        });

        const cancelButton = document.getElementById('denyButton');
        cancelButton.addEventListener('click', () => {
            closeModal();
        });
    }

    function closeModal() {
        showModalDelete = false;
        modalButtonDelete.style.display = "none";
    }

    function deleteModal(userId) {
        $.ajax({
            url: 'delete.php',
            method: 'GET',
            data: {
                id: userId,
            },
            success: function(data) {
                let userColumn = document.getElementById('user' + data)

                userColumn.remove();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Request failed. Status: ' + textStatus + ', error thrown: ' + errorThrown);
            }
        });
    }
</script>
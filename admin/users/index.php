<?php

include "../../nav.php";
include "../../adminnav.php";

require_once "../../includes/db.php";
require_once "../../includes/functions.php";

$userInfo = searchDb($conn, null, null, $_SESSION["userid"]);

if ($_SESSION["admin"] == 0) {
    header("Location:/pictashare");
}

$sql = "SELECT * FROM users ORDER BY id DESC";

$stmt = $conn->prepare($sql);

$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

<table class="mx-8 admintable">
    <tbody>
        <tr class="[&>td]:!h-fit">
            <td>Picture</td>
            <td>Banner</td>
            <td>Id</td>
            <td>Username</td>
            <td>Nickname</td>
            <td>Email</td>
            <td>Description</td>
            <td>Votes</td>
            <td>Views</td>
            <td>Creation date</td>
            <td>Admin</td>
            <td>Visit</td>
            <td>Edit</td>
            <td>Delete</td>
        </tr>
        <?php foreach ($users as $user) : ?>
            <tr>
                <?php
                if ($user["picture"] == null) {
                    $picture = '/pictashare/images/default/profile.svg';
                } else {
                    $picture = 'data:image/jpeg;base64,' . base64_encode($user['picture']);
                }

                if ($user["banner"] == null) {
                    $picture = '/pictashare/images/default/profile.svg';
                } else {
                    $picture = 'data:image/jpeg;base64,' . base64_encode($user['banner']);
                }
                ?>
                <td><img src='<?php echo $picture ?>' alt="" class="max-w-xs max-h-16 object-cover bg-base-200"></td>
                <td><img src='<?php echo $banner ?>' alt="" class="max-w-xs max-h-16 object-cover bg-base-200"></td>
                <td><?php echo $user["id"] ?></td>
                <td><?php echo $user["username"] ?></td>
                <td><?php echo $user["nickname"] ?></td>
                <td><?php echo $user["email"] ?></td>
                <td><?php echo $user["description"] ?></td>
                <td><?php echo $user["votes"] ?></td>
                <td><?php echo $user["views"] ?></td>
                <td><?php echo $user["createdate"] ?></td>
                <td><?php echo $user["admin"] ?></td>
                <td><a href="/pictashare/profile/?user=<?php echo $user["username"] ?>">Visit</a></td>
                <td><a href="/pictashare/profile/?user=<?php echo $user["username"] ?>&edit=true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg></a></td>
                <td>Remove</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
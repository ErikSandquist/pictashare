<?php

include "../../nav.php";

require_once "../../includes/db.php";
require_once "../../includes/functions.php";

$userInfo = searchDb($conn, null, null, $_SESSION["userid"]);

if ($_SESSION["admin"] == 0) {
    header("Location:/pictashare");
}

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
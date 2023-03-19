<?php

include "../nav.php";

if (isset($_GET["user"])) {
    $username = $_GET["user"];
} elseif (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    header("Location:../login");
    exit();
}

require_once("../includes/db.php");
require_once("../includes/functions.php");

$userInfo = searchDb($conn, null, $username);

if ($userInfo === false) {
    header("Location:?error=notfound");
    exit();
}

if (isset($_GET["error"])) {
    exit();
}

$date1 = new DateTime($userInfo["createdate"]);
$date2 = new DateTime(date("Y/m/d"));
$days  = $date2->diff($date1)->format('%a');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $userInfo["username"] ?></title>
</head>

<body>
    <main class="mt-32 w-[900px] h-full mx-auto bg-base-200 rounded-3xl">
        <img src="<?php echo 'data:image/jpeg;base64,' . base64_encode($userInfo['picture']) ?>" alt="" class="w-full h-40 rounded-t-3xl">
        <div class="p-8"></div>
    </main>

</body>

</html>
<?php
session_start();

if (isset($_SESSION["username"])) {

    require_once("includes/db.php");
    require_once("includes/functions.php");

    $userInfo = searchDb($conn, null, $_SESSION["username"], null);

    if ($userInfo["picture"] == null) {
        $picture = '/pictashare/images/default/profile.svg';
    } else {
        $picture = 'data:image/jpeg;base64,' . base64_encode($userInfo['picture']);
    }
}

ob_start();
?>

<head>
    <link rel="stylesheet" href="/pictashare/output.css">
</head>
<nav class="z-10 fixed top-0 left-0 w-full h-32 flex justify-between p-8 px-40 items-center backdrop-blur-xl">
    <div class="text-7xl">
        <a href="/pictashare" class="flex gap-4 items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" ; transition: 0.3s;">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <circle cx="9" cy="9" r="2"></circle>
                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
            </svg>
            Pictashare
        </a>
    </div>
    <div class="flex items-center h-fit gap-4">
        <a href="/pictashare/gallery" class="button ghost">Gallery</a>
        <?php
        if (isset($_SESSION['userid'])) : ?>
            <a href="/pictashare/upload/" class="button outline">Upload</a>
            <div class="dropdown dropdown-end">
                <label tabindex="0"> <img src='<?php echo $picture ?>' alt="" class="h-14 w-14 rounded-full object-cover bg-base-200"></label>
                <ul tabindex="0" class="dropdown-content shadow bg-base-100 rounded-box w-52 mt-4 text-lg flex flex-col">
                    <a href="/pictashare/profile/?user=<?php echo $_SESSION["username"] ?>">
                        <li class="rounded-t-lg">Profile</li>
                    </a>
                    <a href="/pictashare/random">
                        <li>Suprise me</li>
                    </a>
                    <?php if ($userInfo["admin"] == 1) : ?>
                        <a href="/pictashare/admin">
                            <li>Admin page</li>
                        </a>
                    <?php endif; ?>
                    <a href="/pictashare/logout">
                        <li class="rounded-b-lg">Log out</li>
                    </a>
                </ul>
            </div>
        <?php else : ?>
            <a href="/pictashare/login" class="button outline">Login</a>
            <a href="/pictashare/signup" class="button">Sign up</a>
        <?php endif; ?>
    </div>
</nav>
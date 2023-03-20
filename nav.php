<?php
session_start();
?>

<head>
    <link rel="stylesheet" href="/pictashare/output.css">
</head>
<nav class="z-10 fixed top-0 left-0 w-full h-32 flex justify-between p-8 px-40 items-center">
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
    <div class="flex h-fit gap-4">
        <a href="gallery" class="button ghost">Gallery</a>
        <?php
        if (isset($_SESSION['userid'])) {
            echo '<a href="/pictashare/profile/?user=' . $_SESSION["username"] . '" class="button outline">Profile</a>';
        } else {
            echo '<a href="/pictashare/login" class="button outline">Login</a>
                <a href="/pictashare/signup" class="button">Sign up</a>';
        }
        ?>
    </div>
</nav>
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

<?php if (isset($_GET["error"])) : ?>
    <div class="absolute top-32 w-full flex justify-center z-50">
        <div class="alert alert-warning shadow-lg">
            <div class="flex gap-2 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-black flex-shrink-0 h-8 w-8" fill="none" viewBox="0 0 24 24">
                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                    <line x1="12" x2="12" y1="9" y2="13"></line>
                    <line x1="12" x2="12.01" y1="16" y2="17"></line>
                </svg>
                <span class="text-black"><?php echo $_GET["error"] ?></span>
            </div>
        </div>
    </div>
<?php endif; ?>

<head>
    <link rel="stylesheet" href="/pictashare/output.css">
</head>
<nav class="z-10 fixed top-0 left-0 w-full h-32 flex justify-between p-8 px-8 xl:px-40 items-center backdrop-blur-xl">
    <div class="text-5xl">
        <a href="/pictashare" class="flex gap-4 items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" ; transition: 0.3s;">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <circle cx="9" cy="9" r="2"></circle>
                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
            </svg>
            <span class="hidden xl:inline">Pictashare</span>
        </a>
    </div>
    <div class="flex items-center h-fit gap-4">
        <a href="/pictashare/gallery" class="!hidden !sm:block button ghost">Gallery</a>
        <?php
        if (isset($_SESSION['userid'])) : ?>
            <a href="/pictashare/upload/" class="button outline">Upload</a>
            <div class="dropdown dropdown-end">
                <label tabindex="0"> <img src='<?php echo $picture ?>' alt="" class="h-14 w-14 rounded-full object-cover bg-base-200 cursor-pointer"></label>
                <ul tabindex="0" class="dropdown-content shadow bg-base-100 rounded-box w-52 mt-4 text-lg flex flex-col">
                    <a href="/pictashare/profile/?user=<?php echo $_SESSION["username"] ?>">
                        <li class="rounded-t-lg">Profile</li>
                    </a>
                    <a href="/pictashare/random">
                        <li>Suprise me</li>
                    </a>
                    <a href="/pictashare/gallery" class="!sm:hidden">
                        <li>Gallery</li>
                    </a>
                    <?php if ($_SESSION["admin"] == 1) : ?>
                        <a href="/pictashare/admin">
                            <li>Admin page</li>
                        </a>
                    <?php endif; ?>
                    <a onclick="modal()" class="cursor-pointer">
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

<div class="fixed w-full h-full top-0 left-0 justify-center items-center bg-base-300 bg-opacity-50 z-50 hidden" id="modal">
    <div class="w-96 h-48 bg-base-200 rounded-2xl p-4 flex flex-col justify-between">
        <div>
            <h2 class="text-xl font-bold mb-1">Are you sure you want to log out?</h2>
            <p class="text-sm opacity-80">You will be required to enter your information again to access the account</p>
        </div>
        <div class="w-full flex justify-end gap-2">
            <a onclick="modal()" class="bg-base-100 px-4 py-2 rounded-lg cursor-pointer hover:bg-opacity-50 transition-colors duration-200">No, stay</a>
            <a href="/pictashare/logout" class="bg-error text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200">Log out</a>
        </div>
    </div>
</div>

<script>
    let showModal = false;
    let modalButton = document.getElementById("modal");

    function modal() {
        if (showModal == false) {
            modalButton.style.display = "flex";
            showModal = true;
        } else {
            modalButton.style.display = "none";
            showModal = false;
        }
    }
</script>
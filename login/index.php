<?php
if (isset($_SESSION["user"])) {
    header("Location:index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/pictashare/output.css">
</head>

<body class="h-full flex items-center justify-center">

    <form action="../includes/login.php" method="post" class="flex flex-col w-60 h-full">
        <h1 class="text-5xl text-center mb-6">Login</h1>
        <label for="username">Username</label>
        <input type="text" name="username" id="" class="form-input">
        <!-- <label for="email">Email</label>
        <input type="email" name="email" id="" class="form-input"> -->
        <label for="psw">Password</label>
        <input type="password" name="psw" id="" class="form-input">
        <input type="submit" value="submit" name="submit" class="button submit form-input">
    </form>
    <div class="w-6/12 h-screen flex items-center justify-center">
        <picture>
            <source srcset="/pictashare/images/bg/herogradient.webp" type="image/webp">
            <img src="/pictashare/images/bg/herogradient.jpg" alt="" class="absolute w-6/12 h-full right-0 top-0 object-cover">
        </picture>
        <div class="text-7xl z-10 absolute w-6/12 h-full right-0 top-0 flex justify-center items-center">
            <a href="/pictashare" class="flex gap-4 items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" ; transition: 0.3s;">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="9" cy="9" r="2"></circle>
                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                </svg>
                Pictashare
            </a>
        </div>
    </div>
</body>

</html>
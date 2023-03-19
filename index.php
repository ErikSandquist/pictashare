<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pictashare</title>
    <style></style>
</head>

<?php
include "nav.php"
?>

<body>
    <div>
        <picture>
            <source srcset="/pictashare/images/bg/herogradient.webp" type="image/webp">
            <img src="/pictashare/images/bg/herogradient.jpg" alt="" class="absolute w-6/12 h-full right-0 top-0 object-cover -z-20">
        </picture>
    </div>
    <div>
        <img src="/pictashare/images/hero/hero.svg" alt="" class="absolute w-full h-full left-0 top-0 object-cover -z-10">
    </div>
    <section class="w-full h-full flex z-10 gap-40">
        <div class="flex flex-col justify-center pl-40 gap-4">
            <h1 class="text-6xl font-semibold">A new way of sharing pictures</h1>
            <p class="text-2xl font-light">Start today and begin sharing all your photos with just a simple click
                Scroll endlessly and comment on your favorites. Upvote them and see what post gets to the top</p>
            <div class="flex gap-4">
                <a href="gallery" class="button">Scroll the gallery</a>
            </div>
        </div>
        <div class="w-8/12">
            <picture>
                <source srcset="/pictashare/images/hero/phone.avif" type="image/avif">
                <source srcset="/pictashare/images/hero/phone.webp" type="image/webp">
                <img src="/pictashare/images/hero/phone.png" alt="" class="z-10 h-screen w-auto object-cover">
            </picture>
        </div>
    </section>
</body>

</html>
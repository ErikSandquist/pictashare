<?php
include '../nav.php';

require_once '../includes/db.php';
require_once '../includes/functions.php';

$image = loadPicture($conn, $_GET["id"], "DESC", null, null);

?>

<img src="<?php echo 'data:image/jpeg;base64,' . base64_encode($image); ?>" alt="" class="w-40 h-auto">
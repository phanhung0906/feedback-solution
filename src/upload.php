<?php
define('ROOT_DIR', __DIR__);
define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'test'));
include_once ROOT_DIR . '/config/config.'.APPLICATION_ENV.'.php';
$image = imagecreatefrompng($_POST['image']);
$id = $_POST['id'];

imagealphablending($image, false);
imagesavealpha($image, true);
imagepng($image, PAINT_DIR.'/wPaint-' . $id . '.png');

// return image path
//echo '{"img": "uploads/wPaint-' . $id . '.png"}';

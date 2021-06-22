<?php


function openImage($file)
{
    $extension = pathinfo(
        parse_url($file, PHP_URL_PATH),
        PATHINFO_EXTENSION
    );

    switch ($extension) {
        case 'jpg':
        case 'jpeg':
            $img = imagecreatefromjpeg($file);
            break;
        case 'gif':
            $img = imagecreatefromgif($file);
            break;
        case 'png':
            $img = imagecreatefrompng($file);
            break;
        default:
            $img = false;
            break;
    }
    return $img;
}

function ob_get_image($dst)
{
    ob_start();
    imagejpeg($dst, null, 100);
    imagedestroy($dst);
    $data = ob_get_clean();
    return $data;
}

function save_image($dst)
{
    $filename = "IMG_" . time() . ".png";
    $dir = dirname(__DIR__, 3) . '/cropped_images/';
    if (!file_exists($dir)) {
        mkdir($dir);
    }
    $url = home_url() . '/wp-content/cropped_images/' . $filename;
    imagejpeg($dst, $dir . $filename);
    return $url;
}

function my_crop($src, $targ_pos_x, $targ_pos_y, $targ_w, $targ_h)
{
    $dst_r = imagecrop($src, ['x' => $targ_pos_x, 'y' => $targ_pos_y, 'width' => $targ_w, 'height' => $targ_h]);
    return $dst_r;
}

function resize_image($image, $w, $h)
{
    $width = imagesx($image);
    $height = imagesy($image);

    $r = $width / $height;

    if ($w / $h > $r) {
        $newwidth = $h * $r;
        $newheight = $h;
    } else {
        $newheight = $w / $r;
        $newwidth = $w;
    }

    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $image, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}

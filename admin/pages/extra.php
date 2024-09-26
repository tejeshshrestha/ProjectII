<?php

// frontend purpose data

define('SITE_URL', 'http://127.0.0.1/projectII/');
define('ABOUT_IMG_PATH', SITE_URL . 'images/images/about/');
define('CAROUSEL_IMG_PATH', SITE_URL . 'images/images/carousel/');
define('FACILITIES_IMG_PATH', SITE_URL . 'images/images/facilities/');
define('ROOMS_IMG_PATH', SITE_URL . 'images/images/rooms/');
define('USERS_IMG_PATH', SITE_URL . 'images/images/users/');



// backend upload data
define('UPLOAD_IMAGE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/projectII/images/');
define('ABOUT_FOLDER', 'images/about/');
define('C_FOLDER', 'images/carousel/');
define('F_FOLDER', 'images/facilities/');
define('ROOMS_FOLDER', 'images/rooms/');
define('USERS_FOLDER', 'images/users/');


// sendgrid API key

define('SENDGRID_API_KEY', "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");

define('SENDGRID_EMAIL', "tejeshshrestha@gmail.com");
define('SENDGRID_NAME', "User");



function alert($type, $msg)
{
    $bs_class = ($type == "success") ? "alert-success" : "alert-danger";
    echo <<<alert
    <div class="alert $bs_class alert-dismissible fade show custom-alert role="alert">
    <strong class="ms-4">$msg</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div> 
    alert;
}

function redirect($url)
{
    echo "<script>
    window.location.href = '$url';
    </script>";
}

function adminLogin()
{
    session_start();
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        //header("location: index.php");
        echo "<script>
        window.location.href = 'index.php';
        </script>";
    }
}

function uploadImage($image, $folder)
{
    $valid_mime = ['image/jpeg', 'image/png', 'image/webp'];
    $img_mime = $image['type'];

    if (!in_array($img_mime, $valid_mime)) {
        return 'inv_img';               //invalid img mime or format
    } else if (($image['size'] / (1024 * 1024)) > 2) {
        return 'inv_size';              //invalid size greater than 2MB
    } else {
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $rname = 'IMG_' . random_int(11111, 99999) . ".$ext";

        $img_path = UPLOAD_IMAGE_PATH . $folder . $rname;
        if (move_uploaded_file($image['tmp_name'], $img_path)) {
            return $rname;
        } else {
            return 'upload_failed';
        }

    }
}
function deleteImage($image, $folder)
{
}
function uploadSVGImage($image, $folder)
{
}
function uploadUserImage($image)
{
    $valid_mime = ['image/jpeg', 'image/png', 'image/webp'];
    $img_mime = $image['type'];

    if (!in_array($img_mime, $valid_mime)) {
        return 'inv_img';               //invalid img mime or format
    } else {
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $rname = 'IMG_' . random_int(11111, 99999) . ".jpeg";

        $img_path = UPLOAD_IMAGE_PATH . USERS_FOLDER . $rname;

        if ($ext == 'png' || $ext == 'PNG') {
            $img = imagecreatefrompng($image['tmp_name']);
        } else if ($ext == 'webp' || $ext == 'WEBP') {
            $img = imagecreatefromwebp($image['tmp_name']);
        } else {
            $img = imagecreatefromjpeg($image['tmp_name']);

        }
        if (imagejpeg($img, $img_path, 75)) {
            return $rname;
        } else {
            return 'upload_failed';
        }

    }
}





?>
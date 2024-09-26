<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<link
    href="https://fonts.googleapis.com/css2?family=Merienda:wght@300..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
<link rel="stylesheet" href="./css/common.css">

<?php


require('admin/pages/db_config.php');
require('admin/pages/extra.php');

$contact_q = "SELECT * FROM `contact_details` WHERE `s_no`=?";
$settings_q = "SELECT * FROM `settings` WHERE `s_no`=?";

$values = [1];

$contact_r = mysqli_fetch_assoc(select($contact_q, $values, 'i'));
$settings_r = mysqli_fetch_assoc(select($settings_q, $values, 'i'));

session_start();

if ($settings_r['shutdown'] == 1) {
    echo <<<alertbar
    <div class = 'bg-danger text-center p-2 fw-bold'>
    <i class = "bi bi-exclamation-triangle-fill"></i>
    Bookings are temporarily closed!!
    </div>
    alertbar;
}

?>
<?php

// require ('../settings.php');         doesn't work if included!!
require ('../pages/db_config.php');
require ('../pages/extra.php');
adminLogin();

if (isset($_POST['get_general'])) {
    $q = "SELECT * FROM `settings` WHERE `s_no`=?";
    $values = [1];
    $res = select($q, $values, "i");
    $data = mysqli_fetch_assoc($res);

    $json_data = json_encode($data);
    echo $json_data;
}


if (isset($_POST['update'])) {
    $form_data = filteration($_POST);

    $q = "UPDATE `settings` SET `site_title`=?, `site_about`=? WHERE `s_no`=?";
    $values = [$form_data['site_title'], $form_data['site_about'], 1];
    $res = upd($q, $values, "ssi");       //string string AND integer -> ssi

    echo $res;
}

if (isset($_POST['shutdown'])) {
    $form_data = ($_POST['shutdown'] == 0) ? 1 : 0;  //if 0 -> 1 else if 1 -> 0

    $q = "UPDATE `settings` SET `shutdown`=? WHERE `s_no`=?";
    $values = [$form_data, 1];
    $res = upd($q, $values, "ii");

    echo $res;
}

?>
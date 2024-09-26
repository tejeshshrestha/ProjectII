<?php

require('../pages/db_config.php');
require('../pages/extra.php');
adminLogin();

error_reporting(E_ALL);
ini_set('display_errors', 1);



//     print_r($form_data);


if (isset($_POST['get_users'])) {
    $res = selectAll('user-cred');
    $i = 1;
    $path = USERS_IMG_PATH;

    $data = "";

    while ($row = mysqli_fetch_assoc($res)) {

        $verified = "<span class = 'badge bg-warning'><i class='bi bi-x-lg'></span>";

        $del_btn = " <button type='button' onclick= 'remove_user($row[id])' class='btn btn-danger shadow-none btn-sm'>
        <i class='bi bi-trash'></i>";

        if ($row['is_verified']) {
            $verified = "<span class = 'badge bg-success'><i class='bi bi-check-lg'></span>";
            $del_btn = "";
        }

        $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>Active</button>";

        if ($row['status'] == 0) {
            $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-danger btn-sm shadow-none'>Inactive</button>";

        }
        $date = date("d-m-Y", strtotime($row['date-time']));

        $data .= "
        <tr>
            <td>$i</td>
            <td><img src='$path$row[picture]' width='50px'> <br> $row[name]</td>
            <td>$row[email]</td>
            <td>$row[phone]</td>
            <td>$row[address]</td>
            <td>$row[dob]</td>

            <td>$verified</td>
            <td>$status</td>
            <td>$date</td>
            <td>$del_btn</td>
        </tr>
        ";
        $i++;
    }
    echo $data;
}

if (isset($_POST['toggle_status'])) {
    $frm_data = filteration($_POST);

    $q = "UPDATE `user-cred` SET `status`=? WHERE `id`=?";
    $v = [$frm_data['value'], $frm_data['toggle_status']];

    if (upd($q, $v, 'ii')) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['remove_user'])) {
    $frm_data = filteration($_POST);

    $res = del("DELETE FROM `user-cred` WHERE `id`=? and `is_verified`=?", [$frm_data['user_id'], 0], 'ii');

    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['search_user'])) {

    $frm_data = filteration($_POST);

    $query = "SELECT * from `user-cred` where `name` LIke ?";

    $res = select($query, ["%$frm_data[name]%"], 's');
    $i = 1;
    $path = USERS_IMG_PATH;

    $data = "";

    while ($row = mysqli_fetch_assoc($res)) {

        $verified = "<span class = 'badge bg-warning'><i class='bi bi-x-lg'></span>";

        $del_btn = " <button type='button' onclick= 'remove_user($row[id])' class='btn btn-danger shadow-none btn-sm'>
        <i class='bi bi-trash'></i>";

        if ($row['is_verified']) {
            $verified = "<span class = 'badge bg-success'><i class='bi bi-check-lg'></span>";
            $del_btn = "";
        }

        $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>Active</button>";

        if ($row['status'] == 0) {
            $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-danger btn-sm shadow-none'>Inactive</button>";

        }
        $date = date("d-m-Y", strtotime($row['date-time']));

        $data .= "
        <tr>
            <td>$i</td>
            <td><img src='$path$row[picture]' width='50px'> <br> $row[name]</td>
            <td>$row[email]</td>
            <td>$row[phone]</td>
            <td>$row[address]</td>
            <td>$row[dob]</td>

            <td>$verified</td>
            <td>$status</td>
            <td>$date</td>
            <td>$del_btn</td>
        </tr>
        ";
        $i++;
    }
    echo $data;
}


?>
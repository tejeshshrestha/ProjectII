<?php

require('../pages/db_config.php');
require('../pages/extra.php');
adminLogin();

error_reporting(E_ALL);
ini_set('display_errors', 1);


//     var_dump($form_data);    //returns datatype as well
//     print_r($form_data);     //same but no datatype


if (isset($_POST['get_bookings'])) {

    $frm_data = filteration($_POST);

    $limit = 15;            //LIMIT used for PAGINATION ONLY!

    $page = $frm_data['page'];
    $start = ($page - 1) * $limit;

    $q = "SELECT bo.*,bd.* from `booking_order` bo inner join `booking_details` bd on bo.booking_id = bd.booking_id
     where bo.booking_status = 'booked' and (bo.order_id like ? or bd.phone like ? or bd.user_name like ?) order by bo.booking_id DESC";

    $res = select($q, ["%$frm_data[search]%", "%$frm_data[search]%", "%$frm_data[search]%"], 'sss');
    //$res = mysqli_query($con, $q);

    $limit_query = $q . " LIMIT $start,$limit";
    $limit_res = select($limit_query, ["%$frm_data[search]%", "%$frm_data[search]%", "%$frm_data[search]%"], 'sss');

    $i = 1;
    $table_data = "";

    $total_rows = mysqli_num_rows($res);

    if ($total_rows == 0) {
        $output = json_encode([$table_data => '<b>NO DATA FOUND!<b>']); //, "pagination" => ''

        echo $output;

        exit;

    }

    while ($data = mysqli_fetch_assoc($limit_res)) {
        $date = date("d-m-Y", strtotime($data['date-time']));
        $checkin = date("d-m-Y", strtotime($data['check_in']));
        $checkout = date("d-m-Y", strtotime($data['check_out']));

        $table_data .= "
        <tr>
            <td>$i</td>
            <td>
            <span class = 'badge bg-primary'> Order ID: $data[order_id]</span>
            <br><b>Name : </b>$data[user_name]<br>
            <b>Phone : </b>$data[phone]
            </td>
            <td>
            <b>Room:</b> $data[room_name]
            <br>Price : Rs.  $data[price]<br>
            </td>
            <td>
            <b>Amount paid : </b>$data[trans_amt]<br>
            <b>Date : </b>$date
            </td>
            <td><span class='badge bg-primary'>$data[booking_status]</span></td>
            <td>
                <button type='button' onclick= 'download($data[booking_id])' class='btn btn-danger fw-bold shadow-none btn-sm'>
                <i class='bi bi-filetype-pdf'></i>
            </td>
        </tr>
        ";
        $i++;
    }
    echo $table_data;
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
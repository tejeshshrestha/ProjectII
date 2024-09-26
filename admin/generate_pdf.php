<?php
require('pages/extra.php');
require('pages/db_config.php');
require('pages/mpdf/vendor/autoload.php');

adminLogin();


if (isset($_GET['gen_pdf']) && isset($_GET['id'])) {
    $frm_data = filteration($_GET);

    $q = "SELECT bo.*,bd.*,uc.email from `booking_order` bo inner join `booking_details` bd on bo.booking_id = bd.booking_id 
    Inner join `user-cred` uc on bo.user_id = uc.id
    where bo.booking_status = 'booked' and bo.booking_id = '$frm_data[id]'";

    //$res = select($q, '', '');        //perfect for debugging error
    $res = mysqli_query($con, $q);

    $total_rows = mysqli_num_rows($res);

    if ($total_rows == 0) {
        header('location: dashboard.php');
        exit;
    }
    $data = mysqli_fetch_assoc($res);

    $date = date("d-m-Y", strtotime($data['date-time']));
    $checkin = date("d-m-Y", strtotime($data['check_in']));
    $checkout = date("d-m-Y", strtotime($data['check_out']));

    $table_data = '';
    $table_data .= "
        <h2>BOOKING RECEIPT</h2>
        <table border = '1'>
        <tr>
            <td>Order ID: $data[order_id]</td><br>
            <td>Booking Date : $date</td>
        </tr>
        <tr>
        <td colspan='2'>Status: $data[booking_status]</td>
        </tr>
        <tr>
            <td>Name: $data[user_name]</td>
            <td>Email : $data[email]</td>
        </tr>
        <tr>
            <td>Mobile : $data[phone]</td>
            <td>Address : $data[address]</td>
        </tr>
        <tr>
            <td>Room Name: $data[room_name]</td>
            <td>Cost : Rs. $data[price]</td>
        </tr>
        <tr>
            <td>Check-In: $checkin</td>
            <td>Check-Out : $checkout</td>
        </tr>
        ";

    $table_data .= "</table>";
    echo $table_data;


    $mpdf = new \Mpdf\Mpdf();

    // Write some HTML code:

    $mpdf->WriteHTML($table_data);

    // Output a PDF file directly to the browser

    //$mpdf->Output();
    //$mpdf->Output($data['order_id'] . '.pdf', 'D');

} else {
    header('location: dashboard.php');
}




?>
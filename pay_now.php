<?php
require('admin/pages/extra.php');
require('admin/pages/db_config.php');

// require('pages/paytm/config_paytm.php');
// require('pages/paytm/encdec_paytm.php');

session_start();

if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect('index.php');
}


if (isset($_POST['pay_now'])) {             //Khalti@2024

    // header("Pragma: no-cache");
    // header("Cache-Control: no-cache");
    // header("Expires: 0");

    // $checkSum = "";                  //pages\paytm\config
    // $paramList = array();
    // $paramList["MID"] = PAYTM_MERCHANT_MID;
    // $paramList["ORDER_ID"] = $ORDER_ID;
    // $paramList["CUST_ID"] = $CUST_ID;
    // $paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
    // $paramList["CHANNEL_ID"] = $CHANNEL_ID;
    // $paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
    // $paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;

    // $paramList['CALLBACK_URL'] = CALLBACK_URL;

    // //Here checksum string will return by getChecksumFromArray() function.
    // $checkSum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);





    $ORDER_ID = 'ORD_' . $_SESSION['uId'] . random_int(11111, 9999999);
    // $CUST_ID = $_SESSION["uId"];
    // $INDUSTRY_TYPE_ID = INDUSTRY_TYPE_ID;
    // $CHANNEL_ID = CHANNEL_ID;
    $TXNAMT = $_SESSION['room']['payment'];


    //insert

    $frm_data = filteration($_POST);

    $q1 = "INSERT INTO `booking_order`(`user_id`, `room_id`, `check_in`, `check_out`,`order_id`,`trans_amt`) VALUES (?,?,?,?,?,?)";

    insert($q1, [
        $_SESSION["uId"],
        $_SESSION['room']['id'],
        $frm_data['checkin'],
        $frm_data['checkout'],
        $ORDER_ID,
        $TXNAMT
    ], 'issssi');

    $booking_id = mysqli_insert_id($con);

    $q2 = "INSERT INTO `booking_details`(`booking_id`, `room_name`, `price`, `total_pay`,`user_name`, `phone`, `address`) VALUES (?,?,?,?,?,?,?)";

    //var_dump($_SESSION);

    insert($q2, [
        $booking_id,
        $_SESSION['room']['name'],
        $_SESSION['room']['price'],
        $TXNAMT,
        $frm_data['name'],
        $frm_data['phone'],
        $frm_data['address']
    ], 'issssss');

}

?>
<html>

<head>
    <title>Processing</title>
</head>

<body>
    <h1>Booking in progress.....</h1>

    <?php
    header("refresh:2; url=success.php?order=$ORDER_ID");        //waits 2 secs before redirecting
    //redirect('facilities.php'); ?>
</body>

</html>
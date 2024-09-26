<?php
require('../admin/pages/db_config.php');
require('../admin/pages/extra.php');

session_start();

if (isset($_GET['fetch_rooms'])) {

    //print_r($_GET['chk_avail']);

    $chk_avail = json_decode($_GET['chk_avail'], true);

    if ($chk_avail['checkin'] != '' && $chk_avail['checkout'] != '') {
        $today_date = new DateTime(date("Y-m-d"));
        $checkin_date = new DateTime($chk_avail['checkin']);
        $checkout_date = new DateTime($chk_avail['checkout']);

        if ($checkin_date == $checkout_date) {
            echo "<h3 class = 'text-center text-danger'>You cannot Check IN and OUT on the same day!</h3>";
            exit;
        } else if ($checkout_date < $checkin_date) {
            echo "<h3 class = 'text-center text-danger'>CheckOut date cannot be before CheckIn date!</h3>";
            exit;
        } else if ($checkin_date < $today_date) {
            echo "<h3 class = 'text-center text-danger'>Invalid CheckIn date chosen!</h3>";
            exit;
        }
    }

    //count no. of rooms
    $count_rooms = 0;

    $output = "";

    // fetch settings table for shutdown value
    $settings_q = "SELECT * FROM `settings` WHERE `s_no`=1";
    $settings_r = mysqli_fetch_assoc(mysqli_query($con, $settings_q));



    $room_res = select('SELECT * FROM `rooms` WHERE `status`=? and `remove`=? ORDER by `id` DESC LIMIT 6', [1, 0], 'ii');
    while ($room_data = mysqli_fetch_assoc($room_res)) {

        if ($chk_avail['checkin'] != '' && $chk_avail['checkout'] != '') {
            $tb_query = "SELECT COUNT(*) as `total_bookings` from `booking_order` where
            booking_status = ? and room_id = ?
            and check_out > ? and check_in < ?";

            $values = ['booked', $room_data['id'], $chk_avail['checkin'], $chk_avail['checkout']];

            $tb_fetch = mysqli_fetch_assoc(select($tb_query, $values, 'siss'));

            if (($room_data['quantity'] - $tb_fetch['total_bookings']) <= 0) {
                continue;
            }
        }


        //get choices

        $choices_q = mysqli_query($con, "SELECT c.name FROM `choices` c INNER join `room_choices` rc 
     on c.id = rc.choice_id where rc.room_id = '$room_data[id]'");

        $choices_data = "";
        while ($cho_row = mysqli_fetch_assoc($choices_q)) {
            $choices_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>
            $cho_row[name]
            </span>";
        }

        $facilities_q = mysqli_query($con, "SELECT f.name FROM `facilities` f INNER join `room_facilities` rf
     on f.id = rf.facilities_id where rf.room_id = '$room_data[id]'");

        $facilities_data = "";

        while ($cho_row = mysqli_fetch_assoc($facilities_q)) {
            $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>
            $cho_row[name]
            </span>";
        }


        $book_btn = "";

        if (!$settings_r['shutdown']) {
            $login = 0;
            if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
                $login = 1;
                $book_btn = "<button onclick = 'checkLogin($login,$room_data[id])' class='btn btn-sm text-white shadow-none custom-bg btn-outline-dark'>Book Now</button>";
            }
        }

        $output .= "
            <div class='col-lg-4 col-md-6 my-3'>
            <div class='card border-0 shadow' style='max-width: 360px; margin: auto'>
            <img src='images/images/rooms/1.jpg' class='card-imp-top' />
                <div class='card-body'>
                    <h5>$room_data[name]</h5>
                    <h6 class='mb-4'>Rs. $room_data[price]</h6>
                    <div class='choices mb-4'>
                    <h6 class='mb-1'>Choices</h6>
                    $choices_data
                </div>
            <div class='facilities mb-4'>
            <h6 class='mb-1'>Facilities</h6>
            $facilities_data
            </div>
            <div class='guests mb-4'>
            <h6 class='mb-1'>Guests</h6>
            <span class='badge rounded-pill bg-light text-dark text-wrap'>
            $room_data[adult] Adults
            </span>
            <span class='badge rounded-pill bg-light text-dark text-wrap'>
            $room_data[children] Children
            </span>
            </div>
            <div class='rating mb-4'>
            <h6 class='mb-1'>Rating</h6>
            <span class='badge rounded-pill bg-light'>
            <i class='bi bi-star-fill text-warning'></i>
            <i class='bi bi-star-fill text-warning'></i>
            <i class='bi bi-star-fill text-warning'></i>
            <i class='bi bi-star-fill text-warning'></i>
            </span>
            </div>
            <div class='d-flex justify-content-evenly mb-2'>
            $book_btn              
            </div>
            </div>
            </div>
            </div>
            ";

        $count_rooms++;
    }

    if ($count_rooms > 0) {
        echo $output;
    } else {
        echo "<h3 class = 'text-center text-danger'>No rooms to show!</h3>";
    }
}








?>
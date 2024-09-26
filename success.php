<!DOCTYPE html>
<html lang="en">

<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Pahuna</title>

<?php require("pages/links.php") ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<body class="bg-light">

    <?php require("pages/header.php") ?>

    <div class="container">
        <h2 class="my-5 mb-2 text-center fw-bold h-font">Booking Status</h2>

        <?php
        //unset($_SESSION['room']);
        
        if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
            redirect('hotels.php');
        }

        $slct_q = "SELECT `booking_id`, `user_id` from `booking_order` WHERE `order_id`='$_GET[order]'";
        $slct_res = mysqli_query($con, $slct_q);
        if (mysqli_num_rows($slct_res) == 0) {
            redirect('index.php');
        }
        $slct_fetch = mysqli_fetch_assoc($slct_res);


        $upd_q = "UPDATE `booking_order` set `booking_status`='booked' where `booking_id`='$slct_fetch[booking_id]'";
        mysqli_query($con, $upd_q);
        ?>

        <div class="row">
            <div class="col-12 my-5 mb-3 px-4">
            </div>
            <div class="col-12 px-4 text-center">
                <?php
                echo <<<data
                        <div class="col-12 px-4">
                        <p class ="fw-bold alert alert-success">
                        <i class = "bi bi-check-circle-fill"></i>
                        Booking done successfully!
                        <br>
                        <br>
                     
                        <p/>
                        </div>
                        data
                    ?>
            </div>
        </div>
    </div>

    <?php require("pages/footer.php") ?>

</body>

</html>
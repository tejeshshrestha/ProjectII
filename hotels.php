<!DOCTYPE html>
<html lang="en">

<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<?php require("pages/links.php") ?>
<title>
    <?php echo $settings_r['site_title'] ?> - Rooms
</title>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<body class="bg-light">

    <?php require("pages/header.php");

    $checkin_default = "";
    $checkout_default = "";


    if (isset($_GET['check_availability'])) {
        $frm_data = filteration($_GET);

        $checkin_default = $frm_data['checkin'];
        $checkout_default = $frm_data['checkout'];

    }
    ?>


    <h2 class="my-5 mb-2 text-center fw-bold h-font">
        Our Rooms
    </h2>
    <div class="h-line bg-dark"></div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 ps-4">
                <nav class="navbar navbar-expand-lg bg-white rounded">
                    <div class="container-fluid flex-lg-column align-items-stretch">
                        <h4 class="mt-2 text-center">FILTERS</h4>
                        <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filterDropdown" aria-controls="navbarNav" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="filterDropdown">
                            <div class="border bg-light p-3 rounded mb-3">

                                <h5 class="d-flex align-items-center justify-content-between mb-3"
                                    style="font-size : 18px">
                                    <span>CHECK AVAILABILITY</span>
                                    <button id="chk_avail_btn" class="btn shadow-none btn-sm text-secondary d-none"
                                        onclick="chk_avail_clear()">Reset</button>
                                </h5>
                                <label class="form-label" style="font-weight: 500">Check-In</label>
                                <input type="date" class="form-control shadow-none mb-3" id="checkin"
                                    value="<?php echo $checkin_default ?>" onchange="chk_avail_filter()" />
                                <label class="form-label" style="font-weight: 500">Check-Out</label>
                                <input type="date" class="form-control shadow-none" id="checkout"
                                    value="<?php echo $checkout_default ?>" onchange="chk_avail_filter()" />
                            </div>

                        </div>
                </nav>
            </div>

            <div class="col-lg-9 col-md-12 px-4">
                <div class="row" id="rooms-data">
                </div>
            </div>
        </div>
    </div>
    <script>

        let rooms_data = document.getElementById('rooms-data');

        let checkin = document.getElementById('checkin');
        let checkout = document.getElementById('checkout');

        let chk_avail_btn = document.getElementById('chk_avail_btn');

        function fetch_rooms() {

            let chk_avail = JSON.stringify({
                checkin: checkin.value,
                checkout: checkout.value
            });

            let xhr = new XMLHttpRequest();
            xhr.open("GET", "ajax/rooms.php?fetch_rooms&chk_avail=" + chk_avail, true);

            xhr.onprogress = function () {
                rooms_data.innerHTML = `<div class="spinner-border text-info mb-3 d-block" id="info_loader" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>`;
            }
            xhr.onload = function () {
                rooms_data.innerHTML = this.responseText;
            }
            xhr.send();
        }

        function chk_avail_filter() {
            if (checkin.value != '' && checkout.value != '') {
                fetch_rooms();
                chk_avail_btn.classList.remove('d-none');
            }

        }

        function chk_avail_clear() {
            checkin.value = '';
            checkout.value = '';
            fetch_rooms();
            chk_avail_btn.classList.add('d-none');
        }


        window.onload = function () {
            fetch_rooms();
        }

    </script>

    <?php require("pages/footer.php") ?>

</body>

</html>
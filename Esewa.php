<!DOCTYPE html>
<html lang="en">

<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Pahuna</title>

<?php require("pages/links.php") ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<body class="bg-light">


    <h2 class="my-5 mb-2 text-center fw-bold h-font">ESEWA</h2>
    <div class="h-line bg-dark"></div>

    <div class="container">

        <body>
            <?php
            $s = hash_hmac('sha256', 'total_amount,transaction_uuid,product_code', '8gBm/:&EnhH.1/q', true);

            $room_res = select('SELECT * FROM `rooms` WHERE `id`=?', [11], 'i');
            $room_data = mysqli_fetch_assoc($room_res);
            ?>


            <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST"
                enctype="application/x-www-form-urlencoded">
                <input type="text" id="amount" name="amount" value="100" required>
                <input type="text" id="tax_amount" name="tax_amount" value="10" required>

                <input type="text" id="total_amount" name="total_amount" value="110" required>

                <input type="text" id="transaction_uuid" name="transaction_uuid" value="ab14a8f2b02c3" required>

                <input type="text" id="product_code" name="product_code" value="EPAYTEST" required>

                <input type="text" id="product_service_charge" name="product_service_charge" value="0" required>
                <input type="text" id="product_delivery_charge" name="product_delivery_charge" value="0" required>
                <input type="text" id="success_url" name="success_url" value="https://esewa.com.np" required>
                <input type="text" id="failure_url" name="failure_url" value="https://google.com" required>
                <input type="text" id="signed_field_names" name="signed_field_names"
                    value="total_amount,transaction_uuid,product_code" required>
                <input type="text" id="signature" name="signature"
                    value="<?php echo hash_hmac('sha256', 'total_amount=110,transaction_uuid=ab14a8f2b02c3,product_code=EPAYTEST', '8gBm/:&EnhH.1/q', true) ?>"
                    required>
                <input value=" Submit" type="submit">
            </form>
        </body>

    </div>

    <?php require("pages/footer.php") ?>

</body>

</html>
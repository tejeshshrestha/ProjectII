<?php
require ('pages/extra.php');
adminLogin();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Carousel</title>
    <?php require ('pages/links.php') ?>
</head>

<body class="bg-light">

    <?php require ("pages/header.php") ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden mt-auto">
                <h3 class="mb-4">CAROUSEL</h3>
            </div>
        </div>
    </div>

    <?php require ('pages/scripts.php') ?>
    <script src="scripts/settings.js"></script>

</body>

</html>
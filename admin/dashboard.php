<?php
require('pages/extra.php');
require('pages/db_config.php');

adminLogin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - DASHBOARD</title>
    <?php require('pages/links.php') ?>
</head>

<body class="bg-light">

    <?php require("pages/header.php");

    $is_shutdown = mysqli_fetch_assoc(mysqli_query($con, "SELECT `shutdown` from `settings`"));

    $current_users = mysqli_fetch_assoc(mysqli_query($con, "SELECT 
        count(id) as `total`,
        COUNT(CASE when `status` = 1 then 1 end) as `active`,
        count(case when `status` = 0 then 1 end) as `inactive`,
        count(case when `is_verified` = 0 then 1 end) as `unverified`from `user-cred`"));

    $current_rooms = mysqli_fetch_assoc(mysqli_query($con, "SELECT
        count(id) as `total`,
        count(case when `status` = 1 then 1 end) as `active`,
        count(case when `status` = 0 then 1 end) as `inactive`from `rooms`"));
    ?>

    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">

                <h3>DASHBOARD</h3><br><br>
                <h5>USERS</h5><br><br>
                <div class="row mb-3">

                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-success p-3">
                            <h6>Total Users</h6>
                            <h1 class="mt-2 mb-0"><?php echo $current_users['total'] ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6>Active Users</h6>
                            <h1 class="mt-2 mb-0"><?php echo $current_users['active'] ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-warning p-3">
                            <h6>Inactive Users</h6>
                            <h1 class="mt-2 mb-0"><?php echo $current_users['inactive'] ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-danger p-3">
                            <h6>Unverified Users</h6>
                            <h1 class="mt-2 mb-0"><?php echo $current_users['unverified'] ?></h1>
                        </div>
                        <br><br><br>
                    </div>


                    <h5>ROOMS</h5><br><br>
                    <div class="row mb-3">

                        <div class="col-md-3 mb-4">
                            <div class="card text-center text-success p-3">
                                <h6>Total Rooms</h6>
                                <h1 class="mt-2 mb-0"><?php echo $current_rooms['total'] ?></h1>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card text-center text-primary p-3">
                                <h6>Active Rooms</h6>
                                <h1 class="mt-2 mb-0"><?php echo $current_rooms['active'] ?></h1>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card text-center text-danger p-3">
                                <h6>Inactive Rooms</h6>
                                <h1 class="mt-2 mb-0"><?php echo $current_rooms['inactive'] ?></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php require('pages/scripts.php') ?>
</body>

</html>
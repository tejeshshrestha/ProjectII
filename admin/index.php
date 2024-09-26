<?php require("pages/db_config.php");
require("pages/extra.php");
session_start();
if
((isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
    //redirect('dashboard.php');
    redirect('settings.php');
} ?>

<!DOCTYPE html>
<html lang="en">


<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login Panel</title>

<?php require('./pages/links.php'); ?>
<style>
    div.login-form {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 400px;
    }
</style>
</head>

<body class="bg-light">
    <div class="login-form text-center rounded bg-white shadow overflow-hidden">
        <form method="post">
            <h4 class="bg-dark text-white py-3">Admin Login Panel</h4>
            <div class="p-4">
                <div class="mb-3">
                    <input name="admin_name" required type="text" class="form-control shadow-none text-center"
                        placeholder="Admin Name">
                </div>
                <div class="mb-4">
                    <input name="admin_pass" required type="password" class="form-control shadow-none text-center"
                        placeholder="Password">
                </div>
                <button name="login" type="submit" class="btn-secondary text-white custom-bg shadow">LOGIN</button>
            </div>
        </form>
    </div>

    <?php
    if (isset($_POST['login'])) {
        $form_data = filteration($_POST);

        $query = "SELECT * FROM `admin-cred` WHERE `admin_name`=? AND `admin_pass`=?";      //Capitalization MUST
        $vals = [
            $form_data['admin_name'],
            $form_data['admin_pass'],
        ];
        $datatype = "ss";
        $result = select($query, $vals, $datatype);  //or send "ss" directly
        if ($result->num_rows == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['adminLogin'] = true;
            $_SESSION['adminId'] = $row['s_no'];
            redirect('dashboard.php');
        } else {
            alert('error', 'Login Failed. Invalid Credentials!');
        }

        // print_r($_POST);
    }
    ?>

    <?php require('pages/scripts.php') ?>
</body>

</html>
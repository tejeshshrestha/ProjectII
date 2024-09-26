<?php
require('../admin/pages/db_config.php');
require('../admin/pages/extra.php');

require('../pages/sendgrid-php/sendgrid-php.php');

function send_mail($uemail, $token)
{
    $email = new \SendGrid\Mail\Mail();

    $email->setFrom(SENDGRID_EMAIL, SENDGRID_NAME);
    $email->setSubject("Account Verification Link");

    $email->addTo($uemail);

    $email->addContent(
        "text/html",
        "Click link to confirm your email:
        <br>
        <a href='" . SITE_URL . "email_confirm.php?email_confirmation&email=$uemail&token=$token" . "'>
        CLICK ME
        </a>
    "
    );

    $sendgrid = new \SendGrid(SENDGRID_API_KEY);
    try {
        $sendgrid->send($email);
        return 1;
    } catch (Exception $e) {
        return 0;
    }
}

if (isset($_POST['register'])) {
    $data = filteration($_POST);

    //match cpass with passw

    if ($data['pass'] != $data['cpass']) {
        echo 'pass_mismatch';
        exit;
    }
    //check user exists or not

    $u_exist = select(
        "SELECT * FROM `user-cred` WHERE `email`=? OR `phone`=? LIMIT 1",          //Limit to select 1st matched user!
        [$data['email'], $data['phone']],
        "ss"
    );

    if (mysqli_num_rows($u_exist) != 0) {
        $u_exist_fetch = mysqli_fetch_assoc($u_exist);
        echo ($u_exist_fetch['email'] == $data['email']) ? 'email_already' : 'phone_already';
        exit;
    }

    //upload user image to server

    $img = uploadUserImage($_FILES['picture']);

    if ($img == 'inv_img') {
        echo 'inv_img';
        exit;
    } else if ($img == 'upload_failed') {
        echo 'upload_failed';
        exit;
    }

    //send confirmation link to user's email

    $token = bin2hex(random_bytes(16));
    if (!send_mail($data['email'], $token)) {
        echo 'mail_failed';
        exit;
    }

    $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);

    $query = "INSERT INTO `user-cred`(`name`,`email`,`address`,`phone`,`dob`,`picture`,`password`,`token`) VALUES(?,?,?,?,?,?,?,?)";

    $values = [
        $data['name'],
        $data['email'],
        $data['address'],
        $data['phone'],
        $data['DOB'],
        $img,
        $enc_pass,
        $token
    ];

    if (insert($query, $values, 'ssssssss')) {
        echo 1;
    } else {
        echo 'insert_failed';
    }

}


if (isset($_POST['login'])) {
    $data = filteration($_POST);

    $u_exist = select(
        "SELECT * FROM `user-cred` WHERE `email`=? OR `phone`=? LIMIT 1",
        [$data['email_mob'], $data['email_mob']],
        "ss"
    );

    if (mysqli_num_rows($u_exist) == 0) {
        echo 'inv_email_mob';
    } else {
        $u_fetch = mysqli_fetch_assoc($u_exist);
        if ($u_fetch['is_verified'] == 0) {
            echo 'not_verified';
        } else if ($u_fetch['status'] == 0) {
            echo 'inactive_cred';
        } else {
            if (!password_verify($data['pass'], $u_fetch['password'])) {            //'pass' is name whereas 'password' is from DB table
                echo 'invalid_pass';
            } else {
                session_start();
                $_SESSION['login'] = true;
                $_SESSION['uId'] = $u_fetch['id'];
                $_SESSION['uName'] = $u_fetch['name'];
                $_SESSION['uPic'] = $u_fetch['picture'];
                $_SESSION['uPhone'] = $u_fetch['phone'];
                echo 1;
            }
        }
    }
}


?>
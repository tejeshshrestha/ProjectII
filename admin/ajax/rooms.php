<?php

require('../pages/db_config.php');
require('../pages/extra.php');
adminLogin();

// error_reporting(E_ALL);
// ini_set('display_errors', 1);



//     print_r($form_data);



if (isset($_POST['add_room'])) {
    $choices = filteration(json_decode($_POST['choices']));
    $facilities = filteration(json_decode($_POST['facilities']));


    $form_data = filteration($_POST);

    // Ensure the necessary data is available
    if (
        isset($form_data['name']) && isset($form_data['area']) && isset($form_data['price']) &&
        isset($form_data['quantity']) && isset($form_data['adult']) && isset($form_data['children']) &&
        isset($form_data['desc'])
    ) {
        $name = $form_data['name'];
        $area = $form_data['area'];
        $price = $form_data['price'];
        $quantity = $form_data['quantity'];
        $adult = $form_data['adult'];
        $children = $form_data['children'];
        $desc = $form_data['desc'];
        $choices = isset($form_data['choices']) ? json_decode($form_data['choices'], true) : [];
        $facilities = isset($form_data['facilities']) ? json_decode($form_data['facilities'], true) : [];

        // Database insert logic
        $con = $GLOBALS['con'];
        $stmt = $con->prepare("INSERT INTO rooms (name, area, price, quantity, adult, children, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Database insert failed: " . $con->error);
        }

        $stmt->bind_param("siiiiis", $name, $area, $price, $quantity, $adult, $children, $desc);
        if ($stmt->execute()) {
            $room_id = $stmt->insert_id;
            $stmt->close();

            foreach ($choices as $choice) {
                $stmt = $con->prepare("INSERT INTO room_choices (room_id, choice_id) VALUES (?, ?)");
                if ($stmt) {
                    $stmt->bind_param("ii", $room_id, $choice);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            foreach ($facilities as $facility) {
                $stmt = $con->prepare("INSERT INTO room_facilities (room_id, facilities_id) VALUES (?, ?)");
                if ($stmt) {
                    $stmt->bind_param("ii", $room_id, $facility);
                    $stmt->execute();
                    $stmt->close();
                }
            }


            echo 1; // Success
        } else {
            $stmt->close();
            echo 0; // Failure
        }
    } else {
        echo 0; // Missing required data
    }




}

if (isset($_POST['get_all_rooms'])) {
    $res = select('SELECT * from `rooms` where `remove`=?', [0], 'i');
    $i = 1;

    $data = "";

    while ($row = mysqli_fetch_assoc($res)) {

        if ($row['status'] == 1) {
            $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>Active</button>";
        } else {
            $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-danger btn-sm shadow-none'>Inactive</button>";
        }

        $data .= "
        <tr class = 'align-middle'>
        <td>$i</td>
        <td>$row[name]</td>
        <td>$row[area] sq. ft.</td>
        <td>
        <span class='badge rounded-pill bg-light text-dark'>
        Adult : $row[adult]
        </span><br>
        <span class='badge rounded-pill bg-light text-dark'>
        Children : $row[children]
        </span><br>
        </td>
        <td>$row[price]</td>
        <td>$row[quantity]</td>
        <td>$status</td>
        <td>
        <button type='button' onclick= 'edit_details($row[id])' class='btn btn-dark shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-room'>
        <i class='bi bi-pencil-square'></i>
        </button>
        </td>
        </tr>
        ";
        $i++;
        // <button type='button' onclick= 'remove_room($row[id])' class='btn btn-danger shadow-none btn-sm'>
        // <i class='bi bi-trash'></i>
        // </button>
    }
    echo $data;
}

if (isset($_POST['get_room'])) {
    $frm_data = filteration($_POST);

    $res1 = select("SELECT * FROM `rooms` WHERE `id`=?", [$frm_data['get_room']], 'i');
    $res2 = select("SELECT * FROM `room_facilities` WHERE `room_id`=?", [$frm_data['get_room']], 'i');
    $res3 = select("SELECT * FROM `room_choices` WHERE `room_id`=?", [$frm_data['get_room']], 'i');

    $roomdata = mysqli_fetch_assoc($res1);
    $facilities = [];
    $choices = [];

    if (mysqli_num_rows($res2) > 0) {
        while ($row = mysqli_fetch_assoc($res2)) {
            array_push($facilities, $row['facilities_id']);
        }
    }

    if (mysqli_num_rows($res3) > 0) {
        while ($row = mysqli_fetch_assoc($res3)) {
            array_push($choices, $row['choice_id']);
        }
    }
    $data = ["roomdata" => $roomdata, "facilities" => $facilities, "choices" => $choices];

    $data = json_encode($data);

    echo $data;
}

if (isset($_POST['edit_room'])) {

    error_log("Received data: " . print_r($_POST, true));

    $choices = filteration(json_decode($_POST['choices']));
    $facilities = filteration(json_decode($_POST['facilities']));

    $frm_data = filteration($_POST);
    $flag = 0;

    $q1 = "UPDATE `rooms` SET `name`=?,`area`=?,`price`=?,`quantity`=?,
    `adult`=?,`children`=?,`description`=? WHERE `id` = ?";

    $values = [$frm_data['name'], $frm_data['area'], $frm_data['price'], $frm_data['quantity'], $frm_data['adult'], $frm_data['children'], $frm_data['desc'], $frm_data['room_id']];

    $affected_rows = upd($q1, $values, 'siiiiisi');
    error_log("Affected rows: $affected_rows");
    error_log("SQL Query: $q1");
    error_log("Values: " . implode(", ", $values));


    error_log("Form data received: " . print_r($_POST, true));

    error_log("Updating room with ID: " . $frm_data['room_id']);


    if ($affected_rows > 0) {
        error_log("Room updated successfully. Affected rows: $affected_rows");
        $flag = 1;
    } else {
        error_log("No rows updated for room.");
    }

    $del_choices = del("DELETE from `room_choices` WHERE `room_id`=?", [$frm_data['room_id']], 'i');
    if ($del_choices > 0) {
        error_log("Room choices deleted successfully. Affected rows: $del_choices");
    } else {
        error_log("No rows deleted for room choices.");
    }

    // Delete room facilities
    $del_facilities = del("DELETE from `room_facilities` WHERE `room_id`=?", [$frm_data['room_id']], 'i');
    if ($del_facilities > 0) {
        error_log("Room facilities deleted successfully. Affected rows: $del_facilities");
    } else {
        error_log("No rows deleted for room facilities.");
    }


    $q2 = "INSERT into `room_facilities`(`room_id`,`facilities_id`) Values (?,?)";
    if ($stmt = mysqli_prepare($con, $q2)) {
        foreach ($facilities as $f) {
            mysqli_stmt_bind_param($stmt, 'ii', $frm_data['room_id'], $f);
            mysqli_stmt_execute($stmt);
        }
        $flag = 1;
        mysqli_stmt_close($stmt);
    } else {
        $flag = 0;
        die('Query cannot be prep! - Insert..');
    }

    $q3 = "INSERT into `room_choices`(`room_id`,`choice_id`) Values (?,?)";
    if ($stmt = mysqli_prepare($con, $q3)) {
        foreach ($choices as $c) {
            mysqli_stmt_bind_param($stmt, 'ii', $frm_data['room_id'], $c);
            mysqli_stmt_execute($stmt);
        }
        $flag = 1;
        mysqli_stmt_close($stmt);
    } else {
        $flag = 0;
        die('Query cannot be prep! - Insert..->' . mysqli_error($con));
    }
    if ($flag) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['toggle_status'])) {
    $frm_data = filteration($_POST);

    $q = "UPDATE `rooms` SET `status`=? WHERE `id`=?";
    $v = [$frm_data['value'], $frm_data['toggle_status']];

    if (upd($q, $v, 'ii')) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['remove_room'])) {
    $frm_data = filteration($_POST);

    //var_dump($frm_data);

    //$res1 = del("DELETE FROM `room_facilities` WHERE `room_id`=?", [$frm_data['room_id']], 'i');
    //$res2 = del("DELETE FROM `room_choices` WHERE `room_id`=?", [$frm_data['room_id']], 'i');
    $res2 = upd("UPDATE `rooms` SET  `remove`=? where `id`=?", [1, $frm_data['room_id']], 'i');

    if ($res3) {
        echo 1;
    } else {
        echo 0;
    }
}

?>
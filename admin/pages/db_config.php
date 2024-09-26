<?php

$hname = 'localhost';
$uname = 'root';
$pass = '';
$db = 'project2';

$con = mysqli_connect($hname, $uname, $pass, $db);      //connection

if (!$con) {
    die("Cannot connect to DB!  " . mysqli_connect_error());
}

function filteration($data)
{
    foreach ($data as $key => $value) {
        $data[$key] = trim($value);
        $data[$key] = stripcslashes($value);
        $data[$key] = htmlspecialchars($value);
        $data[$key] = strip_tags($value);
    }
    return $data;
}

function selectAll($table)
{
    $con = $GLOBALS['con'];
    $sql = "SELECT * FROM `$table`";
    $res = mysqli_query($con, $sql);
    if (!$res) {
        die("Query failed: " . mysqli_error($con));
    }
    return $res;
}

function select($sql, $values, $datatypes)
{
    $con = $GLOBALS['con'];
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);

        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            mysqli_stmt_close($stmt);
            die("Query cannot be executed! - Select");
        }
    } else {
        die("Query cannot be prepared! - Select" . mysqli_error($con));
    }
}

function upd($sql, $values, $datatypes)
{
    $con = $GLOBALS['con'];
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);

        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            error_log("SQL Error - Update: " . mysqli_error($con));  // Log the error
            mysqli_stmt_close($stmt);
            die("Query cannot be executed! - Update");
        }
    } else {
        error_log("SQL Error - Prepare: " . mysqli_error($con));
        die("Query cannot be prepared! - Update" . mysqli_error($con));
    }

}


function insert($sql, $values, $datatypes)
{
    $con = $GLOBALS['con'];
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
        if (mysqli_stmt_execute($stmt)) {
            $insert_id = mysqli_stmt_insert_id($stmt);
            mysqli_stmt_close($stmt);
            return $insert_id;
        } else {
            error_log("SQL Error - Insert: " . mysqli_error($con));  // Log the error
            mysqli_stmt_close($stmt);
            die("Query cannot be executed! - Insert");
        }
    } else {
        die("Query cannot be prepared! - Insert " . mysqli_error($con));
    }
}

function del($sql, $values, $datatypes)
{
    $con = $GLOBALS['con']; // Assuming $con is your database connection

    if ($stmt = mysqli_prepare($con, $sql)) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Get the number of affected rows
            $res = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            error_log("SQL Error - Delete: " . mysqli_error($con));  // Log the error
            mysqli_stmt_close($stmt);
            die("Query cannot be executed! - Delete");
        }
    } else {
        error_log("SQL Error - Prep: " . mysqli_error($con));
        die("Query cannot be prepared! - Delete" . mysqli_error($con));
    }
}
?>
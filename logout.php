<?php
require ('admin/pages/extra.php');

session_start();
session_destroy();
redirect('index.php');

?>
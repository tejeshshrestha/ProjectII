<?php
require ('pages/extra.php');

session_start();
session_destroy();
redirect('index.php');

?>
<?php
require 'app/bootstrap.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$capture = new \Codecourse\Capture\Capture;
$view = new \Codecourse\Views\View;
$capture->load('invoice.php', [], 'pdf');

$capture->respond('senthan_cv.pdf', 'pdf');


<?php
session_start();
// require config và connect database
require '../config.php';
require '../connectDb.php';

// import lib
require '../vendor/autoload.php';

// require model
require '../bootstrap.php';


// Router
$c = $_GET['c'] ?? 'home';
$a = $_GET['a'] ?? 'index';

// ucfirst là upper ký tự đầu tiên 
$str = ucfirst($c) . 'Controller'; //StudentController
require "controller/$str.php";
$controller = new $str();
$controller->$a();

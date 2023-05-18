<?php

$host = 'localhost';
$username = 'root';
$password = '';
$db = 'floorball';

$connect = new mysqli($host, $username, $password, $db);

if ($connect->errno) {
    echo $connect->error;
    die();
}


// $host = 'localhost';
// $username = 'u127283_serg.bolshakov';
// $password = 'bazadannykh%1994';
// $db = 'u127283_floorball_db';

// $connect = new mysqli($host, $username, $password, $db);

// if ($connect->errno) {
//     echo $connect->error;
//     die();
// }
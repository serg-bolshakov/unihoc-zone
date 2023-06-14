<?php
  error_reporting (E_ALL);
  ini_set('display_errors', 'on');

  // $page = $_GET['page'] ?? 'index';
  // $path = "products/$page.php";

  // if (file_exists($path)) {
  //   include $path;
  // } else {
  //   include 'file not found';
  // }

  require_once $_SERVER['DOCUMENT_ROOT'].'/db.php';
  
  isset($_GET['page'])? $page = $_GET['page'] : $page = 'index';
  
  $path = "products/$page.php";
  if (file_exists($path)) {
    include $path;
  } else {
    echo 'file not found';
  }
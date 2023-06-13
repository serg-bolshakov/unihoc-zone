<?php
  error_reporting (E_ALL);
  ini_set('display_errors', 'on');

  $page = $_GET['page'] ?? 'index';
  $path = "products/$page.php";

  if (file_exists($path)) {
    include $path;
  } else {
    include 'file not found';
  }
<?php

session_start();
include_once 'dbconnect.php';

//check login status
if (!isset($_SESSION['user_id'])) {
  echo "login as admin first";
  header("refresh: 3, url=admin_login.php");
  exit();
}
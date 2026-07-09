<?php

$ServerName = "localhost";
$UserName = "root";
$pwd ="";
$DbName = "shopelite";


$conn = mysqli_connect($ServerName, $UserName, $pwd, $DbName);

if (mysqli_connect_errno()) {
	echo "error connecting ";
}else{
	//echo "connected ";
}

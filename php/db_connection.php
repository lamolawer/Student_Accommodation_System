<?php
session_start();
$link = mysqli_connect("localhost", "root", "", "lebo's_accommodation");//connecting to the database

if ($link === false) {
    die("ERROR: Could not connect to the database." . mysqli_connect_error()); //failure to connect to the database show this text
}  
?>
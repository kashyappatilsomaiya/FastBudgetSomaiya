<?php
// inc/config.php

// 🔐 START SESSION (MOST IMPORTANT)
session_start();

// 🛢️ DATABASE CONNECTION
$mysqli = new mysqli("localhost", "root", "", "fastbudget");

if ($mysqli->connect_error) {
    die("Database Connection Failed: " . $mysqli->connect_error);
}

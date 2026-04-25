<?php
// Set timezone to Asia/Kolkata
date_default_timezone_set('Asia/Kolkata');

$conn = new mysqli("localhost", "root", "", "notes_platform");

if ($conn->connect_error) {
    die("DB Error");
}
?>
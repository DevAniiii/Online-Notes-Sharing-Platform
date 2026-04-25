<?php
$conn = new mysqli("localhost", "root", "", "notes_platform");

if ($conn->connect_error) {
    die("DB Error");
}
?>
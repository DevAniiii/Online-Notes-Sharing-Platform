<?php
session_start();
include "../config/db.php";

$title = $_POST['title'];
$content = $_POST['content'];
$visibility = $_POST['visibility'];
$password = $_POST['note_password'] ?? null;

$user_id = $_SESSION['user_id'] ?? null;
$hashedPassword = $password ? password_hash($password, PASSWORD_DEFAULT) : null;


if (!$user_id && $visibility === 'private') {
    die("Login required for private notes");
}

$unique_id = bin2hex(random_bytes(8));

$stmt = $conn->prepare("INSERT INTO notes (unique_id, title, content, visibility, user_id, password) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssis", $unique_id, $title, $content, $visibility, $user_id, $hashedPassword);
$stmt->execute();

header("Location: ../pages/view.php?id=" . $unique_id);
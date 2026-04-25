<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../config/db.php";

$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$visibility = $_POST['visibility'] ?? 'public';
$password = $_POST['note_password'] ?? null;
$expiry = $_POST['expiry'] ?? 'never';
$language = $_POST['language'] ?? 'plaintext';

$user_id = $_SESSION['user_id'] ?? null;
$hashedPassword = $password ? password_hash($password, PASSWORD_DEFAULT) : null;

if (!$user_id && $visibility === 'private') {
    die("Login required for private notes");
}

// Calculate expiry datetime
$expiry_datetime = null;
switch ($expiry) {
    case '30sec':
        $expiry_datetime = date('Y-m-d H:i:s', strtotime('+30 seconds'));
        break;
    case '10min':
        $expiry_datetime = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        break;
    case '1hour':
        $expiry_datetime = date('Y-m-d H:i:s', strtotime('+1 hour'));
        break;
    case '1day':
        $expiry_datetime = date('Y-m-d H:i:s', strtotime('+1 day'));
        break;
    case 'never':
    default:
        $expiry_datetime = null;
        break;
}

$unique_id = bin2hex(random_bytes(8));
$character_count = strlen($content);
$line_count = substr_count($content, "\n") + 1;

$stmt = $conn->prepare("INSERT INTO notes (unique_id, title, content, visibility, user_id, password, expiry, character_count, line_count, language) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssisiiis", $unique_id, $title, $content, $visibility, $user_id, $hashedPassword, $expiry_datetime, $character_count, $line_count, $language);
$stmt->execute();

header("Location: ../pages/view.php?id=" . $unique_id);
exit;
<?php
date_default_timezone_set('Asia/Kolkata');
session_start();
include "../config/db.php";

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

$note_id = $data['note_id'] ?? null;
$comment_text = $data['comment'] ?? null;
$email = $data['email'] ?? 'Anonymous';

// Validate input
if (!$note_id || !$comment_text) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Get note by unique_id
$stmt = $conn->prepare("SELECT id FROM notes WHERE unique_id = ?");
$stmt->bind_param("s", $note_id);
$stmt->execute();
$result = $stmt->get_result();
$note = $result->fetch_assoc();

if (!$note) {
    echo json_encode(['success' => false, 'message' => 'Note not found']);
    exit;
}

// Check if note is public
$check_stmt = $conn->prepare("SELECT visibility FROM notes WHERE id = ?");
$check_stmt->bind_param("i", $note['id']);
$check_stmt->execute();
$note_data = $check_stmt->get_result()->fetch_assoc();

if ($note_data['visibility'] !== 'public') {
    echo json_encode(['success' => false, 'message' => 'Comments not allowed on private notes']);
    exit;
}

// Insert comment
$insert_stmt = $conn->prepare("INSERT INTO comments (note_id, user_id, comment) VALUES (?, ?, ?)");
$user_id = $_SESSION['user_id'] ?? null;
$insert_stmt->bind_param("iss", $note['id'], $user_id, $comment_text);

if ($insert_stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Comment posted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error posting comment']);
}
?>

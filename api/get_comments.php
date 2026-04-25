<?php
date_default_timezone_set('Asia/Kolkata');
include "../config/db.php";

$note_id = $_GET['note_id'] ?? null;

if (!$note_id) {
    echo json_encode(['comments' => []]);
    exit;
}

// Get note
$stmt = $conn->prepare("SELECT id FROM notes WHERE unique_id = ?");
$stmt->bind_param("s", $note_id);
$stmt->execute();
$result = $stmt->get_result();
$note = $result->fetch_assoc();

if (!$note) {
    echo json_encode(['comments' => []]);
    exit;
}

// Get comments
$comments_stmt = $conn->prepare("
    SELECT 
        c.id, 
        c.comment, 
        c.created_at, 
        u.email,
        c.user_id
    FROM comments c
    LEFT JOIN users u ON c.user_id = u.id
    WHERE c.note_id = ?
    ORDER BY c.created_at DESC
");
$comments_stmt->bind_param("i", $note['id']);
$comments_stmt->execute();
$comments_result = $comments_stmt->get_result();

$comments = [];
while ($comment = $comments_result->fetch_assoc()) {
    $comments[] = [
        'id' => $comment['id'],
        'comment' => htmlspecialchars($comment['comment']),
        'email' => $comment['email'] ?? 'Anonymous',
        'created_at' => $comment['created_at'],
        'user_id' => $comment['user_id']
    ];
}

header('Content-Type: application/json');
echo json_encode(['comments' => $comments]);
?>

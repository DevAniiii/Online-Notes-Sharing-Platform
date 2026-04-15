<?php include "../config/db.php"; ?>
<?php include "../includes/header.php"; ?>

<h2 class="text-2xl mb-4">All Notes</h2>

<div class="grid md:grid-cols-3 gap-4">
<?php
$stmt = $conn->prepare("SELECT * FROM notes WHERE visibility=? ORDER BY id DESC");
$vis = "public";
$stmt->bind_param("s", $vis);
$stmt->execute();

$res = $stmt->get_result();

while($row = $res->fetch_assoc()):
?>
<div class="bg-gray-800 p-4 rounded hover:scale-105 transition">
    <h3 class="font-bold"><?= htmlspecialchars($row['title']) ?></h3>
    <p class="text-sm text-gray-400"><?= htmlspecialchars($row['visibility']) ?></p>
    <a href="view.php?id=<?= urlencode($row['unique_id']) ?>" class="text-blue-400">Open</a>
</div>
<?php endwhile; ?>
</div>

<?php include "../includes/footer.php"; ?>
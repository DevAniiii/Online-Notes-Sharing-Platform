<?php include "config/db.php"; ?>
<?php include "includes/header.php"; ?>

<div class="max-w-6xl mx-auto">

<h2 class="text-3xl mb-6 font-bold">Public Pastes</h2>

<div class="grid md:grid-cols-3 gap-6">
<?php
$stmt = $conn->prepare("SELECT * FROM notes WHERE visibility=? ORDER BY id DESC");
$vis = "public";
$stmt->bind_param("s", $vis);
$stmt->execute();

$res = $stmt->get_result(); 

while ($row = $res->fetch_assoc()):
?>
    <div class="bg-gray-800 p-5 rounded-lg border border-gray-700 hover:border-blue-500 transition transform hover:-translate-y-1">

        <h3 class="font-semibold mb-2 truncate">
            <?= htmlspecialchars($row['title']) ?>
        </h3>

        <p class="text-gray-400 text-sm mb-3">
            <?= substr(htmlspecialchars($row['content']), 0, 80) ?>...
        </p>

        <a href="pages/view.php?id=<?= urlencode($row['unique_id']) ?>"
           class="text-blue-400 text-sm hover:underline">
           Open →
        </a>

    </div>
<?php endwhile; ?>
</div>

</div>

<?php include "includes/footer.php"; ?>
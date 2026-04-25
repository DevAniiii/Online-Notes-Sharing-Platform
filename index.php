<?php 
date_default_timezone_set('Asia/Kolkata');
include "config/db.php"; 
?>
<?php include "includes/header.php"; ?>

<div class="max-w-7xl mx-auto px-6 py-12">
    <!-- Hero Section -->
    <div class="mb-16 text-center">
        <h2 class="text-5xl font-bold mb-4">
            <span class="glow-text">Share Code</span> 
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-400">Instantly</span>
        </h2>
        <p class="text-gray-400 text-lg mb-8">Secure & modern code sharing with privacy controls</p>
        <a href="pages/create.php" class="inline-block btn-modern px-8 py-3 rounded-lg bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 font-semibold">
            <i class="fas fa-arrow-right mr-2"></i> Create Your First Paste
        </a>
    </div>

    <!-- Featured Section -->
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-6">
            <i class="fas fa-fire text-orange-400 text-2xl"></i>
            <h3 class="text-3xl font-bold">Latest Public Pastes</h3>
        </div>
    </div>

    <!-- Notes Grid -->
    <div class="grid md:grid-cols-3 gap-6">
<?php
$stmt = $conn->prepare("SELECT * FROM notes WHERE visibility=? AND (expiry IS NULL OR expiry > NOW()) ORDER BY id DESC LIMIT 12");
$vis = "public";
$stmt->bind_param("s", $vis);
$stmt->execute();

$res = $stmt->get_result(); 

if ($res->num_rows === 0): ?>
    <div class="col-span-3 text-center py-12">
        <i class="fas fa-inbox text-4xl text-cyan-500/50 mb-4"></i>
        <p class="text-gray-400">No public pastes yet. Be the first!</p>
    </div>
<?php endif;

while ($row = $res->fetch_assoc()):
    $created_at = date('M d, Y', strtotime($row['created_at'] ?? 'now'));
    $char_count = strlen($row['content']);
?>
    <div class="glassmorphism p-6 rounded-xl border border-cyan-500/30 card-hover">
        <!-- Header -->
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <h3 class="font-semibold text-lg mb-1 line-clamp-2 text-cyan-300">
                    <?= htmlspecialchars($row['title'] ?: 'Untitled') ?>
                </h3>
                <p class="text-xs text-gray-500">
                    <i class="fas fa-calendar"></i> <?= $created_at ?>
                </p>
            </div>
            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                <i class="fas fa-eye"></i> Public
            </span>
        </div>

        <!-- Preview -->
        <div class="mb-4 p-3 rounded-lg bg-slate-900/50 border border-slate-700/50 max-h-24 overflow-hidden">
            <p class="text-sm text-gray-400 font-mono line-clamp-3">
                <?= htmlspecialchars(substr($row['content'], 0, 120)) ?>...
            </p>
        </div>

        <!-- Stats -->
        <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
            <span><i class="fas fa-code"></i> <?= number_format($char_count) ?> chars</span>
            <span><i class="fas fa-eye"></i> <?= $row['view_count'] ?> views</span>
            <?php if (!empty($row['password'])): ?>
                <span class="text-yellow-400"><i class="fas fa-lock"></i> Protected</span>
            <?php endif; ?>
        </div>

        <!-- Action -->
        <a href="pages/view.php?id=<?= urlencode($row['unique_id']) ?>"
           class="block w-full text-center btn-modern px-4 py-2 rounded-lg bg-gradient-to-r from-cyan-500/20 to-blue-500/20 hover:from-cyan-500/40 hover:to-blue-500/40 border border-cyan-500/50 text-cyan-300 font-semibold transition">
            <i class="fas fa-external-link-alt"></i> View Paste
        </a>
    </div>
<?php endwhile; ?>
    </div>

</div>

<?php include "includes/footer.php"; ?>
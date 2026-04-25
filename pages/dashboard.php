<?php include "../config/db.php"; ?>
<?php include "../includes/header.php"; ?>

<div class="max-w-7xl mx-auto px-6 py-12">

    <!-- Header -->
    <div class="mb-12">
        <h2 class="text-4xl font-bold mb-2">
            <i class="fas fa-layer-group text-cyan-400"></i> 
            <span class="glow-text">All Pastes</span>
        </h2>
        <p class="text-gray-400">Browse all public pastes from the community</p>
    </div>

    <!-- Filters Bar -->
    <div class="glassmorphism p-4 rounded-xl border border-cyan-500/30 mb-8 flex gap-3 flex-wrap">
        <button onclick="filterPastes('all')" class="btn-modern px-4 py-2 rounded-lg bg-cyan-500/20 hover:bg-cyan-500/40 border border-cyan-500/50 text-cyan-300 font-semibold transition text-sm active">
            <i class="fas fa-list"></i> All
        </button>
        <button onclick="filterPastes('recent')" class="btn-modern px-4 py-2 rounded-lg bg-transparent hover:bg-cyan-500/20 border border-cyan-500/30 text-gray-300 font-semibold transition text-sm">
            <i class="fas fa-clock"></i> Recent
        </button>
        <button onclick="filterPastes('popular')" class="btn-modern px-4 py-2 rounded-lg bg-transparent hover:bg-cyan-500/20 border border-cyan-500/30 text-gray-300 font-semibold transition text-sm">
            <i class="fas fa-fire"></i> Popular
        </button>
    </div>

    <!-- Notes Grid -->
    <div class="grid md:grid-cols-3 gap-6">
<?php
$stmt = $conn->prepare("SELECT * FROM notes WHERE visibility=? ORDER BY id DESC");
$vis = "public";
$stmt->bind_param("s", $vis);
$stmt->execute();

$res = $stmt->get_result();

if ($res->num_rows === 0): ?>
    <div class="col-span-3 text-center py-12">
        <i class="fas fa-inbox text-5xl text-cyan-500/30 mb-4 block"></i>
        <p class="text-gray-400 text-lg">No pastes found in this category.</p>
    </div>
<?php endif;

while($row = $res->fetch_assoc()):
    $created_at = date('M d', strtotime($row['created_at'] ?? 'now'));
    $char_count = strlen($row['content']);
?>
    <div class="glassmorphism p-6 rounded-xl border border-cyan-500/30 card-hover flex flex-col">
        <!-- Top Section -->
        <div class="flex justify-between items-start mb-3">
            <h3 class="font-bold text-lg text-cyan-300 line-clamp-2 flex-1">
                <?= htmlspecialchars($row['title'] ?: 'Untitled') ?>
            </h3>
            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 flex-shrink-0">
                <i class="fas fa-eye"></i> Public
            </span>
        </div>

        <!-- Preview -->
        <div class="mb-4 p-3 rounded-lg bg-slate-900/50 border border-slate-700/50 flex-1">
            <p class="text-sm text-gray-400 font-mono line-clamp-3">
                <?= htmlspecialchars(substr($row['content'], 0, 100)) ?>...
            </p>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between text-xs text-gray-500 mb-4 pt-3 border-t border-cyan-500/20">
            <span><i class="fas fa-code"></i> <?= number_format($char_count) ?> chars</span>
            <span><i class="fas fa-calendar-alt"></i> <?= $created_at ?></span>
        </div>

        <!-- Action Button -->
        <a href="view.php?id=<?= urlencode($row['unique_id']) ?>"
           class="block w-full text-center btn-modern px-4 py-2 rounded-lg bg-gradient-to-r from-cyan-500/20 to-blue-500/20 hover:from-cyan-500/40 hover:to-blue-500/40 border border-cyan-500/50 text-cyan-300 font-semibold transition">
            <i class="fas fa-arrow-right"></i> View
        </a>
    </div>
<?php endwhile; ?>
    </div>

</div>

<script>
function filterPastes(type) {
    document.querySelectorAll('[onclick^="filterPastes"]').forEach(btn => {
        btn.classList.remove('bg-cyan-500/40', 'text-cyan-300', 'border-cyan-500/50');
        btn.classList.add('bg-transparent', 'text-gray-300', 'border-cyan-500/30');
    });
    
    event.target.closest('button').classList.add('bg-cyan-500/40', 'text-cyan-300', 'border-cyan-500/50');
    event.target.closest('button').classList.remove('bg-transparent', 'text-gray-300', 'border-cyan-500/30');
    
    showToast(`Showing ${type} pastes`, 'info');
}
</script>

<?php include "../includes/footer.php"; ?>
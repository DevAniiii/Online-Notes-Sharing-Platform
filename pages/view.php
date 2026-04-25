<?php
session_start();
include "../config/db.php";

// Get note ID from URL
$id = $_GET['id'] ?? '';

// Fetch the note from database
$stmt = $conn->prepare("SELECT * FROM notes WHERE unique_id=?");
$stmt->bind_param("s", $id);
$stmt->execute();
$res = $stmt->get_result();
$note = $res->fetch_assoc();

// ===== ALL LOGIC THAT REQUIRES HEADERS MUST BE BEFORE HEADER INCLUDE =====

// Handle password verification (must be before header include)
if (!empty($note['password'])) {
    if (!isset($_SESSION['unlocked'][$note['unique_id']])) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = $_POST['password'] ?? '';
            
            if (password_verify($input, $note['password'])) {
                $_SESSION['unlocked'][$note['unique_id']] = true;
                header("Location: view.php?id=" . urlencode($note['unique_id']));
                exit;
            }
        }
    }
}

// ===== NOW INCLUDE HEADER (after all header() calls) =====
include "../includes/header.php";

// Check if note is expired
$expiry_ts = false;
if (!empty($note['expiry'])) {
    $expiry_ts = strtotime($note['expiry']);
}

if ($note && $expiry_ts !== false && $expiry_ts < time()) {
    echo "<div class='max-w-4xl mx-auto px-6 py-12 text-center'>";
    echo "<div class='glassmorphism p-12 rounded-2xl border border-orange-500/30'>";
    echo "<i class='fas fa-hourglass-end text-5xl text-orange-400 mb-4 block'></i>";
    echo "<h2 class='text-2xl font-bold text-orange-400 mb-2'>Paste Expired</h2>";
    echo "<p class='text-gray-400 mb-6'>This paste expired on " . date('M d, Y \a\t H:i', $expiry_ts) . " and is no longer available.</p>";
    echo "<a href='/notes-platform/index.php' class='inline-block btn-modern px-6 py-2 rounded-lg bg-cyan-500/20 hover:bg-cyan-500/40 border border-cyan-500/50 text-cyan-300'>";
    echo "<i class='fas fa-arrow-left mr-2'></i> Go Home</a>";
    echo "</div></div>";
    include "../includes/footer.php";
    exit;
}

// Display not found error
if (!$note) {
    echo "<div class='max-w-4xl mx-auto px-6 py-12 text-center'>";
    echo "<div class='glassmorphism p-12 rounded-2xl border border-red-500/30'>";
    echo "<i class='fas fa-exclamation-triangle text-5xl text-red-400 mb-4 block'></i>";
    echo "<h2 class='text-2xl font-bold text-red-400 mb-2'>Paste Not Found</h2>";
    echo "<p class='text-gray-400 mb-6'>The paste you're looking for doesn't exist or has been deleted.</p>";
    echo "<a href='/notes-platform/index.php' class='inline-block btn-modern px-6 py-2 rounded-lg bg-cyan-500/20 hover:bg-cyan-500/40 border border-cyan-500/50 text-cyan-300'>";
    echo "<i class='fas fa-arrow-left mr-2'></i> Go Home</a>";
    echo "</div></div>";
    include "../includes/footer.php";
    exit;
}

// Check private note access
if ($note['visibility'] === 'private') {
    if (!$note['user_id'] || !isset($_SESSION['user_id']) || $_SESSION['user_id'] != $note['user_id']) {
        echo "<div class='max-w-4xl mx-auto px-6 py-12 text-center'>";
        echo "<div class='glassmorphism p-12 rounded-2xl border border-red-500/30'>";
        echo "<i class='fas fa-lock text-5xl text-red-400 mb-4 block'></i>";
        echo "<h2 class='text-2xl font-bold text-red-400 mb-2'>Access Denied</h2>";
        echo "<p class='text-gray-400'>This is a private paste and you don't have permission to view it.</p>";
        echo "</div></div>";
        include "../includes/footer.php";
        exit;
    }
}

// Display password prompt if not unlocked
if (!empty($note['password'])) {
    if (!isset($_SESSION['unlocked'][$note['unique_id']])) {
        $error = isset($_POST['password']) ? "Wrong password" : null;
        ?>

        <div class="max-w-md mx-auto px-6 py-12">
            <div class="glassmorphism p-8 rounded-2xl border border-yellow-500/30">
                <div class="text-center mb-6">
                    <i class="fas fa-lock text-5xl text-yellow-400 mb-4 block"></i>
                    <h2 class="text-2xl font-bold text-yellow-300 mb-2">Password Protected</h2>
                    <p class="text-gray-400 text-sm">This paste is password protected. Enter the password to view.</p>
                </div>

                <?php if($error): ?>
                    <div class="mb-4 p-3 rounded-lg bg-red-500/20 border border-red-500/50 text-red-300 text-sm">
                        <i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" onsubmit="handleUnlock(event)">
                    <input type="password" name="password" placeholder="Enter password..." required
                        class="w-full p-3 rounded-lg bg-slate-900/50 border border-cyan-500/30 focus:border-cyan-400 text-white placeholder-gray-500 mb-4">
                    <button type="submit" class="w-full btn-modern px-4 py-3 rounded-lg bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 font-semibold transition">
                        <i class="fas fa-unlock mr-2"></i> Unlock Paste
                    </button>
                </form>
            </div>
        </div>

        <?php
        include "../includes/footer.php";
        exit;
    }
}

// Note is accessible - display it
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];

$link = $protocol . $host . "/notes-platform/pages/view.php?id=" . urlencode($note['unique_id']);
$created_at = date('M d, Y \a\t H:i', strtotime($note['created_at'] ?? 'now'));

// Increment view count
$view_stmt = $conn->prepare("UPDATE notes SET view_count = view_count + 1 WHERE id = ?");
$view_stmt->bind_param("i", $note['id']);
$view_stmt->execute();

// Fetch updated note with new view count
$stmt_refresh = $conn->prepare("SELECT * FROM notes WHERE unique_id=?");
$stmt_refresh->bind_param("s", $id);
$stmt_refresh->execute();
$note = $stmt_refresh->get_result()->fetch_assoc();

?>

<div class="max-w-5xl mx-auto px-6 py-12">

    <!-- Header Section -->
    <div class="glassmorphism p-8 rounded-2xl border border-cyan-500/30 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-6">
            <div class="flex-1">
                <h1 class="text-4xl font-bold mb-2 text-cyan-300">
                    <?= htmlspecialchars($note['title'] ?? 'Untitled') ?>
                </h1>
                <div class="flex items-center gap-4 text-sm text-gray-400">
                    <span><i class="fas fa-calendar"></i> <?= $created_at ?></span>
                    <span><i class="fas fa-eye"></i> <?= $note['view_count'] ?> views</span>
                    <?php if ($note['character_count'] > 0): ?>
                        <span><i class="fas fa-code"></i> <?= $note['line_count'] ?? 1 ?> lines</span>
                    <?php endif; ?>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                        <i class="fas fa-eye"></i> <?= ucfirst($note['visibility']) ?>
                    </span>
                    <?php if (!empty($note['password'])): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-500/20 text-yellow-300 border border-yellow-500/30">
                            <i class="fas fa-lock"></i> Password Protected
                        </span>
                    <?php endif; ?>
                    <?php if ($note['expiry'] && $expiry_ts !== false): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-500/20 text-orange-300 border border-orange-500/30">
                            <i class="fas fa-hourglass-end"></i> Expires: <?= date('M d \a\t H:i:s', $expiry_ts) ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex gap-3 w-full md:w-auto">
                <button onclick="copyContent()" 
                    class="flex-1 md:flex-none btn-modern px-6 py-2 rounded-lg bg-cyan-500/20 hover:bg-cyan-500/40 border border-cyan-500/50 text-cyan-300 font-semibold transition">
                    <i class="fas fa-copy mr-2"></i> Copy Code
                </button>
                <a href="/notes-platform/index.php"
                    class="flex-1 md:flex-none btn-modern px-6 py-2 rounded-lg bg-blue-500/20 hover:bg-blue-500/40 border border-blue-500/50 text-blue-300 font-semibold transition text-center">
                    <i class="fas fa-home mr-2"></i> Home
                </a>
            </div>
        </div>
    </div>

    <!-- Code Block with Syntax Highlighting -->
    <div class="mb-8">
        <div class="glassmorphism rounded-2xl border border-cyan-500/30 overflow-hidden">
            <div class="bg-slate-900/50 border-b border-cyan-500/20 px-6 py-3 flex justify-between items-center">
                <span class="text-sm font-mono text-gray-500"><i class="fas fa-code"></i> <?= htmlspecialchars($note['language'] ?? 'Code') ?></span>
                <span class="text-xs text-gray-500"><?= $note['character_count'] ?? strlen($note['content']) ?> characters · <?= $note['line_count'] ?? substr_count($note['content'], "\n") + 1 ?> lines</span>
            </div>
            <pre id="codeBlock"
                class="bg-slate-950 text-gray-300 p-6 overflow-x-auto font-mono text-sm leading-relaxed whitespace-pre-wrap break-words"><code class="language-<?= htmlspecialchars(strtolower($note['language'] ?? 'plaintext')) ?>"><?= htmlspecialchars($note['content'] ?? '') ?></code></pre>
        </div>
    </div>

    <!-- Share Section -->
    <div class="glassmorphism p-8 rounded-2xl border border-cyan-500/30">
        <h3 class="text-lg font-bold text-cyan-300 mb-4">
            <i class="fas fa-share-alt mr-2"></i> Share This Paste
        </h3>
        
        <div class="flex gap-3">
            <input id="shareLink"
                value="<?= htmlspecialchars($link) ?>"
                readonly
                class="flex-1 p-3 rounded-lg bg-slate-900/50 border border-cyan-500/30 text-white font-mono text-sm">

            <button onclick="copyLink()" 
                class="btn-modern px-6 py-3 rounded-lg bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 font-semibold transition">
                <i class="fas fa-link mr-2"></i> Copy Link
            </button>
        </div>
    </div>

</div>

<script>
function copyContent() {
    const text = document.getElementById("codeBlock").innerText;
    navigator.clipboard.writeText(text).then(() => {
        showToast("Code copied to clipboard!", "success");
    }).catch(() => {
        showToast("Failed to copy code", "error");
    });
}

function copyLink() {
    const text = document.getElementById("shareLink").value;
    navigator.clipboard.writeText(text).then(() => {
        showToast("Link copied to clipboard!", "success");
    }).catch(() => {
        showToast("Failed to copy link", "error");
    });
}

function showToast(message, type = 'info') {
    const toast = document.createElement("div");
    toast.className = `fixed bottom-6 right-6 px-6 py-3 rounded-lg font-semibold success-toast z-50 ${
        type === 'success' ? 'bg-cyan-500/70 text-white' : 'bg-red-500/70 text-white'
    }`;
    toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i> ${message}`;
    document.body.appendChild(toast);

    setTimeout(() => toast.remove(), 3000);
}

function handleUnlock(e) {
    e.preventDefault();
    const btn = e.target.querySelector('button');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Checking...';
    btn.disabled = true;
    
    // Submit the form directly without delay
    e.target.submit();
}
</script>

<?php include "../includes/footer.php"; ?>
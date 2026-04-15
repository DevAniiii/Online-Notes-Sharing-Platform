<?php
session_start();
include "../config/db.php";
include "../includes/header.php";

$id = $_GET['id'] ?? '';

$stmt = $conn->prepare("SELECT * FROM notes WHERE unique_id=?");
$stmt->bind_param("s", $id);
$stmt->execute();
$res = $stmt->get_result();
$note = $res->fetch_assoc();

if (!$note) {
    echo "<div class='text-center text-red-500 mt-10'>Note not found</div>";
    include "../includes/footer.php";
    exit;
}

// 🔒 PRIVATE CHECK
if ($note['visibility'] === 'private') {
    if (!$note['user_id'] || !isset($_SESSION['user_id']) || $_SESSION['user_id'] != $note['user_id']) {
        echo "<div class='text-center text-red-500 mt-10'>Access denied</div>";
        include "../includes/footer.php";
        exit;
    }
}

// 🔑 PASSWORD PROTECTION
if (!empty($note['password'])) {

    if (!isset($_SESSION['unlocked'][$note['unique_id']])) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = $_POST['password'] ?? '';

            if (password_verify($input, $note['password'])) {
                $_SESSION['unlocked'][$note['unique_id']] = true;
                header("Location: view.php?id=" . $note['unique_id']);
                exit;
            } else {
                $error = "Wrong password";
            }
        }
        ?>

        <div class="max-w-md mx-auto mt-20 bg-gray-800 p-6 rounded shadow">
            <h2 class="mb-4 text-lg font-bold">🔒 This paste is protected</h2>

            <?php if(isset($error)): ?>
                <p class="text-red-400 mb-2"><?= $error ?></p>
            <?php endif; ?>

            <form method="POST">
                <input type="password" name="password" required
                    class="w-full p-2 mb-3 text-black rounded">
                <button class="bg-blue-600 hover:bg-blue-700 px-4 py-2 w-full rounded">
                    Unlock
                </button>
            </form>
        </div>

        <?php
        include "../includes/footer.php";
        exit;
    }
}

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];

$link = $protocol . $host . "/notes-platform/pages/view.php?id=" . urlencode($note['unique_id']);

?>

<div class="max-w-4xl mx-auto">

<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold">
        <?= htmlspecialchars($note['title'] ?? 'Untitled') ?>
    </h2>

    <button onclick="copyContent()" 
        class="bg-gray-700 hover:bg-gray-600 px-4 py-1 rounded text-sm">
        Copy
    </button>
</div>


<pre id="codeBlock"
class="bg-black text-green-400 p-4 rounded border border-gray-700 overflow-x-auto font-mono text-sm leading-relaxed whitespace-pre-wrap">
<?= htmlspecialchars($note['content'] ?? '') ?>
</pre>


<div class="mt-6">
    <p class="text-sm text-gray-400 mb-2">Share Link</p>

    <div class="flex gap-2">
        <input id="shareLink"
            value="<?= htmlspecialchars($link) ?>"
            readonly
            class="w-full p-2 text-black rounded">

        <button onclick="copyLink()" 
            class="bg-blue-600 hover:bg-blue-700 px-4 rounded">
            Copy
        </button>
    </div>
</div>

</div>

<script>
function copyContent() {
    const text = document.getElementById("codeBlock").innerText;
    navigator.clipboard.writeText(text);
    showToast("Content copied");
}

function copyLink() {
    const text = document.getElementById("shareLink").value;
    navigator.clipboard.writeText(text);
    showToast("Link copied");
}

function showToast(msg) {
    const div = document.createElement("div");
    div.innerText = msg;
    div.className = "fixed bottom-5 right-5 bg-gray-800 text-white px-4 py-2 rounded shadow";
    document.body.appendChild(div);

    setTimeout(() => div.remove(), 2000);
}
</script>

<?php include "../includes/footer.php"; ?>
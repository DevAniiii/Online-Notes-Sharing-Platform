<?php session_start(); ?>
<?php include "../includes/header.php"; ?>

<div class="max-w-4xl mx-auto">

<h2 class="text-3xl mb-6 font-bold">Create New Paste</h2>

<form action="../api/create_note.php" method="POST" class="space-y-4">


    <input type="text" name="title" placeholder="Paste Title"
        class="w-full p-3 rounded bg-gray-800 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">


    <textarea name="content" rows="12" placeholder="Write or paste your code..."
        class="w-full p-3 rounded bg-black text-green-400 font-mono border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>

    <div class="grid md:grid-cols-3 gap-4">

        <div>
            <label class="text-sm text-gray-400">Visibility</label>
            <select name="visibility" id="visibility"
                class="w-full p-2 rounded bg-gray-800 border border-gray-700">
                <option value="public">Public</option>
                <option value="unlisted">Unlisted</option>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <option value="private">Private</option>
                <?php endif; ?>
            </select>
        </div>

        <div>
            <label class="text-sm text-gray-400">Password (optional)</label>
            <input type="password" name="note_password" id="passwordInput"
                placeholder="Protect paste"
                class="w-full p-2 rounded bg-gray-800 border border-gray-700">
        </div>

    
        <div>
            <label class="text-sm text-gray-400">Expiry</label>
            <select name="expiry"
                class="w-full p-2 rounded bg-gray-800 border border-gray-700">
                <option value="never">Never</option>
                <option value="10min">10 Minutes</option>
                <option value="1hour">1 Hour</option>
                <option value="1day">1 Day</option>
            </select>
        </div>

    </div>

    <button class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded transition w-full">
        Create Paste
    </button>

</form>

</div>

<script>

const visibility = document.getElementById("visibility");
const passwordInput = document.getElementById("passwordInput");

visibility.addEventListener("change", () => {
    if (visibility.value === "private") {
        passwordInput.disabled = true;
        passwordInput.value = "";
        passwordInput.placeholder = "Not needed for private";
    } else {
        passwordInput.disabled = false;
        passwordInput.placeholder = "Protect paste";
    }
});
</script>

<?php include "../includes/footer.php"; ?>
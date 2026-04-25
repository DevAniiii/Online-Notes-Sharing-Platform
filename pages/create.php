<?php session_start(); ?>
<?php include "../includes/header.php"; ?>

<div class="max-w-4xl mx-auto px-6 py-12">

    <!-- Header -->
    <div class="mb-12">
        <h2 class="text-4xl font-bold mb-3">
            <span class="glow-text">Create New Paste</span>
        </h2>
        <p class="text-gray-400">Share your code securely with customizable privacy options</p>
    </div>

    <!-- Form Container -->
    <form action="../api/create_note.php" method="POST" class="glassmorphism p-8 rounded-2xl border border-cyan-500/30 space-y-6">

        <!-- Title Input -->
        <div>
            <label class="block text-sm font-semibold text-cyan-300 mb-2">
                <i class="fas fa-heading"></i> Paste Title
            </label>
            <input type="text" name="title" placeholder="Enter a descriptive title..." required
                class="w-full p-4 rounded-lg bg-slate-900/50 border border-cyan-500/30 focus:border-cyan-400 text-white placeholder-gray-500">
        </div>

        <!-- Content Textarea -->
        <div>
            <label class="block text-sm font-semibold text-cyan-300 mb-2">
                <i class="fas fa-code"></i> Code Content
            </label>
            <textarea name="content" rows="14" placeholder="Paste your code here..." required
                class="w-full p-4 rounded-lg bg-slate-900/50 border border-cyan-500/30 focus:border-cyan-400 text-gray-200 font-mono text-sm placeholder-gray-500"></textarea>
            <p class="text-xs text-gray-500 mt-2"><i class="fas fa-lightbulb"></i> Supports syntax highlighting</p>
        </div>

        <!-- Options Grid -->
        <div class="grid md:grid-cols-3 gap-6">

            <!-- Visibility -->
            <div>
                <label class="block text-sm font-semibold text-cyan-300 mb-2">
                    <i class="fas fa-eye"></i> Visibility
                </label>
                <select name="visibility" id="visibility"
                    class="w-full p-3 rounded-lg bg-slate-900/50 border border-cyan-500/30 focus:border-cyan-400 text-white">
                    <option value="public">
                        <i class="fas fa-globe"></i> Public
                    </option>
                    <option value="unlisted">
                        <i class="fas fa-link"></i> Unlisted
                    </option>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <option value="private">
                            <i class="fas fa-lock"></i> Private
                        </option>
                    <?php endif; ?>
                </select>
                <p class="text-xs text-gray-500 mt-2">Who can see this paste?</p>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-semibold text-cyan-300 mb-2">
                    <i class="fas fa-key"></i> Password (Optional)
                </label>
                <input type="password" name="note_password" id="passwordInput"
                    placeholder="Protect with password"
                    class="w-full p-3 rounded-lg bg-slate-900/50 border border-cyan-500/30 focus:border-cyan-400 text-white placeholder-gray-500">
                <p class="text-xs text-gray-500 mt-2">Leave empty if not needed</p>
            </div>

            <!-- Expiry -->
            <div>
                <label class="block text-sm font-semibold text-cyan-300 mb-2">
                    <i class="fas fa-hourglass-end"></i> Auto Delete
                </label>
                <select name="expiry"
                    class="w-full p-3 rounded-lg bg-slate-900/50 border border-cyan-500/30 focus:border-cyan-400 text-white">
                    <option value="never"><i class="fas fa-infinity"></i> Never</option>
                    <option value="10min"><i class="fas fa-clock"></i> 10 Minutes</option>
                    <option value="1hour"><i class="fas fa-hourglass-half"></i> 1 Hour</option>
                    <option value="1day"><i class="fas fa-calendar-day"></i> 1 Day</option>
                </select>
                <p class="text-xs text-gray-500 mt-2">Auto-delete after set time</p>
            </div>

        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <button type="submit" class="w-full btn-modern px-6 py-4 rounded-lg bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 font-bold text-white text-lg flex items-center justify-center gap-2 transition">
                <i class="fas fa-paper-plane"></i> Create Paste
            </button>
        </div>

        <!-- Info Box -->
        <div class="glassmorphism p-4 rounded-lg border border-blue-500/30 text-sm text-gray-300">
            <i class="fas fa-info-circle text-blue-400 mr-2"></i>
            <strong>Pro Tip:</strong> Use passwords and expiry dates for sensitive code. Private pastes are only visible to you.
        </div>

    </form>

</div>

<script>
const visibility = document.getElementById("visibility");
const passwordInput = document.getElementById("passwordInput");

visibility.addEventListener("change", () => {
    if (visibility.value === "private") {
        passwordInput.disabled = true;
        passwordInput.value = "";
        passwordInput.placeholder = "Not available for private pastes";
    } else {
        passwordInput.disabled = false;
        passwordInput.placeholder = "Protect with password";
    }
});

// Form validation feedback
document.querySelector('form').addEventListener('submit', function(e) {
    const title = document.querySelector('input[name="title"]').value.trim();
    const content = document.querySelector('textarea[name="content"]').value.trim();
    
    if (!title || !content) {
        e.preventDefault();
        showToast("Please fill in all required fields", "error");
    }
});

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-6 right-6 px-6 py-3 rounded-lg font-semibold success-toast z-50 ${
        type === 'error' ? 'bg-red-500/70 text-white' : 'bg-cyan-500/70 text-white'
    }`;
    toast.innerHTML = `<i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'} mr-2"></i> ${message}`;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.remove(), 3000);
}
</script>

<?php include "../includes/footer.php"; ?>
</div>

<!-- Modern Footer -->
<footer class="glassmorphism border-t border-cyan-500/20 py-8 px-6 mt-12">
    <div class="max-w-7xl mx-auto">
        <div class="grid md:grid-cols-3 gap-8 mb-6">
            <div>
                <h3 class="font-bold text-cyan-400 mb-3 flex items-center gap-2">
                    <i class="fas fa-code"></i> PasteNotes
                </h3>
                <p class="text-gray-400 text-sm">Fast, secure, and modern code sharing platform.</p>
            </div>
            <div>
                <h4 class="font-semibold mb-3 text-cyan-400">Features</h4>
                <ul class="text-gray-400 text-sm space-y-1">
                    <li><i class="fas fa-shield-alt"></i> Password Protected</li>
                    <li><i class="fas fa-clock"></i> Auto Expiry</li>
                    <li><i class="fas fa-lock"></i> Privacy Control</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-3 text-cyan-400">Connect</h4>
                <div class="flex gap-3 text-sm">
                    <a href="https://github.com/DevAniiii" target="_blank" class="hover:text-cyan-400 transition">
                        <i class="fab fa-github"></i> GitHub
                    </a>
                </div>
            </div>
        </div>
        <div class="border-t border-cyan-500/20 pt-6 text-center text-sm text-gray-400">
            <p>© <span id="year"></span> DevAniiii and Team. A futuristic code sharing platform.</p>
        </div>
    </div>
</footer>

<script>
document.getElementById("year").innerText = new Date().getFullYear();
</script>

<script src="/notes-platform/assets/js/ui.js"></script>
</body>
</html>
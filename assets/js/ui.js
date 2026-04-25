/**
 * Modern UI Interactions & Enhancements
 * Futuristic code sharing platform
 */

window.showToast = function(message, type = 'info', duration = 3000) {
    const toast = document.createElement('div');
    const icons = {
        'success': 'check-circle',
        'error': 'exclamation-circle',
        'info': 'info-circle',
        'warning': 'exclamation-triangle'
    };
    
    const colors = {
        'success': 'bg-cyan-500/70 text-white',
        'error': 'bg-red-500/70 text-white',
        'info': 'bg-blue-500/70 text-white',
        'warning': 'bg-yellow-500/70 text-white'
    };
    
    toast.className = `fixed bottom-6 right-6 px-6 py-3 rounded-lg font-semibold success-toast z-50 ${colors[type] || colors['info']}`;
    toast.innerHTML = `<i class="fas fa-${icons[type] || icons['info']} mr-2"></i> ${message}`;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOutDown 0.4s ease';
        setTimeout(() => toast.remove(), 400);
    }, duration);
};

// Copy to Clipboard Utility
    navigator.clipboard.writeText(text).then(() => {
        showToast(message, 'success');
    }).catch(() => {
        showToast('Failed to copy', 'error');
    });
};

// Form Validation
document.addEventListener('DOMContentLoaded', function() {querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.transform = 'scale(1.02)';
        });
        input.addEventListener('blur', function() {
            this.style.transform = 'scale(1)';
        });
    });

    // Smooth scroll behavior
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Ripple effect on buttons
    document.querySelectorAll('.btn-modern').forEach(button => {
        button.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.className = 'ripple';
            
            this.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        });
    });

    // Add ripple CSS dynamically
    if (!document.getElementById('ripple-style')) {
        const style = document.createElement('style');
        style.id = 'ripple-style';
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.5);
                transform: scale(0);
                animation: ripple-animation 0.6s ease-out;
                pointer-events: none;
            }
            
            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
});

// Lazy loading for images (if any)
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                }
                observer.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => imageObserver.observe(img));
}

// Loading state management
window.setLoading = function(element, isLoading) {
    if (isLoading) {
        element.disabled = true;
        element.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
    } else {
        element.disabled = false;
        element.innerHTML = element.dataset.originalText;
    }
};

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl+S to save (can be extended for AJAX saves)
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
document.addEventListener('keydown', function(e) {

    // Escape to close modals
    if (e.key === 'Escape') {
        document.querySelectorAll('[role="dialog"]').forEach(dialog => {
            dialog.style.display = 'none';
        });
    }
});
// Detect theme preference
function initializeTheme() {
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    document.documentElement.style.colorScheme = prefersDark ? 'dark' : 'light';
}


// Performance optimization - defer non-critical scripts
if ('requestIdleCallback' in window) {
    requestIdleCallback(() => {
        // Non-critical tasks here
    });
}

if ('requestIdleCallback' in window) {
    requestIdleCallback(() => {
    });
}

document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
    } else {
    const text = document.getElementById("codeBlock")?.innerText;
    if (text) {
        copyToClipboard(text, 'Code copied to clipboard!');
    }
};

// Smooth transitions on page load

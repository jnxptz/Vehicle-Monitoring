// Admin Dashboard JavaScript

// Close hamburger menu when a link is clicked
document.querySelectorAll('.hamburger-dropdown a').forEach(link => {
    link.addEventListener('click', () => {
        const hamburgerToggle = document.getElementById('hamburger-toggle');
        if (hamburgerToggle) {
            hamburgerToggle.checked = false;
        }
    });
});

// Also handle form submission (logout)
document.querySelectorAll('.hamburger-dropdown form').forEach(form => {
    form.addEventListener('submit', () => {
        const hamburgerToggle = document.getElementById('hamburger-toggle');
        if (hamburgerToggle) {
            hamburgerToggle.checked = false;
        }
    });
});

// Toggle table row details
function toggleRow(rowId) {
    const detailsRow = document.getElementById(rowId + '-details');
    if (!detailsRow) return;

    const isVisible = detailsRow.style.display === 'table-row';
    detailsRow.style.display = isVisible ? 'none' : 'table-row';
}

// Toggle expand/collapse icon
function toggleIcon(btn) {
    const expanded = btn.getAttribute('aria-expanded') === 'true';
    btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
    
    // Update icon if needed
    const icon = btn.querySelector('i, span');
    if (icon) {
        if (expanded) {
            icon.textContent = '▼'; // or use appropriate icon class
        } else {
            icon.textContent = '▶'; // or use appropriate icon class
        }
    }
}

// Initialize dashboard when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling for better UX
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add keyboard navigation for toggle buttons
    document.querySelectorAll('.toggle-btn').forEach(btn => {
        btn.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });

    // Add loading states for form submissions
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Loading...';
            }
        });
    });

    // Initialize tooltips if needed
    initializeTooltips();
});

// Tooltip functionality
function initializeTooltips() {
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.getAttribute('data-tooltip');
            tooltip.style.cssText = `
                position: absolute;
                background: #1f2937;
                color: white;
                padding: 6px 12px;
                border-radius: 6px;
                font-size: 12px;
                white-space: nowrap;
                z-index: 1000;
                pointer-events: none;
                opacity: 0;
                transition: opacity 0.2s ease;
            `;
            
            document.body.appendChild(tooltip);
            
            // Position tooltip
            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
            
            // Show tooltip
            setTimeout(() => {
                tooltip.style.opacity = '1';
            }, 10);
        });
        
        element.addEventListener('mouseleave', function() {
            const tooltip = document.querySelector('.tooltip');
            if (tooltip) {
                tooltip.style.opacity = '0';
                setTimeout(() => {
                    tooltip.remove();
                }, 200);
            }
        });
    });
}

// Export functions for external use if needed
window.AdminDashboard = {
    toggleRow,
    toggleIcon,
    initializeTooltips
};

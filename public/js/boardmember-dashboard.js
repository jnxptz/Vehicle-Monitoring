// Close hamburger menu when a link is clicked
document.querySelectorAll('.hamburger-dropdown a').forEach(link => {
    link.addEventListener('click', () => {
        document.getElementById('hamburger-toggle').checked = false;
    });
});

// Also handle form submission (logout)
document.querySelectorAll('.hamburger-dropdown form').forEach(form => {
    form.addEventListener('submit', () => {
        document.getElementById('hamburger-toggle').checked = false;
    });
});

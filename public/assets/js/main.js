// public/assets/js/main.js

document.addEventListener('DOMContentLoaded', function () {
    // Sidebar elements
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const mobileSidebarClose = document.getElementById('mobileSidebarClose');
    const mobileSidebarOverlay = document.getElementById('mobileSidebarOverlay');

    function openSidebar() {
        if (!mobileSidebar) return;
        mobileSidebar.classList.remove('translate-x-full');
        mobileSidebar.classList.add('translate-x-0');
        mobileSidebarOverlay.classList.remove('opacity-0', 'pointer-events-none');
        mobileSidebarOverlay.classList.add('opacity-100');
        // lock body scroll
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        if (!mobileSidebar) return;
        mobileSidebar.classList.remove('translate-x-0');
        mobileSidebar.classList.add('translate-x-full');
        mobileSidebarOverlay.classList.remove('opacity-100');
        mobileSidebarOverlay.classList.add('opacity-0', 'pointer-events-none');
        document.body.style.overflow = '';
    }

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', openSidebar);
    }
    if (mobileSidebarClose) {
        mobileSidebarClose.addEventListener('click', closeSidebar);
    }
    if (mobileSidebarOverlay) {
        mobileSidebarOverlay.addEventListener('click', closeSidebar);
    }

    // Close on ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeSidebar();
    });
});

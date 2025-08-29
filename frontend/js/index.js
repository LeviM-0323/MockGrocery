document.addEventListener('DOMContentLoaded', () => {
    // Navbar burger
    const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
    $navbarBurgers.forEach(el => {
        el.addEventListener('click', () => {
            const target = el.dataset.target;
            const $target = document.getElementById(target);
            el.classList.toggle('is-active');
            $target.classList.toggle('is-active');
        });
    });

    // Toast notification for cart
    if (typeof window.__TOAST_MESSAGE__ !== "undefined" && window.__TOAST_MESSAGE__) {
        const toast = document.getElementById('toast');
        toast.textContent = window.__TOAST_MESSAGE__;
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 2000);
    }

    // Cart drawer logic
    const cartDrawer = document.getElementById('cartDrawer');
    const cartToggleBtn = document.getElementById('cartToggleBtn');
    const closeCartDrawer = document.getElementById('closeCartDrawer');
    if (cartDrawer && cartToggleBtn && closeCartDrawer) {
        cartToggleBtn.addEventListener('click', function() {
            cartDrawer.classList.add('open');
            cartToggleBtn.classList.add('is-hidden');
        });
        closeCartDrawer.addEventListener('click', function() {
            cartDrawer.classList.remove('open');
            cartToggleBtn.classList.remove('is-hidden');
        });
        // Optional: Close drawer on outside click
        document.addEventListener('mousedown', function(e) {
            if (cartDrawer.classList.contains('open') && !cartDrawer.contains(e.target) && e.target !== cartToggleBtn) {
                cartDrawer.classList.remove('open');
                cartToggleBtn.classList.remove('is-hidden');
            }
        });
    }
});
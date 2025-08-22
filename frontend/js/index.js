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
});
const openButton = document.getElementById('open-button');
const closeButton = document.getElementById('close-button');
const mobileMenu = document.getElementById('mobile-menu');
const backdrop = document.getElementById('backdrop');
const offCanvas = document.getElementById('off-canvas');

openButton.addEventListener('click', () => {
    mobileMenu.classList.remove('hidden');
    setTimeout(() => {
        backdrop.classList.remove('opacity-0');
        backdrop.classList.add('opacity-100');
        offCanvas.classList.remove('-translate-x-full');
    }, 10);
});

closeButton.addEventListener('click', () => {
    backdrop.classList.remove('opacity-100');
    backdrop.classList.add('opacity-0');
    offCanvas.classList.add('-translate-x-full');
    setTimeout(() => {
        mobileMenu.classList.add('hidden');
    }, 300);
});

backdrop.addEventListener('click', () => {
    backdrop.classList.remove('opacity-100');
    backdrop.classList.add('opacity-0');
    offCanvas.classList.add('-translate-x-full');
    setTimeout(() => {
        mobileMenu.classList.add('hidden');
    }, 300);
});

document.addEventListener('DOMContentLoaded', function () {
    const menuButton = document.getElementById('menu-button');
    const menu = document.getElementById('menu');

    menuButton.addEventListener('click', function () {
        const expanded = menuButton.getAttribute('aria-expanded') === 'true' || false;
        menuButton.setAttribute('aria-expanded', !expanded);
        menu.classList.toggle('opacity-0');
        menu.classList.toggle('scale-95');
        menu.classList.toggle('opacity-100');
        menu.classList.toggle('scale-100');
    });

    document.addEventListener('click', function (event) {
        if (!menuButton.contains(event.target) && !menu.contains(event.target)) {
            menuButton.setAttribute('aria-expanded', 'false');
            menu.classList.add('opacity-0');
            menu.classList.add('scale-95');
            menu.classList.remove('opacity-100');
            menu.classList.remove('scale-100');
        }
    });
});

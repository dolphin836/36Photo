document.addEventListener('DOMContentLoaded', () => {
    const navbarBurger = document.getElementById('navbar-burger');
    const navbarPhoto  = document.getElementById('navbarPhoto');

    navbarBurger.addEventListener('click', () => {
        navbarBurger.classList.toggle('is-active');
        navbarPhoto.classList.toggle('is-active');
    });
});
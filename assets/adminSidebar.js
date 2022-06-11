const sideBar = document.getElementById('offcanvasScrolling');
const breakPointWidth = 765;

if (window.innerWidth >= breakPointWidth) {
    sideBar.classList.add('position-sticky');
    sideBar.classList.remove('offcanvas');
    sideBar.classList.remove('offcanvas-start');
}

window.addEventListener('resize', () => {
    if (window.innerWidth <= breakPointWidth) {
        sideBar.classList.add('offcanvas');
        sideBar.classList.add('offcanvas-start');
        sideBar.classList.add('position-fixed');
        sideBar.classList.remove('position-sticky');
    } else {
        sideBar.classList.remove('offcanvas');
        sideBar.classList.remove('offcanvas-start');
        sideBar.classList.remove('position-fixed');
        sideBar.classList.add('position-sticky');
        sideBar.style.visibility = 'visible';
    }
});
const sideBar = document.getElementById('offcanvasScrolling');
const breakPointWidth = 765;

if (window.innerWidth >= breakPointWidth) {
    fixedMode() 
}

window.addEventListener('resize', () => {
    if (window.innerWidth <= breakPointWidth) {
        canvasMode();
    } else {
        fixedMode() 
    }
});

function canvasMode()
{
    sideBar.classList.add('offcanvas');
    sideBar.classList.add('offcanvas-start');
    sideBar.classList.remove('position-sticky'); 
}

function fixedMode() 
{
    sideBar.classList.remove('offcanvas');
    sideBar.classList.remove('offcanvas-start');
    sideBar.classList.add('position-sticky');
    sideBar.style.visibility = 'visible';
}
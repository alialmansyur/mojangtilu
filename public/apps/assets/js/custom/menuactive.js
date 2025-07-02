$(document).ready(function () {
    function loadActiveMenu() {
        let activeSubMenuId = localStorage.getItem('active_submenu');
        let activeMenuId = localStorage.getItem('active_menu');

        $(".sidebar-item, .submenu, .submenu-item").removeClass("active");
        if (activeSubMenuId) {
            $("#submenu" + activeSubMenuId).addClass("active");
        }

        if (activeMenuId) {
            let menuElement = $("#menu" + activeMenuId);
            menuElement.addClass("active");

            if (menuElement.hasClass("has-sub")) {
                $("#submenu-parent" + activeMenuId).addClass("active");
            }
        }
    }

    window.updateActiveMenu = function (menuId, parentId) {
        localStorage.setItem('active_menu', menuId);
        if (parentId) {
            localStorage.setItem('active_submenu', menuId);
            localStorage.setItem('active_menu', parentId);
        } else {
            localStorage.removeItem('active_submenu');
        }
        loadActiveMenu();
    };

    loadActiveMenu();
});

function logout() {
    localStorage.removeItem('jwt_token');
    localStorage.removeItem('active_menu');
    localStorage.removeItem('active_menus');
    localStorage.removeItem('active_submenu');
    localStorage.clear();
    window.location.href = "/logout";
}
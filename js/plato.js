window.addEventListener("load", function () {
    var flechaAbajo = document.getElementById("flechaAbajo");
    var numFlecha = 100;
    var menuOculto = document.getElementById("menuOculto");

    var num = 1.1 * (parseInt(window.innerHeight));
    window.addEventListener("scroll", function () {
        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        if (scrollTop > numFlecha) {
            flechaAbajo.setAttribute("hidden", "");
        } else {
            flechaAbajo.removeAttribute("hidden");
        }
        if (scrollTop > num) {
            menuOculto.removeAttribute("hidden");
        } else {
            menuOculto.setAttribute("hidden", "");
        }
    });
});

window.addEventListener("load", function () {
    var flechaAbajo = document.getElementById("flechaAbajo");
    var numFlecha = 100;
    window.addEventListener("scroll", function () {
        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        if (scrollTop > numFlecha) {
            flechaAbajo.setAttribute("hidden", "");
        } else {
            flechaAbajo.removeAttribute("hidden");
        }
    });
});

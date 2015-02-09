function inicio() {
    var paginaActual = 0;

    /*
     * Mostrar imagenes que se van ha subir **********************************
     */

    function precargar(evt) {
        var files = evt.files; // FileList object
        var lista = evt.parentNode.lastElementChild;
        lista.innerHTML = "";
        for (var i = 0, f; f = files[i]; i++) {
            // Only process image files.
            if (!f.type.match('image.*')) {
                continue;
            }
            var reader = new FileReader();
            // Closure to capture the file information.
            reader.onload = (function (theFile) {
                return function (e) {
                    // Render thumbnail.
                    var span = document.createElement('span');
                    span.innerHTML = ['<img class="thumb" src="', e.target.result,
                        '" title="', escape(theFile.name), '"/>'].join('');
                    lista.insertBefore(span, null);
                };
            })(f);
            // Read in the image file as a data URL.
            reader.readAsDataURL(f);
        }
    }


    var imagenes = document.getElementsByClassName("img");
    var ppal = document.getElementsByClassName("ppal");
    for (var i = 0; i < imagenes.length; i++) {
        ppal[i].disabled = true;
        imagenes[i].addEventListener("change", function (evt) {
            precargar(evt.target);
            evt.target.parentNode.nextElementSibling.lastElementChild.disabled = false;
        });
    }

    function filtroNumero(e) {
        var codigo = e.charCode || e.keyCode;
        if (codigo < 32 || codigo == 37 || codigo == 38 || codigo == 39 || codigo == 40) {
            return;
        }
        var texto = String.fromCharCode(codigo);

        //var punto = false;
        var permitidos = "0123456789";
        if (permitidos.indexOf(texto) === -1 && codigo != 46) {
            // Es un carácter no permitido
            if (e.preventDefault) {
                e.preventDefault();
            }
            return false;
        } else if (e.target.value.indexOf('.') >= 0 && codigo == 46) {
            // Ya tiene un punto
            if (e.preventDefault) {
                e.preventDefault();
            }
            return false;
        }
    }

    /*
     * Mostrar/Ocultar elementos de la pagina  ********************************
     */

    var conectarUsuario = function () {
        login.setAttribute("class", "oculto");
        btDesconectar.setAttribute("class", "block");
        tabla.setAttribute("class", "flex");
    }

    var desconectarUsuario = function () {
        login.setAttribute("class", "flex");
        btDesconectar.setAttribute("class", "oculto");
        modaldialog.setAttribute("class", "oculto");
        tabla.setAttribute("class", "oculto");
        form.setAttribute("class", "oculto");
    }

    var comprobarNombre = function comprobarNombre(text) {
        console.log(text);
        var ojson = JSON.parse(text);
        if (ojson.r == 1) {
            nombreValido.textContent = "Nombre ya en uso";
        } else {
            nombreValido.textContent = "";
        }
    };


    var mostrarFormEditar = function (id) {
        ajax = new XMLHttpRequest();
        ajax.open("GET", "ajaxGetObjeto.php?id=" + id, true);
        ajax.onreadystatechange = function () {
            if (ajax.readyState === 4 && ajax.status === 200) {
                console.log(ajax.responseText);
                ojson = JSON.parse(ajax.responseText);
                if (ojson.id !== "") {
                    nombre.value = ojson.nombre;
                    descripcion.value = ojson.descripcion;
                    precio.value = ojson.precio;

                    formTitle.textContent = "Editar plato: " + ojson.nombre;
                    login.setAttribute("class", "oculto");
                    tabla.setAttribute("class", "oculto");
                    modaldialog.setAttribute("class", "oculto");
                    btDesconectar.setAttribute("class", "block");
                    form.setAttribute("class", "block");

                    nombreValido.textContent = "";
                    descripcionValido.textContent = "";
                    precioValido.textContent = "";

                    nombre.addEventListener("blur", function () {
                        if (nombre.value === "") {
                            nombreValido.textContent = "Necesario un nombre";
                        } else if (nombre.value !== ojson.nombre) {
                            var ajax = new Ajax();
                            ajax.setUrl("ajaxComprobarNombre.php?nombre=" + nombre.value);
                            ajax.setRespuesta(comprobarNombre);
                            ajax.doPeticion();
                        }
                    });
                    descripcion.addEventListener("change", function () {
                        if (descripcion.value.length < 10) {
                            descripcionValido.textContent = "Al menos 10 caracteres";
                        } else {
                            descripcionValido.textContent = "";
                        }
                    });
                    precio.addEventListener("change", function () {
                        if (precio.value.length < 1) {
                            precioValido.textContent = "Necesario un precio";
                        } else if (precio.value.length < 2) {
                            if (precio.value == '.')
                                precioValido.textContent = "Precio no valido";
                        } else {
                            precioValido.textContent = "";
                        }
                    });

                    var reset = document.getElementsByClassName("reset");
                    var ppal = document.getElementsByName("ppal");
                    var imgInput = document.getElementsByClassName("img");
                    for (i in ojson.fotos) {
                        if (ojson.fotos[i] !== "") {
                            console.log(i);
                            num = i.substring(i.length - 1);
                            imgInput[num - 1].setAttribute("hidden", "");
                            var imgNew = document.createElement("img");
                            imgNew.setAttribute("src", ojson.fotos[i]);
                            imgNew.setAttribute("class", "miniatura");
                            borrarImg[num - 1].parentNode.insertBefore(imgNew, borrarImg[num - 1]);
                            ppal[num - 1].disabled = false;
                            reset[num - 1].value = "Borrar";
                        }
                    }
                    ppal[ojson.foto.substring(4, 5) - 1].checked = true;
                    /* limpiar */
                    for (var i = 0; i < reset.length; i++) {
                        reset[i].onclick = function (e) {
                            //reset[i].addEventListener("click", function (e) {
                            var previo = e.target.previousElementSibling;
                            if (previo.getAttribute("class") === "miniatura") {
                                /* borrar foto */
                                ajax = new XMLHttpRequest();
                                ajax.open("GET", "ajaxBorrarFoto.php?foto=" + previo.getAttribute("src"), true);
                                ajax.onreadystatechange = function () {
                                    if (ajax.readyState == 4) {
                                        if (ajax.status == 200) {
                                            console.log(ajax.responseText);
                                            var ojsonBorrar = JSON.parse(ajax.responseText);
                                            if (ojsonBorrar.r == 1) {
                                                e.target.value = "Limpiar";
                                                e.target.previousElementSibling.parentNode.removeChild(e.target.previousElementSibling);
                                                var radio = e.target.parentNode.nextElementSibling.lastElementChild;
                                                radio.disabled = true;
                                                radio.checked = false;
                                                e.target.parentNode.firstElementChild.nextElementSibling.removeAttribute("hidden");
                                            }
                                        }
                                    }
                                };
                                ajax.send();
                            }
                            //});
                        };
                    }

                    btAceptar.onclick = function () {
                        var errorVal = false;
                        if (nombre.value === "") {
                            nombreValido.textContent = "Necesario un nombre";
                            errorVal = true;
                        }
                        if (!errorVal && nombre.value !== ojson.nombre) {
                            ajax = new XMLHttpRequest();
                            ajax.open("GET", "ajaxComprobarNombre.php", true);
                            ajax.onreadystatechange = function () {
                                if (ajax.readyState == 4) {
                                    if (ajax.status == 200) {
                                        console.log("nombre: " + ajax.responseText);
                                        var ojson = JSON.parse(ajax.responseText);
                                        if (ojson.r == 1) {
                                            nombreValido.textContent = "Nombre ya en uso";
                                            errorVal = true;
                                        }
                                    }
                                }
                            };
                            ajax.send();
                        }

                        if (nombreValido.textContent !== "")
                            errorVal = true;
                        if (descripcion.value.length < 10) {
                            errorVal = true;
                            descripcionValido.textContent = "Al menos 10 caracteres";
                        }
                        if (precio.value.length < 1) {
                            precioValido.textContent = "Necesario un precio";
                            errorVal = true;
                        } else if (precio.value.length < 2) {
                            if (precio.value === '.') {
                                precioValido.textContent = "Precio no valido";
                                errorVal = true;
                            }
                        } else {
                            precioValido.textContent = "";
                        }
                        if (errorVal) {
                            mostrarMensaje("Valores no validos");
                        } else {

                            var imagen1 = document.getElementById("imagen1");
                            var imagen2 = document.getElementById("imagen2");
                            var imagen3 = document.getElementById("imagen3");
                            var imagen4 = document.getElementById("imagen4");

                            /* ver como validar fotos */
                            if (!imagen1.hasAttribute("hidden") && !imagen2.hasAttribute("hidden") &
                                    !imagen3.hasAttribute("hidden") && !imagen4.hasAttribute("hidden") &&
                                    imagen1.value == "" && imagen2.value == "" &&
                                    imagen3.value == "" && imagen4.value == "") {
                                mostrarMensaje("No ha seleccionado ninguna imagen");
                            } else {
                                var auxPpal = "";
                                var ppal = document.getElementsByName("ppal");
                                for (var i = 0; i < ppal.length; i++) {
                                    if (ppal[i].checked) {
                                        auxPpal = ppal[i].value;
                                    }
                                }
                                console.log("js cogue Ppal: " + auxPpal);
                                if (auxPpal === "") {
                                    mostrarMensaje("No ha seleccionado ninguna imagen como principal");
                                } else {
                                    var parametros = new FormData();
                                    var imagenes = document.getElementsByClassName("img");
                                    console.log(imagenes);
                                    var imgValidas = new Array();
                                    //var newPpal = ojson.foto;
                                    for (var i = 0; i < imagenes.length; i++) {
                                        console.log(imagenes[i].value);
                                        if (imagenes[i].value !== "") {
                                            //  console.log("value: " + imagenes[i].value);
                                            imgValidas.push(imagenes[i]);
                                            //  if (i === auxPpal - 1)
                                            //    newPpal = imgValidas.length - 1;
                                        }
                                    }
                                    parametros.append("id", ojson.id);
                                    parametros.append("nombre", nombre.value);
                                    parametros.append("descripcion", descripcion.value);
                                    parametros.append("precio", precio.value);
                                    parametros.append("principal", auxPpal);
                                    console.log("js manda Ppal: " + auxPpal);
                                    var imgExistentes = document.querySelectorAll(".miniatura");
                                    console.log(imgExistentes.length);
                                    parametros.append("imgExistentes", imgExistentes.length);
                                    for (i = 0; i < imgValidas.length; i++) {
                                        archivoactual = imgValidas[i].files[0];
                                        console.log(archivoactual);
                                        parametros.append('archivo[]', archivoactual, archivoactual.name);
                                    }
                                    ajax = new XMLHttpRequest();
                                    if (ajax.upload) {
                                        /* cambiar url para editar y crear el archivo ajaxEditar.php */
                                        ajax.open("POST", "ajaxEditar.php", true);
                                        ajax.onreadystatechange = function () {
                                            if (ajax.readyState == 4) {
                                                if (ajax.status == 200) {
                                                    console.log(ajax.responseText);
                                                    var ojson = JSON.parse(ajax.responseText);
                                                    if (ojson.r == 0) {
                                                        mostrarMensaje("Editado correctamente");
                                                        ocultarForm();
                                                        limpiarForm();
                                                        cargarPagina(paginaActual);
                                                    } else if (ojson.r == -1) {
                                                        mostrarMensaje("Datos no validos");
                                                    } else if (ojson.r == -2) {
                                                        mostrarMensaje("No se ha podido editar");
                                                        ocultarForm();
                                                        limpiarForm();
                                                        cargarPagina(paginaActual);
                                                    }
                                                } else {
                                                    mostrarMensaje("Error al intentar insertar");
                                                }
                                            }
                                        };
                                        ajax.send(parametros);
                                    }
                                }
                            }
                        }
                    }
                }

            }
        };
        ajax.send();


        btCancelar.onclick = function () {
            ocultarForm();
            mostrarMensaje("Edicion cancelada, las fotos borradas no se pueden recuperar");
            limpiarForm();
            cargarPagina(paginaActual);
        };
    };

    var mostrarForm = function (titulo) {
        formTitle.textContent = titulo;
        login.setAttribute("class", "oculto");
        tabla.setAttribute("class", "oculto");
        modaldialog.setAttribute("class", "oculto");
        btDesconectar.setAttribute("class", "block");
        form.setAttribute("class", "block");


        nombre.addEventListener("blur", function () {
            if (nombre.value == "") {
                nombreValido.textContent = "Necesario un nombre";
            } else {
                var ajax = new Ajax();
                ajax.setUrl("ajaxComprobarNombre.php?nombre=" + nombre.value);
                ajax.setRespuesta(comprobarNombre);
                ajax.doPeticion();
            }
        });
        descripcion.addEventListener("change", function () {
            if (descripcion.value.length < 10) {
                descripcionValido.textContent = "Al menos 10 caracteres";
            } else {
                descripcionValido.textContent = "";
            }
        });
        precio.addEventListener("change", function () {
            if (precio.value.length < 1) {
                precioValido.textContent = "Necesario un precio";
            } else if (precio.value.length < 2) {
                if (precio.value == '.')
                    precioValido.textContent = "Precio no valido";
            } else {
                precioValido.textContent = "";
            }
        });

        btAceptar.onclick = function () {
            var errorVal = false;
            if (nombre.value === "") {
                nombreValido.textContent = "Necesario un nombre";
                errorVal = true;
            }
            if (!errorVal) {
                ajax = new XMLHttpRequest();
                ajax.open("GET", "ajaxComprobarNombre.php", true);
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        if (ajax.status == 200) {
                            console.log("nombre: " + ajax.responseText);
                            var ojson = JSON.parse(ajax.responseText);
                            if (ojson.r == 1) {
                                nombreValido.textContent = "Nombre ya en uso";
                                errorVal = true;
                            }
                        }
                    }
                };
                ajax.send(parametros);
            }


            var ajax = new Ajax();
            ajax.setUrl("ajaxComprobarNombre.php?nombre=" + nombre.value);
            ajax.setRespuesta(comprobarNombre);
            ajax.doPeticion();

            if (nombreValido.textContent !== "")
                errorVal = true;
            if (descripcion.value.length < 10) {
                errorVal = true;
                descripcionValido.textContent = "Al menos 10 caracteres";
            }
            if (precio.value.length < 1) {
                precioValido.textContent = "Necesario un precio";
                errorVal = true;
            } else if (precio.value.length < 2) {
                if (precio.value === '.') {
                    precioValido.textContent = "Precio no valido";
                    errorVal = true;
                }
            } else {
                precioValido.textContent = "";
            }
            if (errorVal) {
                mostrarMensaje("Valores no validos");
            } else {
                var imagen1 = document.getElementById("imagen1");
                var imagen2 = document.getElementById("imagen2");
                var imagen3 = document.getElementById("imagen3");
                var imagen4 = document.getElementById("imagen4");

                if (imagen1.value == "" && imagen2.value == "" &&
                        imagen3.value == "" && imagen4.value == "") {
                    mostrarMensaje("No ha seleccionado ninguna imagen");
                } else {
                    var ppal = document.getElementsByName("ppal");
                    var auxPpal = "";
                    for (var i = 0; i < ppal.length; i++) {
                        if (ppal[i].checked) {
                            auxPpal = ppal[i].value;
                        }
                    }
                    if (auxPpal === "") {
                        mostrarMensaje("No ha seleccionado ninguna imagen como principal");
                    } else {
                        var parametros = new FormData();
                        var imagenes = document.getElementsByClassName("img");
                        var imgValidas = new Array();
                        var newPpal = 0;
                        for (var i = 0; i < imagenes.length; i++) {
                            if (imagenes[i].value !== "") {
                                imgValidas.push(imagenes[i]);
                                if (i === auxPpal - 1)
                                    newPpal = imgValidas.length - 1;
                            }
                        }
                        parametros.append("nombre", nombre.value);
                        parametros.append("descripcion", descripcion.value);
                        parametros.append("precio", precio.value);
                        parametros.append("principal", newPpal);
                        for (i = 0; i < imgValidas.length; i++) {
                            archivoactual = imgValidas[i].files[0];
                            parametros.append('archivo[]', archivoactual, archivoactual.name);
                        }
                        ajax = new XMLHttpRequest();
                        if (ajax.upload) {
                            ajax.open("POST", "ajaxInsertar.php", true);
                            ajax.onreadystatechange = function () {
                                if (ajax.readyState == 4) {
                                    if (ajax.status == 200) {
                                        console.log(ajax.responseText);
                                        var ojson = JSON.parse(ajax.responseText);
                                        if (ojson.r == 0) {
                                            mostrarMensaje("Insertado correctamente");
                                            ocultarForm();
                                            limpiarForm();
                                            cargarPagina(0);
                                        } else if (ojson.r == -1) {
                                            mostrarMensaje("Datos no validos");
                                        } else if (ojson.r == -3) {
                                            mostrarMensaje("No se ha podido insertado");
                                        } else if (ojson.r == -2) {
                                            mostrarMensaje("Insertado, con error al subir archivos");
                                            ocultarForm();
                                            limpiarForm();
                                            cargarPagina(0);
                                        }
                                    } else {
                                        mostrarMensaje("Error al intentar insertar");
                                    }
                                }
                            };
                            ajax.send(parametros);
                        }
                    }
                }
            }
        };
        btCancelar.onclick = function () {
            ocultarForm();
            mostrarMensaje("Inserción cancelada");
            limpiarForm();
        };
    };

    function limpiarForm() {
        for (var i = 0; i < borrarImg.length; i++) {
            borrarInputFile(borrarImg[i]);
        }
        nombre.value = "";
        descripcion.value = "";
        precio.value = "";
        var miniatura = document.querySelectorAll(".miniatura");
        for (var i = 0; i < miniatura.length; i++) {
            miniatura[i].parentNode.removeChild(miniatura[i]);
        }
        for (var i = 0; i < ppal.length; i++) {
            ppal[i].checked = false;
            ppal[i].disabled = true;
        }
        var imgInput = document.querySelectorAll(".img");
        for (var i = 0; i < imgInput.length; i++) {
            imgInput[i].removeAttribute("hidden");
        }

        var reset = document.getElementsByClassName("reset");
        for (var i = 0; i < reset.length; i++) {
            reset[i].value = "Lmpiar";
        }
    }

    var ocultarForm = function () {
        formTitle.textContent = "";
        login.setAttribute("class", "oculto");
        modaldialog.setAttribute("class", "oculto");
        tabla.setAttribute("class", "flex");
        btDesconectar.setAttribute("class", "block");
        form.setAttribute("class", "oculto");
    }

    function mostrarConfirm(id, name) {
            MDtitle.textContent = "Borrar";
            MDcontent.textContent =
                    "¿quieres borrar el palto con nombre " + name + "?";
            var cancelado = "Borrado cancelado";

        modaldialog.setAttribute("class", "flex");
        login.setAttribute("class", "oculto");
        tabla.setAttribute("class", "oculto");
        form.setAttribute("class", "oculto");
        btMYes.onclick = function () {
            borrar(id);
            ocultarConfirm();
        };
        btMNo.onclick = function () {
            mostrarMensaje("Borrado cancelado");
            ocultarConfirm();
        };
    };

    var ocultarConfirm = function () {
        login.setAttribute("class", "oculto");
        modaldialog.setAttribute("class", "oculto");
        tabla.setAttribute("class", "flex");
    };

    var ocultarMensaje = function () {
        msn.textContent = "";
        msn.setAttribute("class", "oculto");
    };

    function mostrarMensaje(texto) {
        msn.textContent = texto;
        msn.setAttribute("class", "block");
        setTimeout(ocultarMensaje, 1500);
    }

    function mostrarTabla(texto) {
        while (puntoInsercion[0].hasChildNodes()) {
            puntoInsercion[0].removeChild(puntoInsercion[0].firstChild);
        }
        var ojson = JSON.parse(texto);
        for (var i = 0; i < ojson.platos.length; i++) {
            var tr = document.createElement("tr");
            for (prop in ojson.platos[i]) {
                if (prop != "foto") {
                    var td = document.createElement("td");
                    td.textContent = ojson.platos[i][prop];
                    tr.appendChild(td);
                } else {
                    var td = document.createElement("td");
                    var imgFoto = document.createElement("img");
                    imgFoto.setAttribute("class", "thumb");
                    imgFoto.setAttribute("src", "../fotos/" + ojson.platos[i].id + "/" + ojson.platos[i][prop]);
                    td.appendChild(imgFoto);
                    //td.textContent = ojson.platos[i][prop];
                    tr.appendChild(td);
                }
            }//Enlace borrar
            var td1 = document.createElement("td");
            td1.setAttribute("class", "link");
            var enlace1 = document.createElement("a");
            enlace1.setAttribute("class", "borrar");
            enlace1.setAttribute("href", "#");
            enlace1.setAttribute("data-id", ojson.platos[i]["id"]);
            enlace1.setAttribute("data-nombre", ojson.platos[i]["nombre"]);
            enlace1.appendChild(document.createTextNode('Borrar'));
            td1.appendChild(enlace1);
            tr.appendChild(td1);
            //Enlace editar
            var td2 = document.createElement("td");
            td2.setAttribute("class", "link");
            var enlace2 = document.createElement("a");
            enlace2.setAttribute("class", "editar");
            enlace2.setAttribute("href", "#");
            enlace2.setAttribute("data-id", ojson.platos[i]["id"]);
            enlace2.appendChild(document.createTextNode('Editar'));
            td2.appendChild(enlace2);
            tr.appendChild(td2);
            tabla.appendChild(tr);
            puntoInsercion[0].appendChild(tr);
        }
        paginacion(ojson.paginas);
        crearEventos();
    }

    /*
     * Paginación **********************************
     */

    function paginacion(pag) {

        var tr = document.createElement("tr");
        var td = document.createElement("td");
        td.setAttribute("colspan", "7");
        tr.setAttribute("class", "paginacion");

        var inicio = parseInt(pag["inicio"]);
        var enlaceInicio = document.createElement("a");
        enlaceInicio.setAttribute("href", "#");
        enlaceInicio.setAttribute("class", "pag");
        enlaceInicio.setAttribute("data-pagina", inicio);
        enlaceInicio.appendChild(document.createTextNode("<<"));
        td.appendChild(enlaceInicio);

        var anterior = parseInt(pag["anterior"]) >= 0 ? pag["anterior"] : "";
        //console.log("anterior: "+anterior);
        var enlaceAnterior = document.createElement("a");
        enlaceAnterior.setAttribute("href", "#");
        enlaceAnterior.setAttribute("class", "pag");
        enlaceAnterior.setAttribute("data-pagina", anterior);
        enlaceAnterior.appendChild(document.createTextNode("<"));
        td.appendChild(enlaceAnterior);

        var primero = parseInt(pag["primero"]) >= 0 ? pag["primero"] : "";
        if (primero !== "") {
            //console.log(primero);
            var enlacePrimero = document.createElement("a");
            enlacePrimero.setAttribute("href", "#");
            enlacePrimero.setAttribute("class", "pag");
            if (primero == paginaActual) {
                enlacePrimero.setAttribute("id", "actual");
            }
            enlacePrimero.setAttribute("data-pagina", primero);
            enlacePrimero.appendChild(document.createTextNode(parseInt(primero) + 1));
            td.appendChild(enlacePrimero);
        }

        var segundo = parseInt(pag["segundo"]) >= 0 ? pag["segundo"] : "";
        //console.log(segundo);
        if (segundo !== "") {
            var enlaceSegundo = document.createElement("a");
            enlaceSegundo.setAttribute("href", "#");
            enlaceSegundo.setAttribute("class", "pag");
            if (segundo == paginaActual) {
                enlaceSegundo.setAttribute("id", "actual");
            }
            enlaceSegundo.setAttribute("data-pagina", segundo);
            enlaceSegundo.appendChild(document.createTextNode(parseInt(segundo) + 1));
            td.appendChild(enlaceSegundo);
        }

        //var actual = pag["actual"] + 1;
        //console.log("actual: "+pag["actual"] );
        var actual = parseInt(pag["actual"]) >= 0 ? pag["actual"] : "";
        //console.log("actual: "+actual);   
        if (actual !== "") {
            var enlaceActual = document.createElement("a");
            enlaceActual.setAttribute("href", "#");
            enlaceActual.setAttribute("class", "pag");
            if (actual == paginaActual) {
                enlaceActual.setAttribute("id", "actual");
            }
            enlaceActual.setAttribute("data-pagina", actual);
            enlaceActual.appendChild(document.createTextNode(parseInt(actual) + 1));
            td.appendChild(enlaceActual);
        }

        var cuarto = parseInt(pag["cuarto"]) >= 0 ? pag["cuarto"] : "";
        //console.log(cuarto);
        if (cuarto !== "") {
            var enlaceCuarto = document.createElement("a");
            enlaceCuarto.setAttribute("href", "#");
            enlaceCuarto.setAttribute("class", "pag");
            if (cuarto == paginaActual) {
                enlaceCuarto.setAttribute("id", "actual");
            }
            enlaceCuarto.setAttribute("data-pagina", cuarto);
            enlaceCuarto.appendChild(document.createTextNode(parseInt(cuarto) + 1));
            td.appendChild(enlaceCuarto);
        }

        var quinto = parseInt(pag["quinto"]) >= 0 ? pag["quinto"] : "";
        //console.log(quinto);
        if (quinto !== "") {
            var enlaceQuinto = document.createElement("a");
            enlaceQuinto.setAttribute("href", "#");
            enlaceQuinto.setAttribute("class", "pag");
            if (quinto == paginaActual) {
                enlaceQuinto.setAttribute("id", "actual");
            }
            enlaceQuinto.setAttribute("data-pagina", quinto);
            enlaceQuinto.appendChild(document.createTextNode(parseInt(quinto) + 1));
            td.appendChild(enlaceQuinto);
        }

        var siguiente = parseInt(pag["siguiente"]) >= 0 ? pag["siguiente"] : "";
        //console.log(siguiente);    
        var enlaceSiguiente = document.createElement("a");
        enlaceSiguiente.setAttribute("href", "#");
        enlaceSiguiente.setAttribute("class", "pag");
        enlaceSiguiente.setAttribute("data-pagina", siguiente);
        enlaceSiguiente.appendChild(document.createTextNode(">"));
        td.appendChild(enlaceSiguiente);

        var ultimo = parseInt(pag["ultimo"]);
        //console.log(ultimo);
        var enlaceUltimo = document.createElement("a");
        enlaceUltimo.setAttribute("href", "#");
        enlaceUltimo.setAttribute("class", "pag");
        enlaceUltimo.setAttribute("data-pagina", ultimo);
        enlaceUltimo.appendChild(document.createTextNode(">>"));
        td.appendChild(enlaceUltimo);

        tr.appendChild(td);
        puntoInsercion[0].appendChild(tr);
    }

    /*
     * Eventos **********************************
     */

    function borrarInputFile(e) {
        var id = e.getAttribute("data-id");
        var inputOriginal = document.getElementById(id);
        if (inputOriginal.value != "") {
            var nuevo = inputOriginal.cloneNode(false);
            nuevo.value = "";
            var radio = inputOriginal.parentNode.nextElementSibling.lastElementChild;
            radio.disabled = true;
            radio.checked = false;
            inputOriginal.parentNode.insertBefore(nuevo, inputOriginal);
            inputOriginal.parentNode.removeChild(inputOriginal);
            precargar(nuevo);
            nuevo.addEventListener("change", function (evt) {
                precargar(evt.target);
                evt.target.parentNode.nextElementSibling.lastElementChild.disabled = false;
            });
        }
    }

    var borrarImg = document.getElementsByClassName("reset");
    for (var i = 0; i < borrarImg.length; i++) {
        borrarImg[i].addEventListener("click", function (e) {
            borrarInputFile(e.target);
        });
    }

    function crearEventos() {
        var enlaces = document.getElementsByClassName("pag");
        for (var i = 0; i < enlaces.length; i++) {
            agregarEvento(enlaces[i]);
        }

        var enlacesEditar = document.getElementsByClassName("editar");
        for (var i = 0; i < enlacesEditar.length; i++) {
            agregarEventoEditar(enlacesEditar[i]);
        }

        var enlacesBorrar = document.getElementsByClassName("borrar");
        for (var i = 0; i < enlacesBorrar.length; i++) {
            agregarEventoBorrar(enlacesBorrar[i]);
        }
    }

    function agregarEvento(elemento) {
        var datapag = elemento.getAttribute("data-pagina");
        elemento.addEventListener("click", function (e) {
            if (datapag !== paginaActual) {
                cargarPagina(datapag);
            }
        });
    }

    function agregarEventoBorrar(elemento) {
        var id = elemento.getAttribute("data-id");
        var name = elemento.getAttribute("data-nombre");
        elemento.addEventListener("click", function (e) {
            mostrarConfirm(id, name);
            /*mostrarConfirm("borrar",
             "¿quieres borrar el palto con nombre " + name + "?", 
             borrar(id), "Borrado cancelado");*/
        });
    }

    function agregarEventoEditar(elemento) {
        var id = elemento.getAttribute("data-id");
        var name = elemento.getAttribute("data-nombre");
        elemento.addEventListener("click", function (e) {
            mostrarFormEditar(id);
        });
    }

    /*
     * Gestion del resultado de ajax **********************************
     */

    function loguear(txt) {
        var ojson = JSON.parse(txt);
        if (ojson.r == 1) {
            mostrarMensaje("Usuario conectado");
            conectarUsuario();
            cargarPagina(0);
        } else {
            mostrarMensaje("Usuario o login incorrectos");
        }
    }

    function desloguear() {
        desconectarUsuario();
        mostrarMensaje("Usuario desconectado");
    }

    function conectar(txt) {
        var ojson = JSON.parse(txt);
        if (ojson.r == 1) {
            conectarUsuario();
            cargarPagina(0);
        }
    }

    function borrarResul(txt) {
        var ojson = JSON.parse(txt);
        if (ojson.r == 1) {
            if (paginaActual > ojson.paginas.ultimo) {
                paginaActual = ojson.paginas.ultimo;
            }
            mostrarTabla(txt);
            mostrarMensaje("Plato borrado");
        } else {
            mostrarMensaje("No se ha podido borrar");
        }
    }

    /*
     * Llamadas ajax **********************************
     */

    function cargarPagina(pagina) {
        paginaActual = pagina;
        var ajax = new Ajax();
        ajax.setUrl("ajaxSelect.php?pagina=" + pagina);
        ajax.setRespuesta(mostrarTabla);
        ajax.doPeticion();
    }

    function borrar(id) {
        var ajax = new Ajax();
        ajax.setUrl("ajaxDelete.php?id=" + id + "&pagina=" + paginaActual);
        ajax.setRespuesta(borrarResul);
        ajax.doPeticion();
    }

    function conexion() {
        var ajax = new Ajax();
        ajax.setUrl("ajaxConectado.php");
        ajax.setRespuesta(conectar);
        ajax.doPeticion();
    }

    function logearDatos() {
        var nombreUser = document.getElementById("nombreUser").value;
        var clave = document.getElementById("clave").value;

        if (nombreUser !== "" && clave !== "") {
            var ajax = new Ajax();
            ajax.setUrl("ajaxGet.php?login=" + nombreUser + "&clave=" + clave);
            ajax.setRespuesta(loguear);
            ajax.setRespuestaError(mostrarMensaje("No se puede intentar conectar"));
            ajax.doPeticion();
        }
        document.getElementById("nombreUser").value = "";
        document.getElementById("clave").value = "";
    }

    /*
     * Inicio **********************************
     */

    conexion(); // ve si esta conectado

    var login = document.getElementById("login");
    var tabla = document.getElementById("tabla");

    var form = document.getElementById("formulario");
    var formTitle = document.getElementById("formTitle");
    var nombre = document.getElementById("nombre");
    var nombreValido = document.getElementById("nombreValido");
    var descripcion = document.getElementById("descripcion");
    var descripcionValido = document.getElementById("descripcionValido");
    var precio = document.getElementById("precio");
    precio.addEventListener("keypress", filtroNumero, false);
    var precioValido = document.getElementById("precioValido");


    var btDesconectar = document.getElementById("btDesconectar");
    var btLogin = document.getElementById("btLogin");
    var msn = document.getElementById("mensaje");

    var btInsertar = document.getElementById("insertar");
    var btAceptar = document.getElementById("btAceptar");
    var btCancelar = document.getElementById("btCancelar");

    var modaldialog = document.getElementById("modal_dialog");
    var MDtitle = document.getElementById("MDtitle");
    var MDcontent = document.getElementById("MDcontent");
    var btMYes = document.getElementById("btMYes");
    var btMNo = document.getElementById("btMNo");

    var puntoInsercion = tabla.getElementsByTagName("tbody");

    btInsertar.addEventListener("click", function () {
        mostrarForm("Insertar");
    });

    btDesconectar.addEventListener("click", function () {
        var ajax = new Ajax();
        ajax.setUrl("ajaxLogout.php");
        ajax.setRespuesta(desloguear);
        ajax.doPeticion();
    });

    var claveInput = document.getElementById("clave");
    claveInput.addEventListener("keyup", function (e) {
        if (e.key === "Enter") {
            logearDatos();
        }
    });

    btLogin.addEventListener("click", function () {
        logearDatos()
    });

}

window.addEventListener("load", inicio);

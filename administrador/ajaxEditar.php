<?php

require '../require/comun.php';
header('Content-Type: application/json');
if ($sesion->getUsuario() !== null) {
    $id = Leer::post("id");
    $nombre = Leer::post("nombre");
    $descripcion = Leer::post("descripcion");
    $precio = Leer::post("precio");
    $imgExistentes = Leer::post("imgExistentes");
    $principal = Leer::post("principal");

    if (!Validar::isAltaPlato($nombre, $descripcion, $precio)) {
        echo '{"r": -1}'; // datos no validos
        exit();
    }
    $bd = new BaseDatos();
    $modelo = new ModeloPlato($bd);
    $objeto = $modelo->get($id);
    $objeto->setNombre($nombre);
    $objeto->setDescripcion($descripcion);
    $objeto->setPrecio($precio);
    //$objeto->setFoto($principal);

    $ruta = "../fotos/" . $id;
    if (!file_exists($ruta)) {
        mkdir($ruta, Configuracion::PERMISOS);
    }
    /* Renombro los archivos */
    $directorio = opendir($ruta);
    $imagen = array();
    while ($archivo = readdir($directorio)) { //obtenemos un archivo y luego otro sucesivamente
        if ($archivo != ".." && $archivo != ".") {
            $imagen[] = $ruta . "/" . $archivo;
        }
    }
    sort($imagen, SORT_STRING);
    for ($i = 0; $i < sizeof($imagen); $i++) {
        $pos = strrpos($imagen[$i], ".", 3);
        $ext = substr($imagen[$i], $pos, 4);
        rename($imagen[$i], $ruta."/foto".($i+1).$ext);
    }


    if ($imgExistentes < 4) {
        if (isset($_FILES["archivo"])) {
            $errores = 0;
            //ver el tipo mime, el tamaño, y que reemplaze
            if ($_FILES["archivo"]["error"] > 0) {
                foreach ($_FILES["archivo"]["error"] as $indice => $valor) {
                    if ($valor == UPLOAD_ERR_OK) {
                        $tipo = explode("/", $_FILES["archivo"]["type"][$indice]);
                        $tamano = $_FILES["archivo"]["size"][$indice];
                        if ($tipo[0] == "image" && $tamano < 2097152) {   //0.5 megas "2097152"
                            $tmp = $_FILES["archivo"]["tmp_name"][$indice];
                            $name = $_FILES["archivo"]["name"][$indice];
                            $pos = strrpos($name, ".");
                            $ext = substr($name, $pos, 4);
                            move_uploaded_file($tmp, "../fotos/" . $id . "/foto" .
                                    ($indice + $imgExistentes + 1 ) . $ext);
                        } else {
                            $errores ++;
                        }
                    } else {
                        $errores ++;
                    }
                }
            }
        }
    }
    //cambiar la foto ppal
    $ruta = "../fotos/" . $id;
    $directorio = opendir($ruta);
    $imagen = array();
    while ($archivo = readdir($directorio)) { //obtenemos un archivo y luego otro sucesivamente
        if ($archivo != ".." && $archivo != ".") {
            $imagen[] = $ruta . "/" . $archivo;
        }
    }
    sort($imagen, SORT_STRING);
    for ($i = 0; $i < sizeof($imagen); $i++) {
        $pos = strrpos($imagen[$i], ".", 3);
        $ext = substr($imagen[$i], $pos, 4);
        $num = substr($imagen[$i], $pos - 1, 1);
        if ($principal === $num || $i == 0) {
            $fotoPpal = "foto" . ($num) . $ext;
            //var_dump($fotoPpal);
            $objeto->setFoto($fotoPpal);
        }
    }

    $r = $modelo->edit($objeto);
    if ($r > -1) {
        echo '{"r": 0}';
    } else {
        echo '{"r": -2}';
    }
}


<?php

require '../require/comun.php';
header('Content-Type: application/json');
if ($sesion->getUsuario() !== null) {
    $nombre = Leer::post("nombre");
    $descripcion = Leer::post("descripcion");
    $precio = Leer::post("precio");

    if (!Validar::isAltaPlato($nombre, $descripcion, $precio)) {
        echo '{"r": -1}'; // datos no validos
        exit();
    }

    $bd = new BaseDatos();
    $modelo = new ModeloPlato($bd);
    $r = $modelo->add(new Plato(null, $nombre, $descripcion, $precio));

    if ($r != -1) {
        $ruta = "../fotos/" . $r;
        if (!file_exists($ruta)) {
            mkdir($ruta, Configuracion::PERMISOS);
        }
        $principal = Leer::post("principal");

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
                            move_uploaded_file($tmp, "../fotos/" . $r . "/foto" . ($indice + 1) . $ext);
                            if ($principal == $indice) {
                                $fotoPpal = "foto" . ($indice + 1) . $ext;
                                $objeto = $modelo->get($r);
                                $objeto->setFoto($fotoPpal);
                                $modelo->edit($objeto);
                            }
                        } else {
                            $errores ++;
                        }
                    } else {
                        $errores ++;
                    }
                }
            }
            if ($errores == 0) {
                echo '{"r": 0}'; // se ha insertado y subido bien
                exit();
            } else {
                echo '{"r": -2}' . $errores; // se ha insertado y no se han subido bien
                exit();
            }
        } else {
            echo '{"r": -3}'; // no se ha insertado
            exit();
        }
    }
}


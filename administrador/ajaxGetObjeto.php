<?php

require '../require/comun.php';
header('Content-Type: application/json');
if ($sesion->getUsuario() !== null) {
    $bd = new BaseDatos();
    $modelo = new ModeloPlato($bd);
    $id = Leer::get("id");

    $ruta = "../fotos/" . $id;
    $imagenes = array();
    if (file_exists($ruta)) {
        $directorio = opendir($ruta);
        while ($archivo = readdir($directorio)) { //obtenemos un archivo y luego otro sucesivamente
            if ($archivo != ".." && $archivo != ".") {
                $imagenes[] = $ruta . "/" . $archivo;
            }
        }
        sort($imagenes, SORT_STRING);
    }
    $resul = substr($modelo->getJSON($id), 0, -1);
    $resul .= ',"fotos": {';
    for ($i = 0; $i < sizeof($imagenes); $i++) {
        if ($i === 0) {
            $resul .= '"foto' . ($i + 1) . '": "' . $imagenes[$i] . '"';
        } else {
            $resul .= ',"foto' . ($i + 1) . '": "' . $imagenes[$i] . '"';
        }
    }

    echo $resul . '}}';
}
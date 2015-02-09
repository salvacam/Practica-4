<?php

require '../require/comun.php';
header('Content-Type: application/json');
if ($sesion->getUsuario() !== null) {
    $foto = Leer::get("foto");
    if (file_exists($ruta)) {
        unlink($foto);
        echo '{"r":1}';
    } else {
        echo '{"r":0}';
    }
}    
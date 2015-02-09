<?php

require '../require/comun.php';
header('Content-Type: text/plain');
$nombre = Leer::get("nombre");
$bd = new BaseDatos();
$modelo = new ModeloPlato($bd);
$r = $modelo->getNombre($nombre);
if ($r->getNombre() !== NULL) {
    echo '{"r": 1}';
    exit();
} else {
    echo '{"r": 0}';
}

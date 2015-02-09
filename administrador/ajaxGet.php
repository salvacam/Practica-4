<?php

require '../require/comun.php';
header('Content-Type: application/json');
$bd = new BaseDatos();
$modelo = new ModeloUser($bd);
$login = Leer::get("login");
$clave = Leer::get("clave");
$aux = $modelo->get($login,$clave);
$bd->closeConexion();
if ($aux->getNombre() === null) {
    echo '{"r": 0}';
    exit();
} else {
    $sesion->setUsuario($aux);
    echo '{"r": 1}';
}
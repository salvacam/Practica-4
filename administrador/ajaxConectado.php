<?php
require '../require/comun.php';
header('Content-Type: application/json');
if($sesion->getUsuario() !== null){
    echo '{"r": 1}';
    exit();
} else {
    echo '{"r": 0}';
}

<?php

require '../require/comun.php';
header('Content-Type: application/json');
$sesion->cerrar();
echo '{"r": 1}';

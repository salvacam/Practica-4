<?php

require '../require/comun.php';
header('Content-Type: application/json');
if ($sesion->getUsuario() !== null) {
    $bd = new BaseDatos();
    $modelo = new ModeloPlato($bd);
    $pagina = 0;
    if (Leer::get("pagina") != null) {
        $pagina = Leer::get("pagina");
    }
    //$enlaces = Paginacion::getEnlacesPaginacionJSON($pagina, $modelo->count(), Configuracion::RPP);
    $total = $modelo->count();
    $enlaces = Paginacion::getEnlacesPaginacionJSON($pagina, $total[0], 2);
    echo '{"paginas":' . json_encode($enlaces) . ',"platos":' . $modelo->getListJSON($pagina, 2, "1=1", array(), "id DESC") . '}';
    //echo '{"platos":'.$modelo->getListJSON($pagina, Configuracion::RPP) . "}"; //sin paginacion
    $bd->closeConexion();
}


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
    $id = Leer::get("id");
    $r = $modelo->deletePorId($id);
    if ($r == 1) {
        $total = $modelo->count();
        $paginas = ceil($total[0] / 2) - 1;
        if ($pagina > $paginas) {
            $pagina = $paginas;
        }
        //borrar carpeta
        $ruta = "../fotos/" . $id;
        if(file_exists($ruta)){
            foreach (scandir($ruta) as $valor) {//readdir  scandir
                if ($valor == '.' || $valor == '..')
                    continue;
                unlink($ruta . DIRECTORY_SEPARATOR . $valor);
            }
            rmdir($ruta);        
        }
        $enlaces = Paginacion::getEnlacesPaginacionJSON($pagina, $total[0], 2);
        echo '{"r": 1,"paginas":' . json_encode($enlaces) . ',"platos":' . $modelo->getListJSON($pagina, 2) . '}';
        $bd->closeConexion();
        exit();
    }
    $bd->closeConexion();
    echo '{"r":0}';
}
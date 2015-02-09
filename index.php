<?php
require './require/comun.php';
$bd = new BaseDatos();

$dir = ".";

$pagina = 0;
if (Leer::get("pagina") != null) {
    $pagina = Leer::get("pagina");
}
$modelo = new ModeloPlato($bd);
$filas = $modelo->getList($pagina, 2, "1=1", array(), "id DESC");
$total = $modelo->count();
$enlaces = Util::getEnlacesPaginacion($pagina, $total[0], 2);
?>
<!doctype html>
<html>

    <?php include "./include/head.php"; ?>

    <section id="contenido">

        <?php
        foreach ($filas as $indice => $objeto) {
            $ruta = "./fotos/" . $objeto->getId() . "/" . $objeto->getFoto();
            if ($objeto->getFoto() != NULL && file_exists($ruta)) {
                $ruta = "./fotos/" . $objeto->getId() . "/" . $objeto->getFoto();
            } else {
                $ruta = "./fotos/defecto.jpg";
            }
            ?>

            <a href="./platos/index.php?id=<?php echo $objeto->getId(); ?>" class ="enlacePlato">
                <span class = "tituloPlato"><?php echo $objeto->getNombre(); ?></span>
                <div class = "textoPlato">
                    <p><?php echo $objeto->getDescripcion(); ?></p>
                </div>
                <img class = "imagenPlato" alt = "imagen plato" src ="<?php echo $ruta; ?>">
            </a>
            <?php
        }
        ?>
        <br/>
        <div id="cajaPaginacion"> 
            <?php echo $enlaces["inicio"]; ?>
            <?php echo $enlaces["anterior"]; ?>
            <?php echo $enlaces["primero"]; ?>                            
            <?php echo $enlaces["segundo"]; ?>
            <?php echo $enlaces["actual"]; ?>
            <?php echo $enlaces["cuarto"]; ?>
            <?php echo $enlaces["quinto"]; ?>
            <?php echo $enlaces["siguiente"]; ?>
            <?php echo $enlaces["ultimo"]; ?>
        </div>
    </section>


    <?php
    $bd->closeConexion();
    include "./include/footer.php";
    ?>

</div>
</body>

</html>

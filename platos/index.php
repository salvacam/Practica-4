<?php
require '../require/comun.php';
$id = Leer::get("id");
if ($id === NULL) {
    header("Location: ../index.php");
    exit();
}
$bd = new BaseDatos();
$modelo = new ModeloPlato($bd);
$dir = "..";

$objeto = $modelo->get($id);
if ($objeto === NULL || $objeto->getNombre() === NULL) {
    header("Location: ../index.php");
    exit();
}

$ruta = "../fotos/" . $objeto->getId();
if ($objeto->getFoto() !== "") {
    $ruta = "../fotos/" . $objeto->getId() . "/" . $objeto->getFoto();
    if (file_exists($ruta)) {
        $ppal = "../fotos/" . $objeto->getId() . "/" . $objeto->getFoto();
    }
}
$ruta = "../fotos/" . $objeto->getId();
$imagen = array();
if (file_exists($ruta)) {
    $directorio = opendir($ruta);
    while ($archivo = readdir($directorio)) { //obtenemos un archivo y luego otro sucesivamente
        if ($archivo != ".." && $archivo != ".") {
            if ($archivo != $objeto->getFoto())
                $imagen[] = $ruta . "/" . $archivo;
        }
    }
    sort($imagen, SORT_STRING);
}
?>
<!doctype html>
<html>

    <?php include "../include/head.php"; ?>

    <a href="../" id="volver">Volver</a>

    <?php
    $ruta = "../fotos/" . $objeto->getId() . "/" . $objeto->getFoto();
    ;
    if ($objeto->getFoto() !== "" && file_exists($ruta)) {
        ?>
        <section class="parallax" style="background-image:url('<?php echo $ppal; ?>')">    
            <article class="fuera">Nombre del plato<br/><br/><span class="mayor"><?php echo $objeto->getNombre(); ?></span></article>
        </section>
        <?php
        if (sizeof($imagen) == 0) {
            ?>
            <article class="fuera">Descripcion del plato<br/><br/><span class="mayor"><?php echo $objeto->getDescripcion(); ?></span></article>
            <article class="fuera">Precio del plato<br/><br/><span class="mayor"><?php echo $objeto->getPrecio(); ?> &#8364;</span></article>
            <?php
        } else {
            for ($i = 0; $i < sizeof($imagen); $i++) {
                ?>
                <section class="parallax" style="background-image:url('<?php echo $imagen[$i] ?>')">                
                    <?php
                    if ($i == 0) {
                        if (sizeof($imagen) == 1) {
                            ?>
                            <article class="fuera">Descripcion del plato<br/><br/><span class="mayor"><?php echo $objeto->getDescripcion(); ?></span></article>
                        </section>
                        <article class="fuera">Precio del plato<br/><br/><span class="mayor"><?php echo $objeto->getPrecio(); ?> &#8364;</span></article>
                        <?php
                    } else {
                        ?>
                        <article>Descripcion del plato<br/><br/><span class="mayor"><?php echo $objeto->getDescripcion(); ?></span></article>
                    </section>
                    <?php
                }
            }
            if ($i == 1) {
                ?>
                <article>Precio del plato<br/><br/><span class="mayor"><?php echo $objeto->getPrecio(); ?> &#8364;</span></article>
                </section>
                <?php
            }
            if ($i > 1) {
                ?>
                </section>
                <?php
            }
        }
    }
} else {
    if (sizeof($imagen) > 0) {
        for ($i = 0; $i < sizeof($imagen); $i++) {
            ?>
            <section class="parallax" style="background-image:url('<?php echo $imagen[$i] ?>')">    
                <?php
                if ($i == 0) {
                    if (sizeof($imagen) == 1) {
                        ?>
                        <article class="fuera">Nombre del plato<br/><br/><span class="mayor"><?php echo $objeto->getNombre(); ?></span></article>
                    </section>
                    <article class="fuera">Descripcion del plato<br/><br/><span class="mayor"><?php echo $objeto->getDescripcion(); ?></span></article>
                    <article class="fuera">Precio del plato<br/><br/><span class="mayor"><?php echo $objeto->getPrecio(); ?> &#8364;</span></article>
                    <?php
                } else {
                    ?>
                    <article>Nombre del plato<br/><br/><span class="mayor"><?php echo $objeto->getNombre(); ?></span></article>
                    </section>
                    <?php
                }
            }
            if ($i == 1) {
                if (sizeof($imagen) == 2) {
                    ?>
                    <article class="fuera">Descripcion del plato<br/><br/><span class="mayor"><?php echo $objeto->getDescripcion(); ?></span></article>
                    </section>
                    <article class="fuera">Precio del plato<br/><br/><span class="mayor"><?php echo $objeto->getPrecio(); ?> &#8364;</span></article>
                    <?php
                } else {
                    ?>
                    <article>Descripcion del plato<br/><br/><span class="mayor"><?php echo $objeto->getDescripcion(); ?></span></article>
                    </section>
                    <?php
                }
            }
            if ($i == 2) {
                ?>
                <article>Precio del plato<br/><br/><span class="mayor"><?php echo $objeto->getPrecio(); ?> &#8364;</span></article>
                </section>
                <?php
            }
            if ($i > 2) {
                ?>
                </section>
                <?php
            }
        }
    } else {
        ?>
        <article class="fuera">Nombre del plato<br/><br/><?php echo $objeto->getNombre(); ?></article>
        <article class="fuera">Descripcion del plato<br/><br/><?php echo $objeto->getDescripcion(); ?></article>
        <article class="fuera">Precio del plato<br/><br/><?php echo $objeto->getPrecio(); ?></article>

        <?php
    }
}
$bd->closeConexion();
include "../include/footer.php";
?>

</div>
</body>

</html>

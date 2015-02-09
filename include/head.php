<head>
    <title>Yantra Restaurante Indio</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="<?php echo $dir; ?>/font/stylesheet.css">
    <link rel="stylesheet" href="<?php echo $dir; ?>/css/rest.css" />
    <?php
    if ($dir == "..") {
        ?>
        <script src="<?php echo $dir; ?>/js/plato.js"></script>
        <?php
    } else {
		?>
        <script src="<?php echo $dir; ?>/js/main.js"></script>
    <?php } ?>
    <link rel="shortcut icon" href="<?php echo $dir; ?>/img/india-icon.png">
</head>

<body>
    <div id="contenedor">
        <img src="<?php echo $dir; ?>/img/down.png" alt=" " id="flechaAbajo" />
        <?php
        if ($dir == "..") {
            ?>
            <nav id="menuOculto" hidden><a href="../" id="volverO">Volver</a></nav>
            <?php
        }
        ?>
        <header>
            <nav>
                <a href="#">inicio</a>
                <a href="#" class="activo"><span>carta</span></a>
                <div id="cajaLogo">
                    <img src="<?php echo $dir; ?>/img/logo.png" alt="logo">
                    <span>restaurante indio</span>
                </div>
                <a href="#">contacto</a>
                <a href="#">reserva</a>
            </nav>
        </header>
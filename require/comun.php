<?php

function autoload($clase) {
    if (file_exists('clases/' . $clase . '.php')) {
        require 'clases/' . $clase . '.php';
    } else {
        require '../clases/' . $clase . '.php';
    }
}
spl_autoload_register('autoload');

$sesion = SesionSingleton::getSesion();


//Ver errores
error_reporting(E_ALL);
ini_set("display_errors", 1);
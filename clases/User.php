<?php
class User {
    //orden de las variables en el orden de la tabla
    private $nombre;	
    private $clave;
    
    //orden igual que las variables, parametros por defecto null
    function __construct($nombre = null, $clave = null) {
        $this->nombre = $nombre;
        $this->clave = $clave;
    }
    
    //array de datos y posicion inicial
    function set($datos, $inicio=0){
        $this->nombre= $datos[0 + $inicio];
        $this->clave = $datos[1 + $inicio];  
    }
    function getNombre() {
        return $this->nombre;
    }

    function getClave() {
        return $this->clave;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setClave($clave) {
        $this->clave = $clave;
    }


}

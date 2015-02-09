<?php

class Plato {

    //orden de las variables en el orden de la tabla
    private $id;
    private $nombre;
    private $descripcion;
    private $precio;
    private $foto;

    //orden igual que las variables, parametros por defecto null
    function __construct($id = null, $nombre = null, $descripcion = null, $precio = null, $foto = null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->foto = $foto;
    }

    //array de datos y posicion inicial
    function set($datos, $inicio = 0) {
        $this->id = $datos[0 + $inicio];
        $this->nombre = $datos[1 + $inicio];
        $this->descripcion = $datos[2 + $inicio];
        $this->precio = $datos[3 + $inicio];
        $this->foto = $datos[4 + $inicio];
    }

    function getId() {
        return $this->id;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getPrecio() {
        return $this->precio;
    }

    function getFoto() {
        return $this->foto;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setPrecio($precio) {
        $this->precio = $precio;
    }

    function setFoto($foto) {
        $this->foto = $foto;
    }

    public function getJSON() {
        $prop = get_object_vars($this);
        $resp = '{ ';
        foreach ($prop as $key => $value) {
            $resp.='"' . $key . '":' . json_encode(htmlspecialchars_decode($value)) . ',';
        }
        $resp = substr($resp, 0, -1) . "}";
        return $resp;
    }

}

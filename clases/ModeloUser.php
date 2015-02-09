<?php

class ModeloUser {

    //Implementamos los métodos que necesitamos para trabajar con la persona
    private $bd;
    private $tabla = "rest_user";

    function __construct(BaseDatos $bd) {
        $this->bd = $bd;
    }
/*
    function add(Root $objeto) {
        $sql = "insert into $this->tabla values (:nombre, :clave);";
        $parametros["nombre"] = $objeto->getNombre();
        $parametros["clave"] = $objeto->getClave();
        $r = $this->bd->setConsulta($sql, $parametros);
        if (!$r) {
            return -1;
        }
        return $this->bd->getAutonumerico(); //return 0 si no fuera autonumerico        
    }
*/
    function get($nombre, $clave) {
        $sql = "SELECT * FROM $this->tabla WHERE nombre=:nombre and clave=:clave;";
        $parametros["nombre"] = $nombre;
        $parametros["clave"] = sha1($clave);
        $r = $this->bd->setConsulta($sql, $parametros);
        if ($r) {
            $user = new User();
            $user->set($this->bd->getFila());
            return $user;
        }
        return null;
    }

    
}

<?php

/**
 * Class Leer
 *
 * @version 0.9
 * @author izv
 * @license http://...
 * @copyright izv by 2daw
 * Esta clase dispone de métodos estáticos que se utilizan para
 * la lectura de parametros de entrada a través de get y post.
 * 
 */
class Leer {

    /**
     * Trata de leer el parámetro de entrada que se pasa como argumento.
     * @access public
     * @param string $param Cadena con el nombre del parámetro
     * @return string|array|null Devuelve una cadena con el valor del parámetro, null si
     * el parámetro no se ha pasado o un array si el parametro es múltiple.
     */
    public static function get($param, $filtrar = true) {
        if (isset($_GET[$param])) {
            $v = $_GET[$param];
            if (is_array($v)) {
                return Leer::leerArray($v);
            } else {
                if ($filtrar) {
                    return Leer::limpiar($v);
                } else {
                    return $v;
                }
            }
            /*
              if ($_GET[$param] === "") {
              return "";
              } else {
              return $_GET[$param];
              }
             */
            /*
              if (empty($_GET[$param])) {
              return "";
              } else {
              return $_GET[$param];
              }
             */
        } else {
            return null;
        }
    }

    public static function isArray($param) {
        if (isset($_GET[$param])) {
            return(is_array($_GET[$param]));
        } elseif (isset($_POST[$param])) {
            return(is_array($_POST[$param]));
        }
        return null;
        /* devuelve 3 valores posibles 
         *      TRUE si es un array, 
         *      FALSE si no es un array
         *      NULL si no ha llegado
         */
    }

    public static function isArrayV2($param) {
        return Leer::request($param);
        /*
         * Devolveria TRUE o FALSE
         */
    }

    private static function leerArray($param, $filtrar = true) {
        $array = array();
        foreach ($param as $key => $value) {
            if ($filtrar) {
                $array[] = Leer::limpiar($value);
            } else {
                $array[] = $value;
            }
        }
        return $array;
    }

    public static function post($param, $filtrar = true) {
        if (isset($_POST[$param])) {
            $v = $_POST[$param];
            if (is_array($v)) {
                return Leer::leerArray($v);
            } else {
                if ($filtrar) {
                    return Leer::limpiar($v);
                } else {
                    return $v;
                }
            }
        } else {
            return null;
        }
    }

    public static function request($param, $filtrar = true) {
        $v = Leer::get($param, $filtrar);
        if ($v == null) {
            $v = Leer::post($param, $filtrar);
        }
        return $v;
    }

    private static function limpiar($param) {
        return trim(htmlspecialchars($param));
    }

}

<?php

class SesionSingleton {

    private static $instancia;

    private function __construct($nombre = "") {
        if ($nombre !== "") {
            session_name($nombre);
        }
        session_start();
    }

    public static function getSesion($nombre = "") {
        if (is_null(self::$instancia)) {
            self::$instancia = new self($nombre);
        }
        return self::$instancia;
    }

    function cerrar() {
        session_unset();
        session_destroy();
    }

    function set($variable, $valor) {
        $_SESSION[$variable] = $valor;
    }

    function delete($variable = "") {
        if ($variable === "") {
            unset($_SESSION);
        } else {
            unset($_SESSION[$variable]);
        }
    }

    function get($variable) {
        if (isset($_SESSION[$variable]))
            return $_SESSION[$variable];
        return null;
    }

    function getNombres() {
        $array = array();
        foreach ($_SESSION as $key => $value) {
            $array[] = $key;
        }
        return $array;
    }

    function isSesion() {
        return count($_SESSION) > 0;
    }

    function isAutentificado() {
        if (Entorno::getNavegadorCliente() != $this->get("__navegador")) {
            $this->cerrar();
            return false;
        }
        if (Entorno::getIpCliente() != $this->get("__ip")) {
            $this->cerrar();
            return false;
        }
        if (time() - $this->get("__timeout") > Configuracion::TIMEOUT) {
            $this->cerrar();
            return false;
        }
        $this->set("__timeout", time());        
        if ($this->getUsuario() != null) {
            return $this->getUsuario();
        }
        if ($this->getAdmin() != null) {
            return $this->getAdmin();
        }
        if ($this->getRoot() != null) {
            return $this->getRoot();
        }
        return false;
        //return isset($_SESSION["__usuario"]);
    }
/*
    function isRoot() {
        $usuario = $this->getUsuario();
        if (!$this->isAutentificado() || !$usuario->getIsroot() || $usuario->getRol() == "usuario") {
            return false;
        }
        return true;
    }

    function isAdmin() {
        $usuario = $this->getUsuario();
        if (!$this->isAutentificado() || !$usuario->getIsroot()) {
            return false;
        }
        return true;
    }
*/
    function autentificado($destino = "index.php") {
        $usuario = $this->getUsuario();
        if (!$this->isAutentificado() || $usuario->getIsactivo() < 1) {
            $this->cerrar();
            $this->redirigir($destino);
        }
    }

    function admin($destino = "index.php") {
        $usuario = $this->getUsuario();
        if (!$this->isAdmin() || $usuario->getIsactivo() < 1) {
            $this->cerrar();            
            $this->redirigir($destino);
        }
    }

    function root($destino = "index.php") {
        $usuario = $this->getUsuario();
        if (!$this->isRoot() || $usuario->getIsactivo() < 1) {
            $this->cerrar();
            $this->redirigir($destino);
        }
    }

    /*
      function administrar($destino = "index.php") {
      $usuario = $this->getUsuario();
      if (!$this->isAutentificado() || (!$usuario->getIsroot() || !$usuario->getAdmin())) {
      $this->redirigir($destino);
      }
      }

      function administrarRoot($destino = "index.php") {
      $usuario = $this->getUsuario();
      if (!$this->isAutentificado() || !$usuario->getIsroot()) {
      $this->redirigir($destino);
      }
      } */

    function setUsuario($usuario) {
        if ($usuario instanceof User) {
            $this->set("__usuario", $usuario);
            $this->set("__ip", Entorno::getIpCliente());
            $this->set("__navegador", Entorno::getNavegadorCliente());
            $this->set("__timeout", time());
        }
    }

    function setAdmin($admin) {
        if ($admin instanceof Admin) {
            $this->set("__admin", $admin);
            $this->set("__ip", Entorno::getIpCliente());
            $this->set("__navegador", Entorno::getNavegadorCliente());
            $this->set("__timeout", time());
        }
    }

    function setRoot($root) {
        if ($root instanceof Root) {
            $this->set("__root", $root);
            $this->set("__ip", Entorno::getIpCliente());
            $this->set("__navegador", Entorno::getNavegadorCliente());
            $this->set("__timeout", time());
        }
    }

    function getUsuario() {
        if ($this->get("__usuario") != null)
            return $this->get("__usuario");
        return null;
    }

    function getUsuarioArray() {
        //var_dump($this->getUsuario());
        foreach ($this->getUsuario() as $indice => $valor) {
            echo "1";
            echo $indice. " - ".$valor . "<br/>";
        }
    }

    function getAdmin() {
        if ($this->get("__admin") != null)
            return $this->get("__admin");
        return null;
    }

    function getRoot() {
        if ($this->get("__root") != null)
            return $this->get("__root");
        return null;
    }

    function redirigir($destino = "index.php") {
        header("Location:" . $destino);
        exit;
    }

}

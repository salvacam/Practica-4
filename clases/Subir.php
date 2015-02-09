<?php

/**
 * Class Subir
 *
 * @version 0.1
 * @author izv
 * @license http://...
 * @copyright izv by 2daw
 * Esta clase dispone de métodos que se utilizan para
 * la subida de archivos.
 * 
 */
class Subir {

    private $input;
    private $files;
    private $destino;
    private $nombre;

    const IGNORAR = 0, RENOMBRAR = 1, REEMPLAZAR = 2;
    const ERROR_INPUT = -1;

    private $accion;
    private $maximo;
    private $extension;
    private $tipo;
    private $error_php;
    private $error;
    private $crearCarpeta;

    function __construct($param) {
        $this->input = $param;
        $this->destino = "./";
        $this->nombre = "";
        $this->accion = Subir::IGNORAR;
        $this->maximo = 2 * 1024 * 1024;
        $this->tipo = array();
        $this->extension = array();
        $this->error_php = UPLOAD_ERR_OK;
        $this->error = 0;
        $this->files = $_FILES[$param];
        $this->crearCarpeta = FALSE;
        /* $this->origen = $_FILES[$nombre]["name"];
          $this->type = $_FILES[$nombre]["type"];
          $this->rutaRelativa = $_FILES[$nombre]["tmp_name"];
          $this->error = $_FILES[$nombre]["error"];
          $this->size = $_FILES[$nombre]["size"]; */
    }

    /**
     * Devuelve el codigo del error de subida del archivo
     * @access public
     * @return 
     */
    public function getErrorPHP() {
        return $this->errorPHP;
    }

//

    public function getError() {
        return $this->error;
    }

//

    public function getErrorMensaje() {
        //Usar switch
        if ($this->error == -1) {
            return "Error";
        }
    }

    public function setCrearCarpeta($crearCarpeta) {
        $this->crearCarpeta = $crearCarpeta;
    }

    /**
     * Establece la ruta relativa donde subir el archivo.
     * @access public
     * @param string $param Cadena con el nombre del parámetro
     */
    public function setDestino($param) {
        $caracter = substr($param, -1);
        if ($caracter != "/") {
            $param.="/";
        }
        $this->destino = $param;
    }

    public function getDestino() {
        return $this->destino;
    }

    /**
     * Establece el nombre sin extension con que se guarda el archivo.
     * @access public
     * @param string $param Cadena con el nombre del parámetro
     */
    public function setNombre($param) {
        $this->nombre = $param;
    }

    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Establece la politica de guardado, sobreescribe, reemplaza o
     * ignora si el archivo ya existe.
     * @access public
     * @param string $param Cadena con el nombre del parámetro
     */
    public function setAccion($param) {
        if ($param == self::RENOMBRAR || $param == self::REEMPLAZAR ||
                $param == self::IGNORAR) {
            $this->accion = $param;
        } else {
            $this->accion = self::IGNORAR;
        }
    }

    /**
     * Establece el tamaño máximo del archivo a subir.
     * @access public
     * @param integer $param Entero
     */
    public function setMaximo($maximo) {
        $this->maximo = $maximo;
    }

    /**
     * Añade una extensión que vamos a permitir subir.
     * @access public
     * @param string|array $param Cadena con el nombre del parámetro
     */
    public function addExtension($param) {
        if (is_array($param)) {
            $this->extension = array_merge($this->extension, $param);
        } else {
            $this->extension[] = $param;
        }
    }

    /**
     * Añade el tipo MIME que vamos a permitir subir.
     * @access public
     * @param string|array $param Cadena con el nombre del tipo MIME
     */
    public function addTipo($param) {
        if (is_array($param)) {
            $this->tipo = array_merge($this->tipo, $param);
        } else {
            $this->tipo[] = $param;
        }
    }

    public function getTipo() {
        foreach ($this->tipo as $value) {
            echo $value . "<br/>";
        }
    }

    /**
     * Devuelve el mensaje de subida del archivo
     * @access public
     * @return 
     */
    public function getMensajeError() {
        return $this->error_php;
    }

    private function isInput() {
        if (!isset($_FILES[$this->input])) {
            $this->error_php = "NO existe el campo";
            return false;
        }
        return true;
    }

    private function isError() {
        if ($this->files["error"] != UPLOAD_ERR_OK) {
            return true;
        }
        return false;
    }

    private function isTamano() {
        if ($this->files["size"] > $this->maximo) {
            $this->error_php = "sobre pasa tamaño";
            return false;
        }
        return true;
    }

    private function isExtension($param) {
        if (sizeof($this->extension) > 0 &&
                !in_array($param, $this->extension)) {
            $this->error_php = "extension no valida";
            return false;
        }
        return true;
    }

    private function isTipo($param) {
        if (sizeof($this->tipo) > 0 &&
                !in_array($param, $this->tipo)) {
            $this->error_php = "tipo MIME no valido";
            return false;
        }
        return true;
    }

    private function isCarpeta() {
        if (!file_exists($this->destino) && !is_dir($this->destino)) {
            //$this->mensaje = "Carpeta no valida";
            $this->error_php = -4;
            return false;
        }
        return true;
    }

    private function crearCarpeta() {
        //return mkdir($this->destino, Configuracion::PERMISOS, true);
        return mkdir($this->destino, 0777, true);
    }

    public function subir() {
        $this->error = 0;
        if (!$this->isInput()) {
            return false;
        }
        $this->files = $_FILES[$this->input];
        $this->errorPHP = $this->files["error"];
        if ($this->isError()) {
            return false;
        }
        if (!$this->isTamano()) {
            return false;
        }
        if (!$this->isCarpeta()) {
            if ($this->crearCarpeta) {
                $this->error_php = 0;
                if (!$this->crearCarpeta()) {
                    $this->error_php = -7;
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        }
        $partes = pathinfo($this->files["name"]);
        $extension = $partes['extension'];
        $nombreOriginal = $partes['filename'];
        if (!$this->isExtension($extension)) {
            return false;
        }
    if (!$this->isTipo($this->files["type"])) {
            return false;
        }
        if ($this->nombre === "") {
            $this->nombre = $nombreOriginal;
        }
        $origen = $this->files["tmp_name"];
        $destino = $this->destino . $this->nombre . "." . $extension;
        if ($this->accion == Subir::REEMPLAZAR) {
            return move_uploaded_file($origen, $destino);
        } elseif ($this->accion == Subir::IGNORAR) {
            if (file_exists($destino)) {
                $this->error = -5;
                return false;
            }
            return move_uploaded_file($origen, $destino);
        } elseif ($this->accion == Subir::RENOMBRAR) {
            $i = 1;
            while (file_exists($destino)) {
                $destino = $destino = $this->destino .
                        $this->nombre . "_$i." . $extension;
                $i++;
            }
            return move_uploaded_file($origen, $destino);
        }
        $this->error = -6;
        return false;
        /*
          //destino
          if ($this->destino != "") {
          if (substr($this->destino, -1) != "/") {
          $this->destino .= "/";
          }
          }
          //nombre
          $nombreOriginal = $_FILES[$this->atributo]["name"];
          if ($this->nombre == "") {
          $this->nombre = $nombreOriginal;
          } else {
          $n = pathinfo($_FILES[$this->atributo]["name"]);
          $this->nombre .="." . $n["extension"];
          }
          $guardar = $this->destino . $this->nombre;
          $rutaRelativa = $_FILES[$this->atributo]["tmp_name"];
          if (move_uploaded_file($rutaRelativa, $guardar)) {
          $this->error_php = "Se ha subido correctamente el archivo";
          } else {
          $this->error_php = "No se ha subido el archivo";
          } */
    }

}

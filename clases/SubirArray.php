<?php

/**
 * Class SubirArray
 *
 * @version 0.1
 * @author izv
 * @license http://...
 * @copyright izv by 2daw
 * Esta clase dispone de métodos que se utilizan para la subida de archivos.
 */
class SubirArray {

    private $input;
    private $files;
    private $destino;
    private $nombre;

    const IGNORAR = 0, RENOMBRAR = 1, REEMPLAZAR = 2;

    private $accion;
    private $maximo;
    private $extension;
    private $tipo;
    private $error_php;
    private $error;
    private $crearCarpeta;

    function __construct($param) {
        if (is_array($_FILES[$param]["name"])) {
            foreach ($_FILES[$param]['name'] as $file) {
                $this->nombre[] = "";
                $this->error[] = 0;
                $this->error_php[] = UPLOAD_ERR_OK;
            }
            foreach ($_FILES[$param] as $key => $value) {
                $this->files[] = $value;
            }
        } else {
            $this->nombre[] = "";
            $this->error[] = 0;
            $this->error_php[] = UPLOAD_ERR_OK;
            $this->files[0][] = $_FILES[$param]["name"];
            $this->files[1][] = $_FILES[$param]["type"];
            $this->files[2][] = $_FILES[$param]["tmp_name"];
            $this->files[3][] = $_FILES[$param]["error"];
            $this->files[4][] = $_FILES[$param]["size"];
        }
        $this->destino = "./";
        $this->input = $param;
        $this->accion = SubirArray::IGNORAR;
        $this->maximo = 2 * 1024 * 1024;
        $this->tipo = array();
        $this->extension = array();
    }

    /**
     * Devuelve el codigo del error de subida del archivo
     * @access public
     * @return 
     */
    public function getErrorPHP() {
        $salida = "";
        foreach ($this->error_php as $value) {
            $salida.= $value . "<br/>";
        }
        return $salida;
    }

    /**
     * Devuelve el codigo del error de la clase subir
     * @access public
     * @return 
     */
    public function getError() {
        $salida = "";
        foreach ($this->error as $value) {
            $salida.= $value . "<br/>";
        }
    }

    /**
     * Devuelve el texto del error de la clase subir
     * @access public
     * @return string mensaje de error de la clase
     */
    public function getErrorMensaje() {
        $salida = "";
        for ($i = 0; $i < sizeof($this->error); $i++) {
            switch ($this->error[$i]) {
                case 0:
                    $salida.= $this->files[0][$i] . ": Subido sin error<br/>";
                    break;
                case -1:
                    $salida.= $this->files[0][$i] . ": Error al definir el campo input<br/>";
                    break;
                case -2:
                    $salida.= $this->files[0][$i] . ": Error de php al subir el archivo" . "<br/>";
                    break;
                case -3:
                    $salida.= $this->files[0][$i] . ": Error el archivo supera el tamaño máximo definido<br/>";
                    break;
                case -4:
                    $salida.= $this->files[0][$i] . ": Error extensión no valida<br/>";
                    break;
                case -5:
                    $salida.= $this->files[0][$i] . ": Error tipo MIME no valido<br/>";
                    break;
                case -6:
                    $salida.= $this->files[0][$i] . ": Error no se puede guardar en la carpeta definida<br/>";
                    break;
                case -7:
                    $salida.= $this->files[0][$i] . ": Error no se puede crear la carpeta definida<br/>";
                    break;
                case -8:
                    $salida.= $this->files[0][$i] . ": Error el archivo ya existe<br/>";
                    break;
                case -9:
                    $salida.= $this->files[0][$i] . ": Error no se ha podido subir el archivo<br/>";
            }
        }
        return $salida;
    }

    /**
     * Establece la pólitica de creación de carpetas.
     * @access public
     * @param boolena $crearCarpeta TRUE o FALSE crear carpetas
     */
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

    /**
     * Devuelve el destino donde se intentaran subir los archivos
     * @access public
     * @return string destino
     */
    public function getDestino() {
        return $this->destino;
    }

    /**
     * Establece el nombre sin extension con que se guarda el archivo.
     * @access public
     * @param string[] $param Array de Cadena con los nombres
     */
    public function setNombre() {
        //if (is_array($param)) {
        //for ($i = 0; $i < sizeof($this->nombre) && $i < sizeof($param); $i++) {
        for ($i = 0; $i < sizeof($this->nombre); $i++) {
            $this->nombre[$i] = "avatar";
        }
        //} else {
        //  $this->nombre[0] = $param;
        //}
    }

    /**
     * Devuelve los nombres con los que se intentara guardar los archivos
     * @access public
     * @return string nombre o nombres con los que se guardaran los archivos a subir
     */
    public function getNombre() {
        $salida = "";
        foreach ($this->nombre as $value) {
            $salida .= $value . "<br/>";
        }
        return $salida;
    }

    /**
     * Establece la política de guardado, sobreescribe, reemplaza o
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
     * Devuelve la política de guardado, sobreescribe, reemplaza o
     * ignora si el archivo ya existe.
     * @access public
     * @return const IGNORAR = 0, RENOMBRAR = 1, REEMPLAZAR = 2;
     */
    public function getAccion() {
        return $this->accion;
    }

    /**
     * Establece el tamaño máximo del archivo a subir.
     * @access public
     * @param integer $maximo Entero
     */
    public function setMaximo($maximo) {
        $this->maximo = $maximo;
    }

    /**
     * Devuelve el tamaño máximo permitido
     * @access public
     * @return int tamaño maximo que se puede subir
     */
    public function getMaximo() {
        return $this->maximo;
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
     * Devuelve las extensiones de archivos que permitimos subir
     * @access public
     * @return string extensiones que permitimos subir
     */
    public function getExtension() {
        $salida = "";
        foreach ($this->extension as $value) {
            $salida.= $value . "<br/>";
        }
        return salida;
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

    /**
     * Devuelve los tipos MIME de archivos que permitimos subir
     * @access public
     * @return string tipos MIME que permitimos subir
     */
    public function getTipo() {
        $salida = "";
        foreach ($this->tipo as $value) {
            $salida .= $value . "<br/>";
        }
        return $salida;
    }

    /**
     * Devuelve el mensaje de error de PHP de subida del archivo
     * @access public
     * @return string mensaje de error de PHP
     */
    public function getMensajeError() {
        $salida = "";
        foreach ($this->error_php as $value) {
            $salida .= $value . "<br/>";
        }
        return $salida;
    }

    private function isInput() {
        if (!isset($_FILES[$this->input])) {
            return false;
        }
        return true;
    }

    private function isError($param) {
        if ($param != UPLOAD_ERR_OK) {
            return true;
        }
        return false;
    }

    private function isTamano($param) {
        if ($param > $this->maximo) {
            return true;
        }
        return false;
    }

    private function isExtension($param) {
        if (sizeof($this->extension) > 0 &&
                !in_array($param, $this->extension)) {
            return false;
        }
        return true;
    }

    private function isTipo($param) {
        if (sizeof($this->tipo) > 0 &&
                !in_array($param, $this->tipo)) {
            return false;
        }
        return true;
    }

    private function isCarpeta($param) {
        if (!file_exists($param) && !is_dir($param)) {
            return false;
        }
        return true;
    }

    private function crearCarpeta($param) {
        return mkdir($param, 0777, true);
    }

    public function subir() {
        for ($i = 0; $i < sizeof($this->files[0]); $i++) {
            $cont = TRUE;
            $this->error[$i] = 0;
            if (!$this->isInput()) {
                $this->error[$i] = -1;
                $cont = FALSE;
            }
            if ($this->isError($this->files[3][$i]) && $cont) {
                $this->error[$i] = -2;
                $cont = FALSE;
            }
            if ($this->isTamano($this->files[4][$i]) && $cont) {
                $this->error[$i] = -3;
                $cont = FALSE;
            }
            //Comprobar si se crea carpeta
            if (!$this->isCarpeta($this->destino) && $cont) {
                if ($this->crearCarpeta) {
                    if (!$this->crearCarpeta($this->destino)) {
                        $this->error[$i] = -7;
                        $cont = FALSE;
                    }
                } else {
                    $this->error[$i] = -7;
                    $cont = FALSE;
                }
            }
            $partes = pathinfo($this->files[0][$i]);
            $extension = $partes['extension'];
            $nombreOriginal = $partes['filename'];
            //comprobar extension
            if (!$this->isExtension($extension) && $cont) {
                $this->error[$i] = -4;
                $cont = FALSE;
            }
            //comprobar tipo
            $tipo = explode("/", $this->files[1][$i]);
            if (!$this->isTipo($tipo[0]) && $cont) {
                $this->error[$i] = -5;
                $cont = FALSE;
            }
            if ($this->nombre[$i] === "") {
                $this->nombre[$i] = $nombreOriginal;
            }
            $origen = $this->files[2][$i];
            $destino = $this->destino . $this->nombre[$i] . "." . $extension;
            if ($this->accion == SubirArray::REEMPLAZAR && $cont) {
                if (!move_uploaded_file($origen, $destino)) {
                    $this->error[$i] = -6;
                    $cont = FALSE;
                } else {
                    $cont = FALSE;
                }
            } elseif ($this->accion == SubirArray::IGNORAR && $cont) {
                if (file_exists($destino)) {
                    $this->error[$i] = -8;
                    $cont = FALSE;
                }
                if (!move_uploaded_file($origen, $destino) && $cont) {
                    $this->error[$i] = -6;
                    $cont = FALSE;
                } else {
                    $cont = FALSE;
                }
            } elseif ($this->accion == SubirArray::RENOMBRAR && $cont) {
                $x = 1;
                while (file_exists($destino)) {
                    $destino = $this->destino .
                            $this->nombre[$i] . "_" . $x . "." . $extension;
                    $x++;
                }
                if (!move_uploaded_file($origen, $destino)) {
                    $this->error[$i] = -6;
                    $cont = false;
                } else {
                    $cont = FALSE;
                }
            }
            if ($cont) {
                $this->error[$i] = -9;
            }
        }
    }

}

<?php
include_once("helper/MyConexion.php");
include_once("helper/NewRouter.php");
include_once("controller/UsuarioController.php");
include_once("controller/PartidaController.php");
include_once("controller/PreguntaController.php");
include_once("controller/EstadisticasController.php");
include_once("model/UsuarioModel.php");
include_once("model/PartidaModel.php");
include_once("model/PreguntaModel.php");
include_once("model/EstadisticasModel.php");
include_once('vendor/mustache/src/Mustache/Autoloader.php');
include_once ("helper/MustacheRenderer.php");

class ConfigFactory
{
    private $config;
    private $objetos;

    private $conexion;
    private $renderer;

    public function __construct()
    {
        $this->config = parse_ini_file("config/config.ini");

        $this->conexion= new MyConexion(
            $this->config["server"],
            $this->config["user"],
            $this->config["pass"],
            $this->config["database"]
        );

        $this->renderer = new MustacheRenderer("vista");

        $this->objetos["router"] = new NewRouter($this, "UsuarioController", "base");

        $this->objetos["UsuarioController"] = new UsuarioController(new UsuarioModel($this->conexion), $this->renderer);

        $this->objetos["PartidaController"] = new PartidaController(new PartidaModel($this->conexion), $this->renderer);

        $this->objetos["PreguntaController"] = new PreguntaController(new PreguntaModel($this->conexion), $this->renderer);

        $this->objetos["EstadisticasController"] = new EstadisticasController(new EstadisticasModel($this->conexion), $this->renderer);

    }

    public function get($objectName)
    {
        return $this->objetos[$objectName];
    }
}
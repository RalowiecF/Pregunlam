<?php

class PreguntaController
{
    private $model;
    private $renderer;

    public function __construct($model, $renderer)
    {
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function base()
    {
        $this->registrarPregunta();
    }

    public function registrarPregunta()
    {
        if(isset($_SESSION["usuarioLogueado"])) {
            $this->renderer->render("registroPregunta", ["usuarioLogueado" => $_SESSION["usuarioLogueado"]]);
        }else $this->renderer->render("registroPregunta");
    }
}
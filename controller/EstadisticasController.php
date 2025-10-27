<?php

class EstadisticasController
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
        $this->seleccion();
    }

    public function seleccion()
    {
        $this->renderer->render("seleccionEstadistica");
    }
}
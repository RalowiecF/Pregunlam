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
        $this->seleccionPartida();
    }

    public function ver()
    {
        $this->renderer->render("seleccionPartida");
    }
}
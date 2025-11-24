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

    public function base(){
        $this->seleccion();
    }

    public function seleccion(){
        $this->renderer->render("seleccionEstadistica", ["usuarioLogueado" => $_SESSION["usuarioLogueado"]]);
    }

    public function ver(){
        if ($this->validarOpcion() && $this->validarPeriodo()){
            $data = $this->model->getEstadistica($_POST['opcion'], $_POST['periodo']);
            $this->renderer->render("seleccionEstadistica", $data);
        } else {
            $this->renderer->render("seleccionEstadistica", ["usuarioLogueado" => $_SESSION["usuarioLogueado"]]);
        }
    }

    public function exportarPdf(){
        if ($this->validarOpcion() && $this->validarPeriodo()){
            $this->model->exportarPdf($_POST['opcion'], $_POST['periodo']);
        } else {
            $this->renderer->render("seleccionEstadistica", ["usuarioLogueado" => $_SESSION["usuarioLogueado"]]);
        }
    }

    public function validarOpcion(){
        $opcionesValidas = ['País', 'Sexo', 'Edad', 'Partidas', 'Resultados', 'PreguntasSugeridas'];
        if(isset($_POST['opcion']) && in_array($_POST['opcion'], $opcionesValidas)){
            return true;
        }else {
            $this->renderer->render("lobby", ["usuarioLogueado" => $_SESSION["usuarioLogueado"],
                "error" => 'Opcion invalida.',]);
            exit();
        }
    }

    public function validarPeriodo(){
        $opcionesValidas = ['dia', 'semana', 'mes', 'anio'];
        if(isset($_POST['periodo']) && in_array($_POST['periodo'], $opcionesValidas)){
            return true;
        }else {
            $this->renderer->render("lobby", ["usuarioLogueado" => $_SESSION["usuarioLogueado"],
                "error" => 'Periodo invalido.',]);
            exit();
        }
    }

}
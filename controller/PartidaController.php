<?php

class PartidaController
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

    public function seleccionPartida()
    {
        if(isset($_SESSION["usuarioLogueado"])) {
            $this->renderer->render("seleccionPartida", ["usuarioLogueado" => $_SESSION["usuarioLogueado"]]);
        }else $this->renderer->render("seleccionPartida");
    }

    public function nueva(){
        $this->validarCantidadPreguntas();
        if(isset($_SESSION["usuarioLogueado"])) {
            $pregunta = $this->model->nuevaPartida($_POST['cantidadPreguntas']);
            $trampas = $this->model->getTrampas();
            $data = ["usuarioLogueado" => $_SESSION["usuarioLogueado"],
                "pregunta"        => $pregunta,
                "trampas"         => $trampas];
            $this->renderer->render("partida", $data);
        }else $this->redirectToIndex();
    }

    public function continuarPartida(){
        $this->validarOpcionSeleccionada();
        $continuarPartida = $this->model->continuarPartida((INT)$_POST['opcionSeleccionada']);
        if($continuarPartida['siguientePaso'] === 'siguientePregunta'){
            $trampas = $this->model->getTrampas();
            $data = ["usuarioLogueado" => $_SESSION["usuarioLogueado"],
                "pregunta"        => $continuarPartida['pregunta'],
                "trampas"         => $trampas];
            $this->renderer->render("partida", $data);
        }else{
            $this->renderer->render("resultado", ["victoria" => $continuarPartida['victoria']]);
        }
    }

    public function redirectToIndex(){
        header("Location: " . BASE_URL);
        exit;
    }

    public function validarCantidadPreguntas(){
        $opcionesValidas = ['5', '10', '15'];
        if(isset($_POST['cantidadPreguntas']) && in_array((INT)$_POST['cantidadPreguntas'], $opcionesValidas)){
            return true;
        }else $this->redirectToIndex();
    }

    public function validarOpcionSeleccionada(){
        if(isset($_POST["opcionSeleccionada"]) && (1<=(INT)$_POST["opcionSeleccionada"] && (INT)$_POST['opcionSeleccionada']<=4)){
            return true;
        }else $this->redirectToIndex();
    }

}
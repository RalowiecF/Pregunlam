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
        $usuarioLogueado = $_SESSION["usuarioLogueado"];
        if($this->model->verificarPartidaInconclusa()) {
            $this->validarCantidadPreguntas();
                $data = $this->model->nuevaPartida((INT)$_POST['cantidadPreguntas']);
                $this->renderer->render("partida", $data);
        }else {
            $error = $_SESSION["error"];
            unset($_SESSION["error"]);
            return $this->renderer->render("lobby", ["usuarioLogueado" => $usuarioLogueado,
                "error" => $error,]);
        }
    }

    public function continuarPartida(){
        $idPregunta = isset($_POST['idPregunta']) ? (int)$_POST['idPregunta'] : 0;
        if($this->model->verificarPreguntaInconclusa($idPregunta) && $this->model->verificarTiempoDeRespuesta($idPregunta)) {
            if(isset($_POST['hacerTrampa'])){
                $continuarPartida = $this->model->hacerTrampa($idPregunta);
            } else {
                $this->validarOpcionSeleccionada();
                $continuarPartida = $this->model->continuarPartida((INT)$_POST['opcionSeleccionada']);
            }
            if($continuarPartida['siguientePaso'] === 'siguientePregunta'){
                $this->renderer->render("partida", $continuarPartida);
                }else{
                    $this->renderer->render("resultado", $continuarPartida);
                }
        } else {
                $error = $_SESSION["error"];
                unset($_SESSION["error"]);
                return $this->renderer->render("lobby", ["usuarioLogueado" => $_SESSION["usuarioLogueado"],
                                                            "error" => $error,]);
            }
    }

    public function verReglas()
    {
        if(isset($_SESSION["usuarioLogueado"])) {
            $this->renderer->render("reglas", ["usuarioLogueado" => $_SESSION["usuarioLogueado"]]);
        }else $this->renderer->render("seleccionPartida");
    }

    public function redirectToIndex(){
        header("Location: " . BASE_URL);
        exit;
    }

    public function validarCantidadPreguntas(){
        $opcionesValidas = ['10', '15', '20'];
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
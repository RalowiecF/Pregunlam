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

    public function redirectToIndex()
    {
        header("Location: " . BASE_URL);
        exit;
    }

    public function registrarPregunta()
    {
        if (!isset($_SESSION["usuarioLogueado"])) {
            $this->redirectToIndex();
            return;
        }
        $categorias = $this->model->obtenerCategorias();
        $niveles = $this->model->obtenerNiveles();
        $this->renderer->render("registroPregunta", [
            "usuarioLogueado" => $_SESSION["usuarioLogueado"],
            "categorias" => $categorias,
            "niveles" => $niveles
        ]);
    }

    public function listaPreguntas(){
        if (isset($_SESSION["usuarioLogueado"])) {
            $this->renderer->render("listaPreguntas", ["preguntasSugeridas" => $this->model->obtenerPreguntasSegunIdEstado(1),
                                    "preguntasReportadas" => $this->model->obtenerPreguntasSegunIdEstado(3),
                                    "preguntasVigentes" => $this->model->obtenerPreguntasSegunIdEstado(2),
                                    "categorias" => $this->model->obtenerCategorias(),
                                    "niveles" => $this->model->obtenerNiveles(),
                                    "usuarioLogueado" => $_SESSION["usuarioLogueado"]]);
        } else $this->redirectToIndex();
    }

    public function sugerirPreguntaNueva()
    {
        if (!isset($_SESSION["usuarioLogueado"])) {
            $this->redirectToIndex();
        }

        $enunciado = $_POST["enunciado"];
        $categoria = $_POST["categoria"];
        $nivel = $_POST["nivel"];
        $respuestaCorrecta = $_POST["respuestaCorrecta"];
        $respuestaIncorrecta1 = $_POST["respuestaIncorrecta1"];
        $respuestaIncorrecta2 = $_POST["respuestaIncorrecta2"];
        $respuestaIncorrecta3 = $_POST["respuestaIncorrecta3"];


        $resultado = $this->model->sugerirPreguntaNueva(
            $enunciado,
            $categoria,
            $nivel,
            $respuestaCorrecta,
            $respuestaIncorrecta1,
            $respuestaIncorrecta2,
            $respuestaIncorrecta3
        );

        if ($resultado) {
            $_SESSION['mensaje'] = "Pregunta registrada correctamente.";
            $this->redirectToIndex();
        } else {
            $_SESSION['error'] = "Error al registrar la pregunta.";
            $this->renderer->render("registroPregunta");
        }
    }

    public function cambiarEstadoPregunta($idPregunta,$nuevoEstado){
        if (!isset($_SESSION["usuarioLogueado"])) {
            $this->redirectToIndex();
        }

        $preguntaObtenida = $this->model->obtenerPregunta($idPregunta);
        if($preguntaObtenida == null){
            $_SESSION['error'] = "La pregunta no existe.";
            $this->redirectToIndex();
        }
        $this->model->cambiarEstadoPregunta($idPregunta,$nuevoEstado);
        $_SESSION['mensaje'] = "Estado de la pregunta actualizado correctamente.";
        $this->listaPreguntas();
    }

    public function eliminarPregunta($idPregunta){
        if (!isset($_SESSION["usuarioLogueado"])) {
            $this->redirectToIndex();
        }

        $this->model->eliminarPregunta($idPregunta);
        $_SESSION['mensaje'] = "Pregunta eliminada correctamente.";
        $this->listaPreguntas();
    }

    public function editarPregunta($idPregunta){
        if(!isset($_SESSION["usuarioLogueado"])){
            $this->redirectToIndex();
            return;
        }
        $esEdicion = true;
        $pregunta = $this->model->obtenerPreguntaPorId($idPregunta);
        $categorias = $this->model->obtenerCategorias();
        $niveles = $this->model->obtenerNiveles();
        $this->renderer->render("registroPregunta", [
            "usuarioLogueado" => $_SESSION["usuarioLogueado"],
            "esEdicion" => $esEdicion,
            "pregunta" => $pregunta,
            "categorias" => $categorias,
            "niveles" => $niveles
        ]);
    }


}
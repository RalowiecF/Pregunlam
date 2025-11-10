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

    public function listaPreguntas()
    {
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

    public function cambiarEstadoPregunta()
    {
        if (!isset($_SESSION["usuarioLogueado"])) {
            $this->redirectToIndex();
        }

        $idPregunta = $_GET['idPregunta'];
        $nuevoEstado = $_GET["nuevoEstado"];

        $preguntaObtenida = $this->model->obtenerPregunta($idPregunta);
        if ($preguntaObtenida == null) {
            $_SESSION['error'] = "La pregunta no existe.";
            $this->redirectToIndex();
        }
        $this->model->cambiarEstadoPregunta($idPregunta, $nuevoEstado);
        $_SESSION['mensaje'] = "Estado de la pregunta actualizado correctamente.";
        $this->listaPreguntas();
    }

    public function eliminarPregunta()
    {
        if (!isset($_SESSION["usuarioLogueado"])) {
            $this->redirectToIndex();
        }

        $idPregunta = $_GET['idPregunta'];

        $this->model->eliminarPregunta($idPregunta);
        $_SESSION['mensaje'] = "Pregunta eliminada correctamente.";
        $this->listaPreguntas();
    }

    public function editarPregunta()
    {
        if (!isset($_SESSION["usuarioLogueado"])) {
            $this->redirectToIndex();
            return;
        }
        $idPregunta = $_GET['idPregunta'];
        $esEdicion = true;
        $pregunta = $this->model->obtenerPreguntaPorId($idPregunta);
        $respuestasIncorrectas = $this->model->obtenerRespuestasIncorrectasPorIdPregunta($idPregunta);
        $categorias = $this->model->obtenerCategorias();
        $niveles = $this->model->obtenerNiveles();
        $this->renderer->render("registroPregunta", [
            "usuarioLogueado" => $_SESSION["usuarioLogueado"],
            "esEdicion" => $esEdicion,
            "pregunta" => $pregunta,
            "respuestasIncorrectas" => $respuestasIncorrectas,
            "categorias" => $categorias,
            "niveles" => $niveles
        ]);
    }

    public function reportarPregunta()
    {
        if (!isset($_SESSION["usuarioLogueado"])) {
            $this->redirectToIndex();
            return;
        }

        $idPregunta = $_GET['idPregunta'];
        $pregunta = $this->model->obtenerPreguntaPorId($idPregunta);
        $categoriaArray = $this->model->obtenerCategoriaPorId($pregunta['idCategoria']);
        $categoria = $categoriaArray[0];
        $this->renderer->render("reportarPregunta", [
            "usuarioLogueado" => $_SESSION["usuarioLogueado"],
            "pregunta" => $pregunta,
            "categoria" => $categoria
        ]);
    }

    public function reportarPreguntaForm(){
        if (!isset($_SESSION["usuarioLogueado"])) {
            $this->redirectToIndex();
            return;
        }

        $idPregunta = $_POST["idPregunta"];
        $motivo = $_POST["motivoReporte"];

        $this->model->reportarPregunta($idPregunta, $motivo);

        $_SESSION['mensaje'] = "La pregunta ha sido reportada correctamente.";
        $this->redirectToIndex();
    }


    public function editarPreguntaForm()
    {
        if (!isset($_SESSION["usuarioLogueado"])) {
            $this->redirectToIndex();
            return;
        }
        $idPregunta = $_POST["idPregunta"];
        $enunciado = $_POST["enunciado"];
        $categoria = $_POST["categoria"];
        $nivel = $_POST["nivel"];
        $respuestaCorrecta = $_POST["respuestaCorrecta"];

        $this->model->editarPregunta(
            $idPregunta,
            $enunciado,
            $categoria,
            $nivel,
            $respuestaCorrecta
        );

        $idRespuestas = $_POST["idRespuestaIncorrecta"];
        $respuestas = $_POST["respuestaIncorrecta"];

        foreach ($idRespuestas as $i => $idRespuestaIncorrecta) {
            $texto = $respuestas[$i];
            $this->model->editarRespuestasIncorrectas($idRespuestaIncorrecta, $texto);
        }

        $this->listaPreguntas();

    }


    public function filtrarPreguntasSugeridas()
    {
        if (!isset($_SESSION["usuarioLogueado"])) {
            $this->redirectToIndex();
            return;
        }

        $idCategoria = $_POST["categoria"];
        if(empty($idCategoria)){
            $preguntasObtenidas = $this->model->obtenerPreguntasSegunIdEstado(1);
        }else{
            $preguntasObtenidas = $this->model->obtenerPreguntasSugeridasPorCategoria($idCategoria);
        }

        $this->renderer->render("listaPreguntas", ["preguntasSugeridas" => $preguntasObtenidas,
            "preguntasReportadas" => $this->model->obtenerPreguntasSegunIdEstado(3),
            "preguntasVigentes" => $this->model->obtenerPreguntasSegunIdEstado(2),
            "categorias" => $this->model->obtenerCategorias(),
            "niveles" => $this->model->obtenerNiveles(),
            "usuarioLogueado" => $_SESSION["usuarioLogueado"]]);

    }

    public function filtrarPreguntasVigentes()
    {
        if (!isset($_SESSION["usuarioLogueado"])) {
            $this->redirectToIndex();
            return;
        }

        $seccionActiva = $_POST["seccionActiva"];
        $idCategoria = $_POST["categoria"];
        if(empty($idCategoria)){
            $preguntasObtenidas = $this->model->obtenerPreguntasSegunIdEstado(2);
        }else{
            $preguntasObtenidas = $this->model->obtenerPreguntasVigentesPorCategoria($idCategoria);
        }

        $this->renderer->render("listaPreguntas", ["preguntasVigentes" => $preguntasObtenidas,
            "preguntasReportadas" => $this->model->obtenerPreguntasSegunIdEstado(3),
            "preguntasSugeridas" => $this->model->obtenerPreguntasSegunIdEstado(1),
            "categorias" => $this->model->obtenerCategorias(),
            "niveles" => $this->model->obtenerNiveles(),
            "seccionActiva" => $seccionActiva,
            "usuarioLogueado" => $_SESSION["usuarioLogueado"]]);

    }

    public function aprobarPregunta()
    {
        if (!isset($_SESSION["usuarioLogueado"])) {
            $this->redirectToIndex();
            return;
        }

        $idPregunta = $_GET['idPregunta'];
        $this->model->aprobarPregunta($idPregunta);
        $_SESSION['mensaje'] = "Pregunta aprobada correctamente.";
        $this->listaPreguntas();
    }


}
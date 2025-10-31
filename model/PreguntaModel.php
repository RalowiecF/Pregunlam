<?php

class PreguntaModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerPreguntasSegunIdEstado($idEstado)
    {
        $sql = "SELECT * FROM pregunta p WHERE p.idEstado = $idEstado ";
        return $this->conexion->query($sql);
    }

    public function obtenerRespuestasIncorrectasSegunIdPregunta($idPregunta)
    {
        $sql = "SELECT ri.respuestaIncorrecta FROM respuesta_incorrecta ri WHERE ri.idPregunta = $idPregunta ";
        return $this->conexion->query($sql);
    }


    public function obtenerCategorias()
    {
        $sql = "SELECT * FROM categoria";
        return $this->conexion->query($sql);
    }

    public function obtenerNiveles()
    {
        $sql = "SELECT * FROM nivel";
        return $this->conexion->query($sql);
    }

    public function sugerirPreguntaNueva($enunciado, $categoria, $nivel, $respuestaCorrecta, $respuestaIncorrecta1, $respuestaIncorrecta2, $respuestaIncorrecta3)
    {
        // Insertar la pregunta y obtener su id
        $sqlPregunta = "INSERT INTO pregunta (enunciado, idCategoria, idNivel, respuestaCorrecta) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sqlPregunta);
        $stmt->bind_param("siis", $enunciado, $categoria, $nivel, $respuestaCorrecta);
        $stmt->execute([$enunciado, $categoria, $nivel, $respuestaCorrecta]);
        $idPregunta = $this->obtenerIdUltimaPregunta();


        // Insertar respuestas incorrectas
        $sqlRespuesta = "INSERT INTO respuesta_incorrecta (idPregunta, respuestaIncorrecta) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($sqlRespuesta);

        $stmt->bind_param("is", $idPregunta, $respuestaIncorrecta1);
        $stmt->execute();

        $stmt->bind_param("is", $idPregunta, $respuestaIncorrecta2);
        $stmt->execute();

        $stmt->bind_param("is", $idPregunta, $respuestaIncorrecta3);
        $stmt->execute();

        return true;
    }

    public function obtenerIdUltimaPregunta()
    {
        // Consulta SQL para obtener el último ID de pregunta
        $sql = "SELECT idPregunta FROM pregunta ORDER BY idPregunta DESC LIMIT 1";

        // Preparar la consulta
        $stmt = $this->conexion->prepare($sql);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $result = $stmt->get_result();

        // Si hay resultados, devolver el idPregunta
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc(); // Obtener la primera fila
            return $row['idPregunta']; // Devolver el idPregunta
        } else {
            return null; // Si no hay preguntas, devolver null
        }
    }

    public function eliminarPregunta($idPregunta)
    {
        $sqlEliminarRespuestas = "DELETE FROM respuesta_incorrecta WHERE idPregunta = ?";
        $stmt = $this->conexion->prepare($sqlEliminarRespuestas);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();

        $sqlEliminarPregunta = "DELETE FROM pregunta WHERE idPregunta = ?";
        $stmt = $this->conexion->prepare($sqlEliminarPregunta);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();

        return true;
    }

    public function obtenerPreguntaPorId($idPregunta)
    {
        $sql = "SELECT * FROM pregunta WHERE idPregunta = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function obtenerRespuestasIncorrectasPorIdPregunta($idPregunta)
    {
        $sql = "SELECT * FROM respuesta_incorrecta WHERE idPregunta = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function editarPregunta($idPregunta, $enunciado, $categoria, $nivel, $respuestaCorrecta)
    {
        $sqlActualizarPregunta = "UPDATE pregunta SET enunciado = ?, idCategoria = ?, idNivel = ?, respuestaCorrecta = ? WHERE idPregunta = ?";
        $stmt = $this->conexion->prepare($sqlActualizarPregunta);
        $stmt->bind_param("siisi", $enunciado, $categoria, $nivel, $respuestaCorrecta, $idPregunta);
        $stmt->execute();
        return true;
    }

    public function editarRespuestasIncorrectas($idRespuestaIncorrecta,$respuestaIncorrecta)
    {
        $sqlActualizarRespuesta = "UPDATE respuesta_incorrecta SET respuestaIncorrecta = ? WHERE idRespuestaIncorrecta = ?";
        $stmt = $this->conexion->prepare($sqlActualizarRespuesta);
        $stmt->bind_param("si", $respuestaIncorrecta, $idRespuestaIncorrecta);
        $stmt->execute();
        return true;
    }


}
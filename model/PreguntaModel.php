<?php

class PreguntaModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerPreguntasSugeridas()
    {
        $sql = "SELECT * FROM pregunta p WHERE p.idEstado = 1 ";
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

    public function sugerirPreguntaNueva($enunciado,$categoria,$nivel,$respuestaCorrecta,$respuestaIncorrecta1,$respuestaIncorrecta2,$respuestaIncorrecta3){
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

    public function obtenerIdUltimaPregunta() {
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
}
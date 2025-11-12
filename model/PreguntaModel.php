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
        $sql = "SELECT * FROM categoria ";
        return $this->conexion->query($sql);
    }

    public function obtenerCategoriasAprobadas(){
        $sql = "SELECT * FROM categoria c WHERE c.estado = 'Aprobada' ";
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

    public function obtenerPreguntasSugeridasPorCategoria($idCategoria)
    {
        $sql = "SELECT * FROM pregunta p WHERE p.idEstado = 1 AND p.idCategoria = $idCategoria ";
        return $this->conexion->query($sql);
    }

    public function obtenerPreguntasVigentesPorCategoria($idCategoria){
        $sql = "SELECT * FROM pregunta p WHERE p.idEstado = 2 AND p.idCategoria = $idCategoria ";
        return $this->conexion->query($sql);
    }


    public function aprobarPregunta($idPregunta): bool
    {
        $sql = "UPDATE pregunta p SET p.idEstado = 2 WHERE p.idPregunta = $idPregunta ";
        $this->conexion->query($sql);
        return true;
    }

    public function obtenerCategoriaPorId($idCategoria)
    {
        $sql = "SELECT * FROM categoria c WHERE c.idCategoria = $idCategoria ";
        return $this->conexion->query($sql);
    }

    public function reportarPregunta($idPregunta, $motivo){
        if ($this->verificarCantidadReportesDelUsuario() < 3) {
            $idUsuario = $_SESSION["usuarioLogueado"]["idUsuario"];
            $fechaReporte = time();
            $sql = "UPDATE pregunta SET idEstado = 3, motivoReporte = ?, idUsuarioQueReporto = $idUsuario, fechaReporte = $fechaReporte WHERE idPregunta = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("si", $motivo, $idPregunta);
            $stmt->execute();

            if ( $stmt->affected_rows > 0 ) { // true si se actualizó algo
                return ['error' => "La pregunta ha sido reportada.",
                    'usuarioLogueado' => $_SESSION['usuarioLogueado'],];
                    } else {
                return ['error' => "No se encontró la pregunta.",
                    'usuarioLogueado' => $_SESSION['usuarioLogueado'],];
            }
        } else {
            return ['error' => "Alcanzaste el límite de reportes permitidos.",
                    'usuarioLogueado' => $_SESSION['usuarioLogueado'],];
        }
    }

    public function verificarCantidadReportesDelUsuario(): int{
        $idUsuario = (INT)$_SESSION['usuarioLogueado']['idUsuario'];
        $sql = "select count(idUsuarioQueReporto) as cantidad from pregunta where idUsuarioQueReporto = $idUsuario";
        $resultado = $this->conexion->query($sql);
        return (INT)$resultado[0]['cantidad'];
    }


    public function obtenerCategoriaPorNombre($nombreCategoria)
    {
        $sql = "SELECT * FROM categoria WHERE descripcion = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $nombreCategoria);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function sugerirCategoriaNueva($nombreCategoria)
    {
        $sql = "INSERT INTO categoria (descripcion,estado) VALUES (?,'Deshabilitada')";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $nombreCategoria);
        return $stmt->execute();
    }

    public function cambiarEstadoCategoria($idCategoria){
        $sqlObtenerEstado = "SELECT estado FROM categoria WHERE idCategoria = ?";
        $stmt = $this->conexion->prepare($sqlObtenerEstado);
        $stmt->bind_param("i", $idCategoria);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        $estadoActual = $fila['estado'];

        $nuevoEstado = ($estadoActual === 'Aprobada') ? 'Deshabilitada' : 'Aprobada';

        $sqlActualizarEstado = "UPDATE categoria SET estado = ? WHERE idCategoria = ?";
        $stmt = $this->conexion->prepare($sqlActualizarEstado);
        $stmt->bind_param("si", $nuevoEstado, $idCategoria);
        return $stmt->execute();
    }

}
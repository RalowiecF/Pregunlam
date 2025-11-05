<?php

class MyConexion
{

    private $conexion;

    public function __construct($server, $user, $pass, $database)
    {
        $this->conexion = new mysqli($server, $user, $pass, $database);
        mysqli_set_charset($this->conexion, 'utf8mb4');
        $this->conexion->query("SET time_zone = '-03:00'");
        if ($this->conexion->error) { die("Error en la conexión: " . $this->conexion->error); }
    }

    public function query($sql){
        $result = $this->conexion->query($sql);
        if ($result === false || $result === true) {
            return null;
        }
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return null;
    }

    public function multy_query($sql) {
        $data = [];
        if ($this->conexion->multi_query($sql)) {
            do {
                if ($result = $this->conexion->store_result()) {
                    $data[] = $result->fetch_all(MYSQLI_ASSOC);
                    $result->free();
                }
            } while ($this->conexion->more_results() && $this->conexion->next_result());
        } else {
            throw new Exception("Error en multi_query: " . $this->conexion->error);
        }

        // Si hay un SELECT final (como el de idPartida)
        return end($data) ?: null;
    }

    public function prepare($sql) {
        return $this->conexion->prepare($sql);
    }
}
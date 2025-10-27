<?php

class PartidaModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function nuevaPartida($cantidadPreguntas){
        $this->setPreguntasEnSession($cantidadPreguntas);
        $_SESSION['preguntaEnCurso'] = 0;
        $_SESSION['cantidadPreguntas'] = $cantidadPreguntas;
        $_SESSION['idPartidaEnCurso'] = $this->crearPartida();
        $pregunta = $this->getPreguntaOpcionesCategoria((INT)$_SESSION['idsPreguntas'][$_SESSION['preguntaEnCurso']]);
        $this->setPreguntaEnPartida((INT)$_SESSION['idsPreguntas'][(INT)$_SESSION['preguntaEnCurso']]);
        return $pregunta;
    }

    public function continuarPartida($opcionSeleccionada): array
    {
        if($opcionSeleccionada === (INT)$_SESSION['posicionRespuestaCorrecta']
            && (INT)$_SESSION['preguntaEnCurso'] < ((INT)$_SESSION['cantidadPreguntas'])-1){
            return $this->continuarSiguientePregunta($opcionSeleccionada);
        }else if($opcionSeleccionada === (INT)$_SESSION['posicionRespuestaCorrecta']){
            return $this->terminarConVictoria();
        }else{
            return $this->terminarConDerrota();
        }
    }

    public function getTrampas(): array{
        $idUsuario = (INT)$_SESSION['usuarioLogueado']['idUsuario'];
        $sql = "select cantidadTrampas from usuario where idUsuario = $idUsuario;";
        $resultado = $this->conexion->query($sql);
        $cantidadTrampas = (int)$resultado[0]['cantidadTrampas'];
        $tieneTrampas = $cantidadTrampas>0;
        return ['cantidadTrampas' => $cantidadTrampas, 'tieneTrampas' => $tieneTrampas];
    }

    public function setPreguntasEnSession($cantidad) {
        $preguntas = [];
        $pregMismoNivel = $this->getIdsPreguntasMismoNivelSinResponder($cantidad);
        if ($pregMismoNivel && count($pregMismoNivel) == $cantidad) {
            $preguntas = $pregMismoNivel;
        } else {
            $pregSinResponder = $this->getIdsPreguntasSinResponder($cantidad);
            if ($pregSinResponder && count($pregSinResponder) == $cantidad) {
                $preguntas = $pregSinResponder;
            } else {
                $preguntas = $this->getIdsPreguntasCualesquiera($cantidad);
            }
        }
        $_SESSION['idsPreguntas'] = array_column($preguntas, 'idPregunta');
    }


    public function crearPartida(){
        $idUsuario = (INT)$_SESSION['usuarioLogueado']['idUsuario'];
        $sql = "start transaction;
            insert into partida () values ();
            set @idPartida := LAST_INSERT_ID();
            insert into usuario_juega_partida (idUsuario, idPartida) values ($idUsuario, @idPartida);
            select @idPartida as idPartida;
            commit;";
        $resultado = $this->conexion->multy_query($sql);
        return (int)$resultado[0]['idPartida'];
    }

    public function getIdsPreguntasMismoNivelSinResponder($cantidad){
        $idUsuario = (INT)$_SESSION['usuarioLogueado']['idUsuario'];
        $sql = "select p.idPregunta FROM pregunta as p join usuario as u on p.idNivel = u.idNivel where u.idUsuario = $idUsuario
and p.idPregunta not in (select distinct ptp.idPregunta from usuario_juega_partida ujp join partida_tiene_pregunta ptp
on ujp.idPartida = ptp.idPartida where ujp.idUsuario = $idUsuario) order by rand() limit $cantidad;";
        return $this->conexion->query($sql);
    }

    public function getIdsPreguntasSinResponder($cantidad){
        $idUsuario = (INT)$_SESSION['usuarioLogueado']['idUsuario'];
        $sql = "select p.idpregunta from pregunta as p where p.idPregunta not in (select distinct ptp.idPregunta
from usuario_juega_partida as ujp join partida_tiene_pregunta as ptp on ujp.idPartida = ptp.idPartida
where ujp.idUsuario = $idUsuario) order by rand() limit $cantidad;";
        return $this->conexion->query($sql);
    }

    public function getIdsPreguntasCualesquiera($cantidad){
        $sql = "select idPregunta from pregunta order by rand() limit $cantidad;";
        return $this->conexion->query($sql);
    }

    public function getPreguntaOpcionesCategoria($idPregunta): array{
        $resultado = $this->getPreguntaRespuestaCategoria($idPregunta);
        $respuestasIncorrectas = $this->getRespuestasIncorrectas($idPregunta);
        $opcionesMezcladas = $this->mezclarOpciones($resultado[0]['respuestaCorrecta'], array_column($respuestasIncorrectas, 'respuestaIncorrecta'));
        return [
            'enunciado' => $resultado[0]['enunciado'],
            'opciones'  => $opcionesMezcladas,
            'categoria' => $resultado[0]['descripcion']
        ];
    }


    public function getPreguntaRespuestaCategoria($idPregunta){
        $sql = "select p.enunciado, p.respuestaCorrecta, c.descripcion from pregunta as p join categoria as c
    on p.idCategoria = c.idCategoria where p.idPregunta = $idPregunta;";
        return $this->conexion->query($sql);
    }

    public function getRespuestasIncorrectas($idPregunta){
        $sql = "select respuestaIncorrecta from respuesta_incorrecta where idPregunta = $idPregunta;";
        return $this->conexion->query($sql);
    }

    public function mezclarOpciones($respuestaCorrecta, $respuestasIncorrectas): array{
        $posicionRespuestaCorrecta = random_int(1, 4);
        $_SESSION['posicionRespuestaCorrecta'] = $posicionRespuestaCorrecta;
        $opciones = [];
        $indiceIncorrecta = 0;
        for ($i = 1; $i <= 4; $i++){
            $key = "opcion$i";
            if($i === $posicionRespuestaCorrecta){
                $opciones[$key] = $respuestaCorrecta;
            } else {
                $opciones[$key] = $respuestasIncorrectas[$indiceIncorrecta];
                $indiceIncorrecta++;
            }
        } return $opciones;
    }

    public function setPreguntaEnPartida($idPregunta){
        $idPartida = (INT)$_SESSION['idPartidaEnCurso'];
        $sql = "set @idResultadoPendiente := (select idResultado from resultado where descripcion = 'Pendiente');
                insert into partida_tiene_pregunta (idPartida, idPregunta, idResultado) values ($idPartida, $idPregunta, @idResultadoPendiente);";
        $this->conexion->multy_query($sql);
    }

    public function continuarSiguientePregunta($opcionSeleccionada): array{
        $_SESSION['preguntaEnCurso']++;
        $pregunta = $this->getPreguntaOpcionesCategoria((INT)$_SESSION['idsPreguntas'][(INT)$_SESSION['preguntaEnCurso']]);
        $this->setPreguntaEnPartida((INT)$_SESSION['idsPreguntas'][(INT)$_SESSION['preguntaEnCurso']]);
        return ['siguientePaso' => 'siguientePregunta',
            'pregunta'      => $pregunta];
    }

    public function terminarConVictoria(): array{
        unset($_SESSION['preguntaEnCurso']);
        return ['siguientePaso' => 'finalizar',
            'victoria'      => true];
    }

    public function terminarConDerrota(): array{
        unset($_SESSION['preguntaEnCurso']);
        return ['siguientePaso' => 'finalizar',
            'victoria'      => false];
    }

}
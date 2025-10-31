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
        $pregunta = $this->getPreguntaOpcionesCategoriaId((INT)$_SESSION['idsPreguntas'][$_SESSION['preguntaEnCurso']]);
        $this->setPreguntaEnPartida($pregunta['idPregunta']);
        $this->actualizarContadoresEntregaPreguntas($pregunta['idPregunta']);
        return $pregunta;
    }

    public function continuarPartida($opcionSeleccionada): array{
        $idPreguntaEnCurso = (INT)$_SESSION['idsPreguntas'][(INT)$_SESSION['preguntaEnCurso']];
        if($opcionSeleccionada === (INT)$_SESSION['posicionRespuestaCorrecta']
            && (INT)$_SESSION['preguntaEnCurso'] < ((INT)$_SESSION['cantidadPreguntas'])-1){
            $this->actualizarResultadoPreguntaEnPartida($idPreguntaEnCurso, (INT)$_SESSION['idPartidaEnCurso'], 'Correcta');
            $this->actualizarContadoresAciertos($idPreguntaEnCurso, (INT)$_SESSION['usuarioLogueado']['idUsuario']);
            return $this->continuarSiguientePregunta($opcionSeleccionada);
        }else {
            $_SESSION['partidaEnCurso'] = false;
            if ($opcionSeleccionada === (int)$_SESSION['posicionRespuestaCorrecta']) {
                return $this->finalizarPartida(true, $idPreguntaEnCurso);
            } else {
                return $this->finalizarPartida(false, $idPreguntaEnCurso);
            }
        }
    }

    public function verificarPartidaInconclusa(): bool{
        $resultado = $this->getPreguntasPendientes();
        if (sizeof($resultado) > 0) {
            $idPreguntaPendiente = $resultado[0]['idPregunta'];
            $idPartidaPendiente = $resultado[0]['idPartida'];
            $this->cerrarPartidaAbandonada($idPreguntaPendiente, $idPartidaPendiente);
        } else return true;
    }

    public function verificarPreguntaInconclusa($idPregunta): bool {
        if (
            !isset($_SESSION['idsPreguntas'], $_SESSION['preguntaEnCurso'], $_SESSION['idPartidaEnCurso']) ||
            !is_array($_SESSION['idsPreguntas'])) {
            $_SESSION['error'] = "No hay una partida activa.";
            return false;
        }
        $preguntaEnCurso = (int) $_SESSION['preguntaEnCurso'];
        $idsPreguntas = $_SESSION['idsPreguntas'];
        $idPreguntaPendiente = (int) $idsPreguntas[$preguntaEnCurso];

        if ($idPregunta === $idPreguntaPendiente) {
            return true;
        } else {
            $idPartidaPendiente = (int) $_SESSION['idPartidaEnCurso'];
            $this->cerrarPartidaAbandonada($idPreguntaPendiente, $idPartidaPendiente);
        }
    }

    public function cerrarPartidaAbandonada($idPreguntaPendiente, $idPartidaPendiente): bool{
        $this->actualizarResultadoPreguntaEnPartida($idPreguntaPendiente, $idPartidaPendiente, 'Abandonada');
        $puntaje = $this->calcularPuntajePartida($idPartidaPendiente);
        $this->actualizarPuntajePartidaUsuario($idPartidaPendiente, $puntaje);
        $this->actualizarDuracionPartida($idPartidaPendiente);
        $this->actualizarNivelUsuario();
        $this->unsetearVariablesSessionPartida();
        $_SESSION['error'] = "Abandonaste una partida. El resultado fue " . $puntaje . " puntos.";
        return false;
    }

    public function actualizarPuntajePartidaUsuario($idPartidaPendiente, $puntaje){
        $idUsuario = (INT)$_SESSION['usuarioLogueado']['idUsuario'];
        $sqlPartida = "update partida set puntaje = $puntaje where idPartida = $idPartidaPendiente";
        $this->conexion->query($sqlPartida);
        $sqlUsuario = "update usuario set puntaje = (puntaje + $puntaje) where idUsuario = $idUsuario";
        $this->conexion->query($sqlUsuario);
        $_SESSION['usuarioLogueado']['puntaje'] += $puntaje;
    }

    public function actualizarNivelUsuario() {
        $idUsuario = (int)$_SESSION['usuarioLogueado']['idUsuario'];
        $sql = "SELECT u.cantidadAciertos, u.cantidadPreguntas, u.idNivel 
            FROM usuario AS u WHERE u.idUsuario = $idUsuario";
        $resultado = $this->conexion->query($sql);

        if ( (int)$resultado[0]['cantidadPreguntas'] < 10) return;

        $porcentaje = ($resultado[0]['cantidadAciertos'] * 100) / $resultado[0]['cantidadPreguntas'];
        $idNivelUsuario = (int)$resultado[0]['idNivel'];

        $niveles = [
            1 => 10,
            2 => 30,
            3 => 70,
            4 => 90,
            5 => 100
        ];

        $idNivel = 3;
        foreach ($niveles as $nivel => $limite) {
            if ($porcentaje < $limite) {
                $idNivel = $nivel;
                break;
            }
        }

        if ($idNivel !== $idNivelUsuario) {
            $update = "UPDATE usuario SET idNivel = $idNivel WHERE idUsuario = $idUsuario";
            $this->conexion->query($update);
        }
    }

    public function calcularPuntajePartida($idPartida):int{
        $puntajeTotal = 0;
        $puntajesPorNivel = [
            'Muy facil' => 2,
            'Facil' => 3,
            'Normal' => 4,
            'Dificil' => 5,
            'Muy dificil' => 6,
        ];
        $resultadosQueSumanPuntos = ['Correcta', 'Salteada Con Trampa'];
        $sql1 = "SELECT n.descripcion AS nivel, r.descripcion AS resultado FROM nivel AS n JOIN pregunta AS p ON n.idNivel = p.idNivel 
             JOIN partida_tiene_pregunta AS ptp ON p.idPregunta = ptp.idPregunta JOIN resultado AS r ON ptp.idResultado = r.idResultado 
             WHERE ptp.idPartida = $idPartida";
        $resultadosPreguntas = $this->conexion->query($sql1);

        if ($resultadosPreguntas) {
            foreach ($resultadosPreguntas as $pregunta) {
                $nivel = $pregunta['nivel'];
                $resultado = $pregunta['resultado'];
                if (in_array($resultado, $resultadosQueSumanPuntos)) {
                    $puntajeTotal += $puntajesPorNivel[$nivel] ?? 0;
                } else {
                    $puntajeTotal -= 4;
                }
            }
        }
        return $puntajeTotal;
    }

    public function getPreguntasPendientes(){
        $idUsuario = (int)$_SESSION['usuarioLogueado']['idUsuario'];
        $sql = "select ptp.idPregunta, max(p.fechaPartida) as fechaPartida, ptp.idPartida from Resultado as r join partida_tiene_pregunta as ptp 
        on r.idResultado = ptp.idResultado join partida as p on ptp.idPartida = p.idPartida join usuario_juega_partida as ujp 
        on p.idPartida = ujp.idPartida where ujp.idUsuario = $idUsuario and r.descripcion = 'Pendiente' HAVING fechaPartida IS NOT NULL";
        $resultado = $this->conexion->query($sql);
        return $resultado ?? [];
    }

    public function actualizarResultadoPreguntaEnPartida($idPregunta, $idPartida, $estado){
        $sql = "select idResultado from resultado where descripcion = '$estado'";
        $resultado = $this->conexion->query($sql);
        $idResultado = (int)$resultado[0]['idResultado'];
        $sql2 = "update partida_tiene_pregunta set idResultado = $idResultado where idPregunta = $idPregunta and idPartida = $idPartida";
        $this->conexion->query($sql2);
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
        $sql = "select p.idPregunta as idPregunta FROM pregunta as p join usuario as u on p.idNivel = u.idNivel where u.idUsuario = $idUsuario
and p.idPregunta not in (select distinct ptp.idPregunta from usuario_juega_partida ujp join partida_tiene_pregunta ptp
on ujp.idPartida = ptp.idPartida where ujp.idUsuario = $idUsuario) order by rand() limit $cantidad;";
        return $this->conexion->query($sql);
    }

    public function getIdsPreguntasSinResponder($cantidad){
        $idUsuario = (INT)$_SESSION['usuarioLogueado']['idUsuario'];
        $sql = "select p.idpregunta as idPregunta from pregunta as p where p.idPregunta not in (select distinct ptp.idPregunta
from usuario_juega_partida as ujp join partida_tiene_pregunta as ptp on ujp.idPartida = ptp.idPartida
where ujp.idUsuario = $idUsuario) order by rand() limit $cantidad;";
        return $this->conexion->query($sql);
    }

    public function getIdsPreguntasCualesquiera($cantidad){
        $sql = "select idPregunta as idPregunta from pregunta order by rand() limit $cantidad;";
        return $this->conexion->query($sql);
    }

    public function getPreguntaOpcionesCategoriaId($idPregunta): array{
        $resultado = $this->getPreguntaRespuestaCategoriaId($idPregunta);
        $respuestasIncorrectas = $this->getRespuestasIncorrectas($idPregunta);
        $opcionesMezcladas = $this->mezclarOpciones($resultado[0]['respuestaCorrecta'], array_column($respuestasIncorrectas, 'respuestaIncorrecta'));
        return [
            'enunciado' => $resultado[0]['enunciado'],
            'opciones'  => $opcionesMezcladas,
            'categoria' => $resultado[0]['descripcion'],
            'idPregunta' => $resultado[0]['idPregunta']
        ];
    }


    public function getPreguntaRespuestaCategoriaId($idPregunta){
        $sql = "select p.idPregunta, p.enunciado, p.respuestaCorrecta, c.descripcion from pregunta as p join categoria as c
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
        $pregunta = $this->getPreguntaOpcionesCategoriaId((INT)$_SESSION['idsPreguntas'][(INT)$_SESSION['preguntaEnCurso']]);
        $this->setPreguntaEnPartida((INT)$_SESSION['idsPreguntas'][(INT)$_SESSION['preguntaEnCurso']]);
        $this->actualizarContadoresEntregaPreguntas($pregunta['idPregunta']);
        return ['siguientePaso' => 'siguientePregunta',
            'pregunta'      => $pregunta];
    }

    public function finalizarPartida($resultadoFinal, $idPreguntaEnCurso): array{
        $idPartida = (INT)$_SESSION['idPartidaEnCurso'];
        $idUsuario = $_SESSION['usuarioLogueado']['idUsuario'];
        if ($resultadoFinal) {
            $this->actualizarResultadoPreguntaEnPartida($idPreguntaEnCurso, $idPartida, 'Correcta');
            $this->actualizarContadoresAciertos($idPreguntaEnCurso, $idUsuario);
        } else {
            $this->actualizarResultadoPreguntaEnPartida($idPreguntaEnCurso, $idPartida, 'Incorrecta');
        }
        $puntaje = $this->calcularPuntajePartida($idPartida);
        $this->actualizarPuntajePartidaUsuario($idPartida, $puntaje);
        $this->actualizarDuracionPartida($idPartida);
        $this->actualizarNivelUsuario();
        $this->unsetearVariablesSessionPartida();
        if($resultadoFinal){
            return ['siguientePaso' => 'finalizar',
                'victoria' => true,
                'puntaje' => $puntaje];
        }else {
            return [
                'siguientePaso' => 'finalizar',
                'victoria' => false,
                'puntaje' => $puntaje];
        }
    }

    public function actualizarContadoresAciertos($idPregunta, $idUsuario){
        $sqlPregunta = "update pregunta set cantidadAciertos = (cantidadAciertos + 1) where idPregunta = $idPregunta;";
        $this->conexion->query($sqlPregunta);
        $sqlUsuario = "update usuario set cantidadAciertos = (cantidadAciertos + 1) where idUsuario = $idUsuario;";
        $this->conexion->query($sqlUsuario);
    }

    public function actualizarContadoresEntregaPreguntas($idPregunta){
        $idUsuario = $_SESSION['usuarioLogueado']['idUsuario'];
        $sqlPregunta = "update pregunta set cantidadApariciones = (cantidadApariciones + 1) where idPregunta = $idPregunta;";
        $this->conexion->query($sqlPregunta);
        $sqlUsuario = "update usuario set cantidadPreguntas = (cantidadPreguntas + 1) where idUsuario = $idUsuario;";
        $this->conexion->query($sqlUsuario);
    }

    public function actualizarDuracionPartida($idPartida){
        $sql1 = "select fechaPartida from partida where idPartida = $idPartida";
        $resultado = $this->conexion->query($sql1);
        $tz = new DateTimeZone('America/Argentina/Buenos_Aires');
        $fechaCreacionPartida = new DateTime($resultado[0]['fechaPartida'], $tz);
        $ahora = new DateTime('now', $tz);
        $diferencia = $ahora->diff($fechaCreacionPartida);
        $totalHoras = $diferencia->days * 24 + $diferencia->h;
        // Limitar la duración a 23:59:59 (si es necesario) para no romper la base de datos
        if($diferencia->days > 0 || $totalHoras >= 24){
            $duracionPartidaFormateada = '23:59:59';
        } else {
            $duracionPartidaFormateada = sprintf('%02d:%02d:%02d',
                $totalHoras,
                $diferencia->i, // Minutos
                $diferencia->s  // Segundos
            );
        }
        $sql2 = "update partida set duracionPartida = '$duracionPartidaFormateada' where idPartida = $idPartida";
        $this->conexion->query($sql2);
    }

    public function unsetearVariablesSessionPartida(){
        unset($_SESSION['preguntaEnCurso']);
        unset($_SESSION['idPartidaEnCurso']);
        unset($_SESSION['idsPreguntas']);
        unset($_SESSION['cantidadPreguntas']);
        unset($_SESSION['posicionRespuestaCorrecta']);
    }

}
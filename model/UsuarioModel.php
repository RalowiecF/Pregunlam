<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once('vendor/autoload.php');

class UsuarioModel
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getUserWith($nombreUsuario, $contrasenia)
    {
        $sql = "SELECT 
                u.idUsuario,u.nombreUsuario,u.mail,u.anioNacimiento,u.fotoPerfil, u.nombre, u.apellido, u.cantidadTrampas, s.descripcion AS sexo, u.fotoPerfil, 
                t.descripcion AS tipoUsuario, u.fechaRegistro,  n.descripcion AS nivel, u.latitud, u.longitud, 
                u.ciudad, u.pais, e.descripcion AS entorno, u.puntaje FROM usuario AS u
            JOIN sexo AS s ON u.idSexo = s.idSexo
            JOIN tipousuario AS t ON u.idTipoUsuario = t.idTipoUsuario
            JOIN nivel AS n ON u.idNivel = n.idNivel
            LEFT JOIN entorno AS e ON u.idEntorno = e.idEntorno
            WHERE u.nombreUsuario = ? AND u.contrasenia = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss", $nombreUsuario, $contrasenia);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            return $this->validarEditorYAdmin($usuario);
        }
        return [];
    }

    public function nuevo($nombreUsuario, $mail, $nombre, $apellido, $anioNacimiento, $sexo, $contrasenia, $fileFotoPerfil, $latitud, $longitud)
    {
        if ($this->verificarNombreUsuarioDuplicado($nombreUsuario) && $this->verificarMailDuplicado($mail)) {
            $idSexo = $this->obtenerIdSexo($sexo);
            $latitud = (float)$latitud;
            $longitud = (float)$longitud;
            $ciudadPais = $this->obtenerCiudadPais($latitud, $longitud);
            $ciudad = $ciudadPais['ciudad'];
            $pais = $ciudadPais['pais'];
            $fotoPerfil = $this->guardarFotoPerfil($fileFotoPerfil, $nombreUsuario);
            $tokenVerificacion = bin2hex(random_bytes(32));
            $sql = "INSERT INTO usuario 
(nombreUsuario, mail, nombre, apellido, anioNacimiento, idSexo, contrasenia, fotoPerfil, latitud, longitud, ciudad, pais, tokenVerificacion)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conexion->prepare($sql);

            $stmt->bind_param(
                "ssssiissddsss",
                $nombreUsuario,  // s
                $mail,           // s
                $nombre,         // s
                $apellido,       // s
                $anioNacimiento, // i
                $idSexo,         // i
                $contrasenia,    // s
                $fotoPerfil,     // s
                $latitud, // d
                $longitud,// d
                $ciudad,         // s
                $pais,           // s
                $tokenVerificacion // s
            );
            $stmt->execute();
            $this->enviarMailVerificacion($mail, $nombreUsuario, $tokenVerificacion);
            return true;
        } else return false;
    }

    public function getRanking(){
        $sql = "SELECT u.idUsuario, u.nombreUsuario, u.puntaje, MAX(p.fechaPartida) AS fechaUltimaPartida FROM usuario u 
    LEFT JOIN usuario_juega_partida ujp ON u.idUsuario = ujp.idUsuario
    LEFT JOIN partida p ON ujp.idPartida = p.idPartida GROUP BY u.idUsuario, u.nombreUsuario, u.puntaje ORDER BY u.puntaje desc;";
        return $this->conexion->query($sql);
    }

    public function getSexoList(){
        $sql = "SELECT descripcion FROM sexo";
        return $result = $this->conexion->query($sql);
    }

    public function verificarNombreUsuarioDuplicado($nombreUsuario,$idUsuario = null): bool
    {
        if ($idUsuario === null) {
            $sql = "SELECT * FROM usuario WHERE nombreUsuario = ?";
            $stmt = $this->conexion->prepare($sql);
            if (!$stmt) {
                $_SESSION['error'] = "Error al preparar la consulta.";
                return false;
            }
            $stmt->bind_param("s", $nombreUsuario);
        } else {
            $sql = "SELECT * FROM usuario WHERE nombreUsuario = ? AND idUsuario != ?";
            $stmt = $this->conexion->prepare($sql);
            if (!$stmt) {
                $_SESSION['error'] = "Error al preparar la consulta.";
                return false;
            }
            $stmt->bind_param("si", $nombreUsuario, $idUsuario);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            $_SESSION['error'] = "Este usuario ya existe";
            return false;
        }
        return true;
    }

    public function verificarMailDuplicado($mail,$idUsuario = null): bool
    {
        if ($idUsuario === null) {
            $sql = "SELECT * FROM usuario WHERE mail = ?";
            $stmt = $this->conexion->prepare($sql);
            if (!$stmt) {
                $_SESSION['error'] = "Error al preparar la consulta.";
                return false;
            }
            $stmt->bind_param("s", $mail);
        } else {
            $sql = "SELECT * FROM usuario WHERE mail = ? AND idUsuario != ?";
            $stmt = $this->conexion->prepare($sql);
            if (!$stmt) {
                $_SESSION['error'] = "Error al preparar la consulta.";
                return false;
            }
            $stmt->bind_param("si", $mail, $idUsuario);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            $_SESSION['error'] = "Este mail ya existe";
            return false;
        }
        return true;
    }

    function obtenerIdSexo($sexo): int
    {
        $sql = "SELECT idSexo FROM sexo WHERE descripcion = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $sexo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();

        if ($fila) {
            return (int)$fila['idSexo'];
        } else {
            $sql = "SELECT idSexo FROM sexo WHERE descripcion = 'Indefinido'";
            $resultado = $this->conexion->query($sql);
            $fila = $resultado->fetch_assoc();
            return (int)$fila['idSexo'];
        }
    }

    function obtenerCiudadPais($lat, $lng): array
    {
        $latlng = urlencode($lat . ',' . $lng);
        $url = "https://nominatim.openstreetmap.org/reverse?lat=$lat&lon=$lng&format=json&accept-language=es";

        $opts = [
            "http" => [
                "header" => "User-Agent: MiAplicacion/1.0 (miemail@example.com)\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        $response = file_get_contents($url, false, $context);

        $ciudad = '';
        $pais = '';

        if ($response !== false) {
            $data = json_decode($response, true);
            $ciudad = $data['address']['city'] ?? $data['address']['town'] ?? $data['address']['village'] ?? '';
            $pais = $data['address']['country'] ?? '';
        }

        // Acotar a 50 caracteres por seguridad
        $ciudad = substr($ciudad, 0, 50);
        $pais = substr($pais, 0, 50);
        return ['ciudad' => $ciudad, 'pais' => $pais];
    }

    public function guardarFotoPerfil($fileFotoPerfil, $nombreUsuario)
    {
        $fotoPerfil = 'imagenes/avatares/' . $nombreUsuario . '.png';
        move_uploaded_file($fileFotoPerfil["tmp_name"], $fotoPerfil);
        return $fotoPerfil;
    }

    public function enviarMailVerificacion($mailDestinatario, $nombreUsuario, $tokenVerificacion)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = 'dd8e7f5372862d';
            $mail->Password = 'eee56449f15bd6';

            $mail->setFrom('no-reply@pregunlam.com', 'PregUNLaM');
            $mail->addAddress($mailDestinatario, $nombreUsuario);

            $mail->isHTML(true);
            $mail->Subject = 'Verificá tu cuenta.';

            $mail->Body = 'Hola ' . htmlspecialchars($nombreUsuario) . ',<br><br>
            Gracias por registrarte. Por favor hacé clic en el siguiente enlace para verificar tu cuenta:<br><br>
            <a href="http://localhost/PregUNLaM/usuario/verificartoken?token=' . urlencode($tokenVerificacion) . '">Verificar cuenta</a>';

            // Para debug
            // $mail->SMTPDebug = 2;
            // $mail->Debugoutput = 'html';

            $mail->send();
            return true;

        } catch (Exception $e) {
            echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
            return false;
        }
    }

    public function validarEditorYAdmin($usuario)
    {
        if ($usuario['tipoUsuario'] == "Administrador") {
            $usuario['isAdmin'] = true;
        } elseif ($usuario['tipoUsuario'] == "Editor") {
            $usuario['isEditor'] = true;
        }
        return $usuario;
    }

    public function actualizar($idUsuario, $nombreUsuario, $mail, $nombre, $apellido, $anioNacimiento, $sexo, $contrasenia, $fileFotoPerfil, $latitud, $longitud)
    {
        if ($this->verificarNombreUsuarioDuplicado($nombreUsuario,$idUsuario) && $this->verificarMailDuplicado($mail,$idUsuario)) {
            $idSexo = $this->obtenerIdSexo($sexo);
            $latitud = (float)$latitud;
            $longitud = (float)$longitud;
            $ciudadPais = $this->obtenerCiudadPais($latitud, $longitud);
            $ciudad = $ciudadPais['ciudad'];
            $pais = $ciudadPais['pais'];
            $fotoPerfil = $this->guardarFotoPerfil($fileFotoPerfil, $nombreUsuario);

            $sql = "UPDATE usuario SET 
        nombreUsuario = ?, 
        mail = ?, 
        nombre = ?, 
        apellido = ?, 
        anioNacimiento = ?, 
        idSexo = ?, 
        contrasenia = ?, 
        fotoPerfil = ?, 
        latitud = ?, 
        longitud = ?, 
        ciudad = ?, 
        pais = ? 
        WHERE idUsuario = ?";

            $stmt = $this->conexion->prepare($sql);

            $stmt->bind_param(
                "sssssiissddsi",
                $nombreUsuario,   // s
                $mail,            // s
                $nombre,          // s
                $apellido,        // s
                $anioNacimiento,  // i
                $idSexo,          // i
                $contrasenia,     // s
                $fotoPerfil,      // s
                $latitud,         // d
                $longitud,        // d
                $ciudad,          // s
                $pais,            // s
                $idUsuario        // i
            );

            if (!$stmt->execute()) {
                $_SESSION['error'] = "Error al actualizar: " . $stmt->error;
                return false;
            }

            return true;
        }
    }

    public function getById($idUsuario){
        $sql = "SELECT 
                u.fotoPerfil, 
                u.nombreUsuario,
                u.pais,
                u.latitud, 
                u.longitud, 
                COUNT(ujp.idPartida) AS partidas
            FROM usuario AS u
            LEFT JOIN usuario_juega_partida ujp ON u.idUsuario = ujp.idUsuario
            WHERE u.idUsuario = ?
            GROUP BY u.idUsuario, u.fotoPerfil, u.nombreUsuario, u.latitud, u.longitud";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        }
        return null;
    }

}
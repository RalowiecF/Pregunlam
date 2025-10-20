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

    public function getUserWith($nombreUsuario, $contrasenia){
        $sql = "SELECT nombreUsuario, nombre, apellido, cantidadTrampas, idSexo, fotoPerfil, 
       idTipoUsuario, fechaRegistro, idNivel, latitud, longitud, ciudad, pais FROM usuario WHERE nombreUsuario = '$nombreUsuario' AND contrasenia = '$contrasenia'";
        $result = $this->conexion->query($sql);
        return $result ?? [];}

    public function nuevo($nombreUsuario, $mail, $nombre, $apellido, $anioNacimiento, $sexo, $contrasenia, $fileFotoPerfil, $latitud, $longitud) {
        if($this->verificarNombreUsuarioDuplicado($nombreUsuario) && $this->verificarMailDuplicado($mail)){
        $idSexo = $this->obtenerIdSexo($sexo);
        $latitud = (float) $latitud;
        $longitud = (float) $longitud;
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
                "ssssiissddsss", // <- "d" para latitud y longitud
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

    public function verificarNombreUsuarioDuplicado($nombreUsuario): bool {
        $sql = "SELECT * FROM usuario WHERE nombreUsuario = ?";
        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            $_SESSION['error'] = "Error al preparar la consulta.";
            return false;
        }

        // 's' indica que el parámetro es de tipo string
        $stmt->bind_param("s", $nombreUsuario);
        $stmt->execute();

        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            $_SESSION['error'] = "Este usuario ya existe";
            return false;
        }
        return true;
    }

    public function verificarMailDuplicado($mail): bool {
        $sql = "SELECT * FROM usuario WHERE mail = ?";
        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            $_SESSION['error'] = "Error al preparar la consulta.";
            return false;
        }

        $stmt->bind_param("s", $mail);
        $stmt->execute();

        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            $_SESSION['error'] = "Este correo electronico ya existe";
            return false;
        }
        return true;
    }


    function obtenerIdSexo($sexo): int {
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


    function obtenerCiudadPais($lat, $lng): array{
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

    public function guardarFotoPerfil($fileFotoPerfil, $nombreUsuario){
        $fotoPerfil =  'imagenes/avatares/' . $nombreUsuario . '.png';
        move_uploaded_file($fileFotoPerfil["tmp_name"], $fotoPerfil);
        return $fotoPerfil;
    }

    public function enviarMailVerificacion($mailDestinatario, $nombreUsuario, $tokenVerificacion){
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

}
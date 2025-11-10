    <?php
    require_once __DIR__ . '/../vendor/autoload.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class UsuarioModel
    {

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getUserWith($nombreUsuario, $contrasenia){
        $sql = "SELECT 
                u.idUsuario,u.nombreUsuario,u.mail,u.anioNacimiento,u.fotoPerfil, u.nombre, u.apellido, u.cantidadTrampas, s.descripcion AS sexo, u.fotoPerfil, 
                t.descripcion AS tipoUsuario, u.fechaRegistro, u.idNivel, n.descripcion AS nivel, u.latitud, u.longitud, 
                u.ciudad, u.pais, e.descripcion AS entorno, u.puntaje FROM usuario AS u
            JOIN sexo AS s ON u.idSexo = s.idSexo
            JOIN tipousuario AS t ON u.idTipoUsuario = t.idTipoUsuario
            JOIN nivel AS n ON u.idNivel = n.idNivel
            LEFT JOIN entorno AS e ON u.idEntorno = e.idEntorno
            WHERE u.nombreUsuario = ? AND u.contrasenia = ? AND mailVerificado = 1";

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
        $sql = "SELECT t1.idUsuario, t1.nombreUsuario, t1.puntaje, t1.duracionPartida, DATE_FORMAT(t1.fechaPartida, '%d/%m/%y %h:%m') AS fechaPartida FROM (
         SELECT
             u.idUsuario,
             u.nombreUsuario,
             p.puntaje,
             p.duracionPartida,
             p.fechaPartida,
             -- Asigna un número de fila para cada partida del usuario
             ROW_NUMBER() OVER (
                 PARTITION BY u.idUsuario
                 ORDER BY p.puntaje DESC, p.duracionPartida ASC
                 ) AS rn FROM
             usuario u JOIN usuario_juega_partida ujp ON u.idUsuario = ujp.idUsuario JOIN partida p ON ujp.idPartida = p.idPartida
         WHERE p.fechaPartida >= DATE_SUB(NOW(), INTERVAL 7 DAY)
     ) t1
-- Filtra para quedarte solo con la mejor partida (rn = 1) para cada usuario
WHERE t1.rn = 1
-- Ordena la lista final de usuarios según los requisitos
ORDER BY
    t1.puntaje DESC,
    t1.duracionPartida ASC limit 100;";
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

    public function guardarFotoPerfil($fileFotoPerfil, $nombreUsuario): string{
        $fotoPerfil =  $nombreUsuario . '.png';
        move_uploaded_file($fileFotoPerfil["tmp_name"], $fotoPerfil);
        return $fotoPerfil;
    }

    public function enviarMailVerificacion($mailDestinatario, $nombreUsuario, $tokenVerificacion): bool{
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.sendgrid.net';
            $mail->SMTPAuth = true;
            $mail->Username = 'apikey';
            $mail->Password = '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'; // tu API Key copiada
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('federico13111987@gmail.com', 'PregUNLaM');
            $mail->addAddress($mailDestinatario, $nombreUsuario);

            $mail->isHTML(true);
            $mail->Subject = 'Verifica tu cuenta.';

            $linkVerificacion = 'https://pregunlam.page.gd/usuario/verificarmail?token=' . urlencode($tokenVerificacion);

            $mail->Body = '
            <p>Hola ' . htmlspecialchars($nombreUsuario) . ',</p>
            <p>Gracias por registrarte en <strong>PregUNLaM</strong>. Por favor, hace clic en el siguiente enlace para verificar tu cuenta:</p>
            <p><a href="' . $linkVerificacion . '" target="_blank">Verificar cuenta</a></p>
            <p>Si vos no creaste esta cuenta, podes ignorar este mensaje.</p>
            <br>
            <p>Saludos,<br>El equipo de PregUNLaM</p>
        ';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
            return false;
        }
    }

    public function verificarEmailConToken($token){
        $sql = "update usuario set mailVerificado = 1, tokenVerificacion = null where tokenVerificacion = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $token);
        $resultado = $stmt->execute();
        if ($resultado) {
            $filasAfectadas = $stmt->affected_rows;
            return $filasAfectadas === 1;
        }
        return false;
    }

    public function validarEditorYAdmin($usuario) {
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

    public function getPerfil($idUsuario){
        $sql = "SELECT 
                idUsuario,
                nombreUsuario, 
                fotoPerfil, 
                pais, 
                latitud, 
                longitud, 
                puntaje
            FROM usuario WHERE idUsuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows === 0) {
            return false;
        }
        $usuario = $resultado->fetch_assoc();
        $partidas = $this->getPartidas($idUsuario, 15);

        if (isset($_SESSION["usuarioLogueado"])) {
            $usuarioLogueado = $_SESSION["usuarioLogueado"];
            $perfilPropio = ($idUsuario === (int)$usuarioLogueado['idUsuario']);
            $esContacto = $this->verificarSiEsContacto($idUsuario);
            return [
                'usuario' => $usuario,
                'usuarioLogueado' => $usuarioLogueado,
                'partidas' => $partidas,
                'perfilPropio' => $perfilPropio,
                'esContacto' => $esContacto];
        } else {
            return [
                'usuario' => $usuario,
                'partidas' => $partidas];
        }
    }

    public function verificarSiEsContacto($idUsuarioBuscado): bool {
        $idUsuarioLogueado = $_SESSION['usuarioLogueado']['idUsuario'];
        $sql = "SELECT idContacto 
            FROM usuario_contacto 
            WHERE idUsuario = ? AND idContacto = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $idUsuarioLogueado, $idUsuarioBuscado);
        $stmt->execute();
        $contacto = $stmt->get_result();
        return $contacto->num_rows > 0;
    }

    public function getPartidas($idUsuario, $cantidad){
        $sql = "SELECT p.puntaje, p.duracionPartida, 
                   DATE_FORMAT(p.fechaPartida, '%d/%m/%y %h:%i') AS fechaPartida 
            FROM partida AS p 
            JOIN usuario_juega_partida AS ujp ON p.idPartida = ujp.idPartida 
            WHERE ujp.idUsuario = ? 
            ORDER BY p.fechaPartida DESC 
            LIMIT ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $idUsuario, $cantidad);
        $stmt->execute();
        $partidas = $stmt->get_result();
        return $partidas->fetch_all(MYSQLI_ASSOC);
    }

    public function getContactos(){
        $idUsuarioLogueado = $_SESSION['usuarioLogueado']['idUsuario'];
        $sql = "SELECT t1.idUsuario, t1.nombreUsuario, t1.puntaje, t1.duracionPartida, IFNULL(DATE_FORMAT(t1.fechaPartida, '%d/%m/%y %H:%i'), 'Sin partidas') AS fechaPartida FROM (
    SELECT
        u.idUsuario,
        u.nombreUsuario,
        p.puntaje,
        p.duracionPartida,
        p.fechaPartida,
        -- Asigna un número de fila para cada partida del usuario
        ROW_NUMBER() OVER (
           PARTITION BY u.idUsuario
             ORDER BY p.puntaje DESC, p.duracionPartida ASC
            ) AS rn FROM
       usuario u LEFT JOIN usuario_juega_partida ujp ON u.idUsuario = ujp.idUsuario LEFT JOIN partida p ON ujp.idPartida = p.idPartida
                    where u.idUsuario in (select idContacto from usuario_contacto where idUsuario = $idUsuarioLogueado)
         ) t1
-- Filtra para quedarte solo con la mejor partida (rn = 1) para cada usuario
WHERE t1.rn = 1
-- Ordena la lista final de usuarios según los requisitos
ORDER BY
    t1.nombreUsuario";
        return $this->conexion->query($sql);
    }

    public  function agregarContacto($idUsuario): bool{
        $idUsuarioLogueado = (INT)$_SESSION["usuarioLogueado"]['idUsuario'];
        if ($idUsuarioLogueado === $idUsuario || $this->verificarSiEsContacto($idUsuario) || $idUsuario === 0) {
            return false;
        } else {
            $sql = "INSERT INTO usuario_contacto (idUsuario, idContacto) VALUES (?, ?)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("ii", $idUsuarioLogueado, $idUsuario);
            $stmt->execute();
            return true;
        }
    }

    public function eliminarContacto($idUsuario): bool{
        $idUsuarioLogueado = $_SESSION['usuarioLogueado']['idUsuario'];
        if ($idUsuarioLogueado === $idUsuario || !($this->verificarSiEsContacto($idUsuario)) || $idUsuario === 0) {
            return false;
        } else {
            $sql = "DELETE FROM `usuario_contacto` WHERE idUsuario = ? and idContacto = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("ii", $idUsuarioLogueado, $idUsuario);
            $stmt->execute();
            return true;
        }
    }

    public function ruletaTrampas($resultado){
        if ($this->verificarDisponibilidadRuleta()) {
            if ($resultado === 0) {
                return ['ruletaHabilitada' => true,
                    'mensaje' => "Podés intentarlo nuevamente",
                    'usuarioLogueado' => $_SESSION['usuarioLogueado'],];
            } else {
                $idUsuario = $_SESSION['usuarioLogueado']['idUsuario'];
                $sql = "update usuario set cantidadTrampas = (usuario.cantidadTrampas + ?) where idUsuario = ?;";
                $stmt = $this->conexion->prepare($sql);
                $stmt->bind_param("ii", $resultado, $idUsuario);
                $stmt->execute();
                $sql2 = "update usuario set ultimoUsoRuleta = now() where idUsuario = $idUsuario";
                $this->conexion->query($sql2);
                $_SESSION['usuarioLogueado']['cantidadTrampas'] += $resultado;
                $mensaje = "Felicidades! Sumaste a tus trampas " . $resultado . " más.";
                return ['ruletaHabilitada' => false,
                    'mensaje' => $mensaje,
                    'usuarioLogueado' => $_SESSION['usuarioLogueado'],];
            }
        } else {
            return ['ruletaHabilitada' => false,
                'mensaje' => "Ya usaste la ruleta hoy. Volve a usarla mañana.",
                'usuarioLogueado' => $_SESSION['usuarioLogueado'],];
        }
    }

    public function verificarDisponibilidadRuleta(): bool {
        $idUsuario = (int) $_SESSION['usuarioLogueado']['idUsuario'];
        $sql = "SELECT ultimoUsoRuleta FROM usuario WHERE idUsuario = $idUsuario";
        $result = $this->conexion->query($sql);
        $tz = new DateTimeZone('America/Argentina/Buenos_Aires');

        $fechaUltimoUsoSinFormato = $result[0]['ultimoUsoRuleta'] ?? null;
        if (!$fechaUltimoUsoSinFormato) {
            return true;
        }
        $fechaUltimoUso = (new DateTime($fechaUltimoUsoSinFormato, $tz))->format('Y-m-d');
        $hoy = (new DateTime('now', $tz))->format('Y-m-d');

        return $hoy !== $fechaUltimoUso;
    }

    public function getByNombreusuario($nombreUsuario){
        $nombreUsuarioBusqueda = "%" . $nombreUsuario . "%";
        $sql = "SELECT t1.idUsuario, t1.nombreUsuario, t1.puntaje, t1.duracionPartida, IFNULL(DATE_FORMAT(t1.fechaPartida, '%d/%m/%y %H:%i'), 'Sin partidas') AS fechaPartida FROM (
    SELECT
         u.idUsuario,
        u.nombreUsuario,
        p.puntaje,
        p.duracionPartida,
        p.fechaPartida,
        -- Asigna un número de fila para cada partida del usuario
        ROW_NUMBER() OVER (
            PARTITION BY u.idUsuario
            ORDER BY p.puntaje DESC, p.duracionPartida ASC
            ) AS rn FROM
        usuario u LEFT JOIN usuario_juega_partida ujp ON u.idUsuario = ujp.idUsuario LEFT JOIN partida p ON ujp.idPartida = p.idPartida
        where u.nombreUsuario like ?
) t1
-- Filtra para quedarte solo con la mejor partida (rn = 1) para cada usuario
WHERE t1.rn = 1
-- Ordena la lista final de usuarios según los requisitos
ORDER BY
    t1.nombreUsuario";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $nombreUsuarioBusqueda);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
}


}
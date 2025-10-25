<?php

class UsuarioController
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
        $this->lobby();
    }

    public function login(){
        if (!isset($_POST["nombreUsuario"]) || !isset($_POST["contrasenia"])) {
            $this->renderer->render("login");
            exit();
        }
        $resultado = $this->model->getUserWith($_POST["nombreUsuario"], $_POST["contrasenia"]);

        if (sizeof($resultado) > 0) {
            $_SESSION["usuarioLogueado"] = $resultado;
            $this->renderer->render("lobby", ["usuarioLogueado" => $_SESSION["usuarioLogueado"]]);
            exit();
        } else {
            $this->renderer->render("login", ["error" => "Usuario o clave incorrecta"]);
            exit();
        }
    }

    public function registroUsuarioForm()
    {
        $this->renderer->render("registroUsuario");
    }

    public function lobby()
    {
        if(isset($_SESSION["usuarioLogueado"])) {
            $this->renderer->render("lobby", ["usuarioLogueado" => $_SESSION["usuarioLogueado"]]);
        }else $this->renderer->render("lobby");
    }

    public function nuevo()
    {
        $this->validarTexto($_POST['nombreUsuario'], 1, 50);
        $this->validarMail($_POST['mail']);
        $this->validarTexto($_POST['nombre'], 1, 50);
        $this->validarTexto($_POST['apellido'], 1, 50);
        $this->validarAnioNacimiento($_POST['anioNacimiento']);
        $sexo = $this->validarSexo($_POST['sexo']);
        $this->validarTexto($_POST['contrasenia'], 1, 50);
        $this->validarImagen($_FILES['fotoPerfil']);
        $this->validarCoordenadas($_POST['latitud'], $_POST['longitud']);

        if ($this->model->nuevo($_POST["nombreUsuario"], $_POST["mail"], $_POST["nombre"], $_POST["apellido"],
            $_POST["anioNacimiento"], $sexo, $_POST["contrasenia"], $_FILES['fotoPerfil'], $_POST["latitud"], $_POST["longitud"])){
            $this->redirectToIndex();
        }else{
            $error = $_SESSION["error"];
            unset($_SESSION["error"]);
            $this->renderer->render("registroUsuario", ["error" => $error]);
            exit();
        }
    }

    public function logout()
    {
        session_destroy();
        unset($this->usuarioLogueado);
        $this->redirectToIndex();
    }

    public function ranking()
    {
        if(isset($_SESSION["usuarioLogueado"])) {
        $this->renderer->render("tablaUsuarios", ["usuarios" => $this->model->getRanking(), "tabla" => "RANKING", "usuarioLogueado" => $_SESSION["usuarioLogueado"]]);
        }else $this->renderer->render("tablaUsuario", ["usuarios" => $this->model->getRanking(), "tabla" => "RANKING"]);
    }

    public function redirectToIndex()
    {
        header("Location: " . BASE_URL);
        exit;
    }

    public function validarTexto($texto, $minLongitud, $maxLongitud): bool {
        if (!isset($texto)) {
            $this->renderer->render("registroUsuario", ["error" => "Texto vacío"]);
            exit();
        }

        $texto = trim($texto);
        if ($texto === '') {
            $this->renderer->render("registroUsuario", ["error" => "Ingresó solo espacios"]);
            exit();
        }

        if (strlen($texto) > $maxLongitud) {
            $this->renderer->render("registroUsuario", ["error" => "Exceso de caracteres."]);
            exit();
        }

        if (strlen($texto) < $minLongitud) {
            $this->renderer->render("registroUsuario", ["error" => "No escribió los caracteres suficientes."]);
            exit();
        }

        return true;
    }

    function validarMail($email): bool {
        if (!isset($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->renderer->render("registroUsuario", ["error" => "No es una dirección de correo electrónico valida."]);
            exit();
        }
        return true;
    }

    function validarAnioNacimiento($anioNacimiento): bool {
        if (!isset($anioNacimiento) || !is_numeric($anioNacimiento)) {
            $this->renderer->render("registroUsuario", ["error" => "No ingresó un número valido."]);
            exit();
        }

        $anioActual = (int) date("Y");

        if ($anioNacimiento <= 1900 || $anioNacimiento >= $anioActual){
            $this->renderer->render("registroUsuario", ["error" => "No ingresó un año valido."]);
            exit();
        }
        return true;
    }

    public function validarSexo($sexo): string {
        $sexosValidos = $this->model->getSexoList();
        if (in_array($sexo, $sexosValidos[0])) {
            return $sexo;
        } else return 'Indefinido';
    }

    function validarImagen($archivo): bool {
        if (!isset($archivo) || $archivo['error'] !== UPLOAD_ERR_OK) {
            $this->renderer->render("registroUsuario", ["error" => "No se recibió ninguna imagen o hubo un error al subirla."]);
            exit();
        }

        $info = getimagesize($archivo['tmp_name']);
        if ($info === false) {
            $this->renderer->render("registroUsuario", ["error" => "El archivo no es una imagen válida."]);
            exit();
        }

        $formatosPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($info['mime'], $formatosPermitidos)) {
            $this->renderer->render("registroUsuario", ["error" => "Formato de imagen no permitido. Solo JPG, PNG, GIF o WEBP."]);
            exit();
        }

        $tamanioMaximo = 5 * 1024 * 1024; // 5 MB
        if ($archivo['size'] > $tamanioMaximo) {
            $this->renderer->render("registroUsuario", ["error" => "La imagen excede el tamaño máximo permitido (5 MB)."]);
            exit();
        }

        return true;
    }

    public function validarCoordenadas($latitud, $longitud): bool {
        if (!is_numeric($latitud) || !is_numeric($longitud)) {
            $this->renderer->render("registroUsuario", ["error" => "Coordenadas invalidas"]);
            exit();
        }
        return true;
    }

    public function editarPerfil(){
        if(!isset($_SESSION["usuarioLogueado"])){
            $this->redirectToIndex();
        }
        $this->renderer->render("registroUsuario", ["usuarioLogueado" => $_SESSION["usuarioLogueado"]]);
    }

    public function editarPerfilForm(){
        if(!isset($_SESSION["usuarioLogueado"])){
            $this->redirectToIndex();
        }
        $this->validarTexto($_POST['nombreUsuario'], 1, 50);
        $this->validarMail($_POST['mail']);
        $this->validarTexto($_POST['nombre'], 1, 50);
        $this->validarTexto($_POST['apellido'], 1, 50);
        $this->validarAnioNacimiento($_POST['anioNacimiento']);
        $sexo = $this->validarSexo($_POST['sexo']);
        $this->validarTexto($_POST['contrasenia'], 1, 50);
//        $this->validarImagen($_FILES['fotoPerfil']);
        $this->validarCoordenadas($_POST['latitud'], $_POST['longitud']);

        $idUsuario = $_SESSION["usuarioLogueado"]["idUsuario"];

        if ($this->model->actualizar(
            $idUsuario,
            $_POST["nombreUsuario"],
            $_POST["mail"],
            $_POST["nombre"],
            $_POST["apellido"],
            $_POST["anioNacimiento"],
            $sexo,
            $_POST["contrasenia"],
            $_FILES['fotoPerfil'],
            $_POST["latitud"],
            $_POST["longitud"]
        )) {
            $_SESSION["usuarioLogueado"] = $this->model->getUserWith($_POST["nombreUsuario"], $_POST["contrasenia"]);
            $this->redirectToIndex();
        } else {
            $error = $_SESSION["error"];
            unset($_SESSION["error"]);
            $this->renderer->render("registroUsuario", ["error" => $error, "usuarioLogueado" => $_SESSION["usuarioLogueado"]]);
            exit();
        }
    }

    public function verPerfil($idUsuario){
        $usuario = $this->model->getById($idUsuario);
        if(!$usuario){
            $this->renderer->render("error", ["mensaje" => "El usuario no existe."]);
            exit();
        }
        if(isset($_SESSION["usuarioLogueado"])) {
            $this->renderer->render("verPerfilUsuario", ["usuario" => $usuario, "usuarioLogueado" => $_SESSION["usuarioLogueado"]]);
        }else{
            $this->renderer->render("verPerfilUsuario", ["usuario" => $usuario]);
        }
    }

}
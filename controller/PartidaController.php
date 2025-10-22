<?php

class PartidaController
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

    public function registroUsuarioForm()
    {
        $this->renderer->render("registroUsuario");
    }

    public function login(){
        $resultado = $this->model->getUserWith($_POST["nombreUsuario"], $_POST["contrasenia"]);

        if (sizeof($resultado) > 0) {
            $_SESSION["usuarioLogueado"] = $resultado[0];
            $this->renderer->render("lobby", ["usuarioLogueado" => $_SESSION["usuarioLogueado"]]);
            exit();
        } else {
            $this->renderer->render("login", ["error" => "Usuario o clave incorrecta"]);
            exit();
        }
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

    public function redirectToIndex()
    {
        header("Location: " . BASE_URL);
        exit;
    }

}
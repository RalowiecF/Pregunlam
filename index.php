<?php
session_start();

include("helper/ConfigFactory.php");
include("helper/Permisos.php");

$controller = $_GET['controller'] ?? '';
$method = $_GET['method'] ?? '';
$tipoUsuario = $_SESSION['usuarioLogueado']['tipoUsuario'] ?? 'Anonimo';
$verificadorPermisos = new Permisos();
if (!$verificadorPermisos->verificarPermiso($tipoUsuario, $controller, $method)) {
    header("Location: " . BASE_URL);
    exit;}

$configFactory = new ConfigFactory();
$router = $configFactory->get("router");

$router->executeController($controller, $method);
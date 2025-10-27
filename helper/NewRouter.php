<?php

class NewRouter
{
    private $configFactory;
    private $defaultController;
    private $defaultMethod;

    public function __construct($configFactory, $defaultController,$defaultMethod)
    {

        $this->configFactory = $configFactory;
        $this->defaultController = $defaultController;
        $this->defaultMethod = $defaultMethod;
    }

    public function executeController($controllerParam, $methodParam)
    {
        $controller = $this->getControllerFrom($controllerParam);
        $this->executeMethodFromController($controller, $methodParam);
    }

    private function getControllerFrom($controllerName)
    {
        $controllerName = $this->getControllerName($controllerName);
        $controller = $this->configFactory->get($controllerName);

        if ($controller == null) {
            header("location: /");
            exit;
        }

        return $controller;
    }

    private function executeMethodFromController($controller, $methodName)
    {
//        // Extraer idUsuario si está en la URL
//        $idUsuario = null;
//        if (preg_match('/verPerfil\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
//            $idUsuario = $matches[1];
//        }
//
//        // Extraer idPregunta si está en la URL
//        $idPregunta = null;
//        if (preg_match('/eliminarPregunta\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
//            $idPregunta = $matches[1];
//        }
//
//        // Si el método es verPerfil y hay idUsuario, pásalo como argumento
//        if ($methodName === 'verPerfil' && $idUsuario !== null) {
//            call_user_func(
//                array($controller, $this->getMethodName($controller, $methodName)),
//                $idUsuario
//            );
//        }elseif ($methodName === 'eliminarPregunta' && $idPregunta !== null){
//            call_user_func(
//                array($controller, $this->getMethodName($controller, $methodName)),
//                $idPregunta
//            );
//        } else {
//            call_user_func(
//                array($controller, $this->getMethodName($controller, $methodName))
//            );
//        }

        // Extraer partes de la URL
        $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        // Buscar el índice del método en la URL
        $methodIndex = array_search($methodName, $urlParts);

        $params = [];
        if ($methodIndex !== false) {
            // Extraer todos los parámetros después del método
            for ($i = $methodIndex + 1; $i < count($urlParts); $i++) {
                if (is_numeric($urlParts[$i])) {
                    $params[] = $urlParts[$i];
                }
            }
        }

        call_user_func_array(
            [ $controller, $this->getMethodName($controller, $methodName) ],
            $params
        );



    }

    public function getControllerName($controllerName)
    {
        return $controllerName ?
            ucfirst($controllerName) . 'Controller' :
            $this->defaultController;
    }

    public function getMethodName($controller, $methodName)
    {
        return method_exists($controller, $methodName) ? $methodName : $this->defaultMethod;
    }
}
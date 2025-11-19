<?php

class Permisos
{
private $permisos;

public function __construct(){
    $this->permisos = [
        'Anonimo' => [
            '' => [''],
            'usuario' => ['', 'login', 'registroUsuarioForm', 'nuevo', 'verificarmail','validarMailAjax', 'ranking', 'verPerfil', 'buscarUsuario'],
        ],
        'Jugador' => [
            '' => [''],
            'usuario' => ['', 'logout', 'registroUsuarioForm', 'ranking', 'verPerfil', 'editarPerfilForm','validarMailAjax' ,'editarPerfil', 'buscarUsuario', 'contactos', 'agregarContacto', 'eliminarContacto', 'comprarTrampas', 'ruletaTrampas'],
            'pregunta' => ['', 'registroPregunta','sugerirPreguntaNueva', 'listar', 'reportarPregunta','sugerirCategoriaNueva'],
            'partida' => ['', 'seleccionPartida', 'nueva', 'continuarPartida', 'verReglas'],
        ],
        'Editor' => [
            '' => [''],
            'usuario' => ['', 'logout', 'registroUsuarioForm', 'ranking', 'verPerfil','validarMailAjax' , 'editarPerfilForm', 'editarPerfil', 'buscarUsuario', 'contactos', 'agregarContacto', 'eliminarContacto'],
            'pregunta' => ['', 'registroPregunta','agregarPregunta', 'listar','listaPreguntas','eliminarPregunta','cambiarEstadoPregunta','editarPregunta','editarPreguntaForm','filtrarPreguntasSugeridas','filtrarPreguntasVigentes','aprobarPregunta','agregarCategoria','cambiarEstadoCategoria', 'reportarPregunta'],
            'partida' => ['', 'seleccionPartida', 'nueva', 'continuarPartida', 'verReglas'],
        ],
        'Administrador' => [
            '*' => ['*'] // acceso total
        ]
    ];
}

    function verificarPermiso($rol, $controller, $method) {
        $permisos = $this->permisos;
        if (!isset($permisos[$rol])) return false;

        // si el rol tiene acceso total
        if (isset($permisos[$rol]['*']) && in_array('*', $permisos[$rol]['*'])) {
            return true;
        }

        // si el controlador está permitido y contiene el método
        return isset($permisos[$rol][$controller]) &&
            in_array($method, $permisos[$rol][$controller]);
    }
}
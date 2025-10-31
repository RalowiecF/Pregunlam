<?php

class Permisos
{
private $permisos;

public function __construct(){
    $this->permisos = [
        'Anonimo' => [
            '' => [''],
            'usuario' => ['', 'login', 'registroUsuarioForm', 'nuevo', 'ranking'],
        ],
        'Jugador' => [
            '' => [''],
            'usuario' => ['', 'logout', 'registroUsuarioForm', 'nuevo', 'ranking'],
            'pregunta' => ['', 'registroPregunta', 'listar'],
            'partida' => ['', 'seleccionPartida', 'nueva', 'continuarPartida'],
        ],
        'Editor' => [
            '' => [''],
            'usuario' => ['', 'logout', 'registroUsuarioForm', 'nuevo', 'ranking'],
            'pregunta' => ['', 'registroPregunta', 'listar'],
            'partida' => ['', 'seleccionPartida', 'nueva', 'continuarPartida'],
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
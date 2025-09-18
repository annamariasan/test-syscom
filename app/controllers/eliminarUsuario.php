<?php
require_once __DIR__ . '/../../config/connection.php';
require_once __DIR__ . '/../models/usuarioModelo.php';
require_once __DIR__ . '/../../config/app.php';

$usuarioModel = new Usuario($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = (int)$_POST['usuario_id'];
    if ($usuarioModel->eliminarUsuario($usuario_id)) {
        header("Location: " . APP_SERVER . "app/views/content/usuarios.php");
        exit;
    } else {
        echo "Error al eliminar el usuario.";
    }
}

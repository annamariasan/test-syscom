<?php

require_once __DIR__ . '/../../config/connection.php';
require_once __DIR__ . '/../models/usuarioModelo.php';
require_once __DIR__ . '/../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $cargo  = (int) $_POST['cargo'];
    $fecha_ingreso = $_POST['fecha_ingreso'];

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        die("Correo inválido");
    }

    $usuarioModel = new Usuario($pdo);
    $registrado = $usuarioModel->registrar($nombre, $correo, $cargo, $fecha_ingreso);

    if ($registrado) {
        header("Location: " . APP_SERVER . "app/views/content/registro.php?success=1");
    } else {
        header("Location: " . APP_SERVER . "app/views/content/registro.php?error=1");
    }
    exit;
}


<?php
require_once __DIR__ . '/../../config/connection.php';
require_once __DIR__ . '/../models/usuarioModelo.php';
require_once __DIR__ . '/../../config/app.php';

$usuarioModel = new Usuario($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
    $usuario_id = (int)$_POST['usuario_id'];
    $archivo = $_FILES['archivo'];

    $carpetaDestino = __DIR__ . '../../uploads/contratos/';

    if (!is_dir($carpetaDestino)) {
        mkdir($carpetaDestino, 0755, true);
    }

    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $nombreArchivo = 'contrato_usuario_' . $usuario_id . '_' . time() . '.' . $extension;
    $rutaFinal = $carpetaDestino . $nombreArchivo;

    if (move_uploaded_file($archivo['tmp_name'], $rutaFinal)) {
        $rutaBD = 'app/uploads/contratos/' . $nombreArchivo;
        $actualizado = $usuarioModel->actualizarContrato($usuario_id, $rutaBD);

        if ($actualizado) {
            echo "Archivo subido correctamente.";
            echo "<p><a href='" . APP_SERVER . "app/views/content/usuarios.php'>⬅ Volver a la lista de usuarios</a></p>";
        } else {
            echo "Error al actualizar la base de datos.";
        }
    } else {
        echo "Error al subir el archivo.";
    }
} else {
    echo "No se recibió ningún archivo.";
}

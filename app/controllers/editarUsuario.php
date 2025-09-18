<?php
require_once __DIR__ . '/../../config/connection.php';
require_once __DIR__ . '/../models/usuarioModelo.php';
require_once __DIR__ . '/../../config/app.php';

$usuarioModel = new Usuario($pdo);

if (!isset($_GET['usuario_id'])) {
    die("Usuario no especificado");
}

$usuario_id = (int)$_GET['usuario_id'];
$usuario = $usuarioModel->obtenerPorId($usuario_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $id_rol = (int)$_POST['id_rol'];
    $fecha_ingreso = trim($_POST['fecha_ingreso']);
    
    if ($usuarioModel->actualizarUsuario($usuario_id, $nombre, $correo, $id_rol, $fecha_ingreso )) {
        header("Location: " . APP_SERVER . "app/views/content/usuarios.php");
        exit;
    } else {
        echo "<p style='color:red;'>Error al actualizar el usuario.</p>";
    }
}

$roles = $usuarioModel->obtenerRoles();
?>

<h2>Editar Usuario</h2>

<form action="" method="post" style="max-width:400px;">
    <input type="hidden" name="usuario_id" value="<?= $usuario['id'] ?>">

    <label for="nombre">Nombre:</label><br>
    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required style="width:100%;"><br><br>

    <label for="correo">Correo:</label><br>
    <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($usuario['correo_electronico']) ?>" required style="width:100%;"><br><br>

    <label for="id_rol">Cargo:</label><br>
    <select id="id_rol" name="id_rol" required style="width:100%;">
        <?php foreach ($roles as $rol): ?>
            <option value="<?= $rol['id'] ?>" <?= $rol['id'] == $usuario['id_rol'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($rol['nombre_cargo']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="fecha_ingreso">Fecha de Ingreso:</label><br>
    <input type="date" id="fecha_ingreso" name="fecha_ingreso" value="<?= htmlspecialchars($usuario['fecha_ingreso']) ?>" required style="width:100%;"><br><br>

    <button type="submit" style="padding:8px 16px; background-color:blue; color:white; border:none; cursor:pointer;">
        Guardar cambios
    </button>
    <a href="<?= APP_SERVER . 'app/views/content/usuarios.php' ?>" style="margin-left:10px;">Cancelar</a>
</form>

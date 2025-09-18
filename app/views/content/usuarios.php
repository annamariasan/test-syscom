<?php
require_once __DIR__ . '/../../../config/connection.php';
require_once __DIR__ . '/../../models/usuarioModelo.php';
require_once __DIR__ . '/../../../config/app.php';

$usuarioModel = new Usuario($pdo);
$usuarios = $usuarioModel->obtenerTodos();

foreach ($usuarios as &$usuario) {
    $usuario['dias_trabajados'] = $usuarioModel->diasHabiles($usuario['fecha_ingreso']);
}
unset($usuario);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: 0 auto; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<h1 style="text-align:center;">Usuarios Registrados</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Cargo</th>
            <th>Fecha Ingreso</th>
            <th>Días trabajados</th>
            <th>Contrato</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?= $usuario['id'] ?></td>
            <td><?= htmlspecialchars($usuario['nombre']) ?></td>
            <td><?= htmlspecialchars($usuario['correo_electronico']) ?></td>
            <td><?= htmlspecialchars($usuario['nombre_cargo']) ?></td>
            <td><?= htmlspecialchars($usuario['fecha_ingreso']) ?></td>
            <td><?= $usuario['dias_trabajados'] ?></td>
            <td>
                <form action="<?php echo APP_SERVER; ?>app/controllers/subirArchivo.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="usuario_id" value="<?= $usuario['id'] ?>">
                    <input type="file" name="archivo" required>
                    <button type="submit">Subir contrato</button>
                </form>
                <?php if (!empty($usuario['contrato'])): ?>
                <a href="<?= htmlspecialchars(APP_SERVER . $usuario['contrato']) ?>" target="_blank">
                    <button type="button" style="background-color: cornflowerblue">Ver contrato</button>
                </a>
                <?php endif; ?>
            </td>
            <td>
                <form action="<?php echo APP_SERVER; ?>app/controllers/editarUsuario.php" method="get" style="display:inline-block;">
                    <input type="hidden" name="usuario_id" value="<?= $usuario['id'] ?>">
                    <button type="submit" style="background-color:green; color:white;">Editar</button>
                </form>
                <form action="<?php echo APP_SERVER; ?>app/controllers/eliminarUsuario.php" method="post" style="display:inline-block;" onsubmit="return confirm('¿Seguro que quieres eliminar este usuario?');">
                    <input type="hidden" name="usuario_id" value="<?= $usuario['id'] ?>">
                    <button type="submit" style="background-color:red; color:white;">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p style="text-align:center;"><a href="../../../index.php">⬅ Volver al inicio</a></p>

</body>
</html>

<?php
require_once __DIR__ . '/config/app.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
</head>
<body>

<div class="container">
    <h1>Bienvenido a <?php echo APP_NAME; ?></h1>
    <p>Selecciona una opción:</p>

    <a href="<?php echo APP_SERVER; ?>app/views/content/registro.php" class="btn">Registrar Usuario</a>
    <a href="<?php echo APP_SERVER; ?>app/views/content/usuarios.php" class="btn">Ver Usuarios</a>
</div>

</body>
</html>

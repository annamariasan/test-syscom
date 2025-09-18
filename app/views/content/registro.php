<?php if (isset($_GET['success'])): ?>
    <p style="color:green;">Usuario registrado correctamente.</p>
<?php elseif (isset($_GET['error'])): ?>
    <p style="color:red;">Hubo un error al registrar el usuario.</p>
<?php endif; ?>

<?php
    require_once __DIR__ . '/../../../config/app.php';
?>

<form action="<?php echo APP_SERVER; ?>app/controllers/registroUsuario.php" method="POST">

    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" id="nombre" required>

    <label for="correo">Correo Electrónico:</label>
    <input type="email" name="correo" id="correo" required>

    <label for="cargo">Cargo:</label>
    <select name="cargo" id="cargo" required>
        <option value="1">Empleado</option>
        <option value="2">Jefe</option>
    </select>

    <label for="fecha_ingreso">Fecha de Ingreso:</label>
    <input type="date" name="fecha_ingreso" id="fecha_ingreso" required>

    <button type="submit">Registrar</button>
</form>
<p style="text-align:center;"><a href="../../../index.php">⬅ Volver al inicio</a></p>


<?php
require_once __DIR__ . '/../../config/connection.php';


class Usuario {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function registrar($nombre, $correo, $cargo, $fecha_ingreso) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO usuarios (nombre, correo_electronico, id_rol, fecha_ingreso) VALUES (:nombre, :correo, :cargo, :fecha_ingreso)");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':cargo', $cargo, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_ingreso', $fecha_ingreso);

            if (!$stmt->execute()) {
                $errorInfo = $stmt->errorInfo();
                echo "<pre> Error SQL: " . $errorInfo[2] . "</pre>";
                return false;
            }

            return true;
        } catch (PDOException $e) {
            echo "<pre> Excepción PDO: " . $e->getMessage() . "</pre>";
            return false;
        }
    }

    public function obtenerTodos() {
        try {
            $stmt = $this->pdo->query("SELECT u.*, r.nombre_cargo FROM usuarios u LEFT JOIN roles r ON u.id_rol = r.id ORDER BY u.id DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener usuarios: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerRoles() {
        try {
            $stmt = $this->pdo->query("SELECT id, nombre_cargo FROM roles ORDER BY nombre_cargo ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener roles: " . $e->getMessage());
            return [];
        }
    }

    public function eliminarUsuario($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar usuario: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarUsuario($id, $nombre, $correo, $id_rol, $fecha_ingreso) {
        try {
            $stmt = $this->pdo->prepare("UPDATE usuarios SET nombre = :nombre, correo_electronico = :correo, id_rol = :id_rol, fecha_ingreso = :fecha_ingreso WHERE id = :id");
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_ingreso', $fecha_ingreso, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar usuario: " . $e->getMessage());
            return false;
        }
    }

    private function obtenerFestivos($year) {
        $url = "https://api-colombia.com/api/v1/holiday/year/$year";
        $json = @file_get_contents($url);

        if ($json === false) {
            error_log("No se pudieron obtener festivos del año $year");
            return [];
        }

        $data = json_decode($json, true);
        $fechas = [];

        if (is_array($data)) {
            foreach ($data as $festivo) {
                if (isset($festivo['date'])) {
                    $fecha = (new DateTime($festivo['date']))->format('Y-m-d');
                    $fechas[] = $fecha;
                }
            }
        }

        return $fechas;
    }

    public function diasHabiles($fecha_ingreso) {
        $ingreso = new DateTime($fecha_ingreso);
        $hoy = new DateTime();

        if ($ingreso > $hoy) {
            return 0;
        }

        $festivos = array_merge(
            $this->obtenerFestivos((int)$ingreso->format('Y')),
            $this->obtenerFestivos((int)$hoy->format('Y'))
        );

        $dias = 0;
        $periodo = new DatePeriod($ingreso, new DateInterval('P1D'), $hoy);

        foreach ($periodo as $fecha) {
            $diaSemana = (int)$fecha->format('N');
            $fechaStr = $fecha->format('Y-m-d');

            if ($diaSemana < 6 && !in_array($fechaStr, $festivos)) {
                $dias++;
            }
        }

        return $dias;
    }

    public function actualizarContrato($id, $rutaArchivo) {
        try {
            $stmt = $this->pdo->prepare("UPDATE usuarios SET contrato = :ruta WHERE id = :id");
            $stmt->bindParam(':ruta', $rutaArchivo, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar contrato: " . $e->getMessage());
            return false;
        }
    }
}

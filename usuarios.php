<?php
include("db.php");

// Verificar conexión
if (!$conn) {
    die("Error de conexión a la base de datos");
}

// Eliminar usuario si se hace clic en "Eliminar"
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: usuarios.php");
    exit;
}

// Obtener usuarios de la base de datos
$resultado = $conn->query("SELECT id, usuario, rol FROM usuarios");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link href="https://fonts.googleapis.com/css2?family=Cardo&family=IM+Fell+English+SC&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cardo', serif;
            background: url('imagenes/vinol.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            color: #f5f5f5;
        }

        .container {
            background-color: rgba(20, 20, 20, 0.95);
            padding: 40px;
            max-width: 800px;
            margin: 60px auto;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.7);
            border: 2px solid #5b0e0e;
            text-align: center;
        }

        h1 {
            font-family: 'IM Fell English SC', serif;
            font-size: 32px;
            color: #e2c290;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.05);
            margin: 20px 0;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #555;
        }

        th {
            background-color: #1a1a1a;
            color: #e2c290;
            font-family: 'IM Fell English SC', serif;
            font-size: 18px;
        }

        tr:hover {
            background-color: rgba(91, 14, 14, 0.3);
        }

        .btn-editar,
        .btn-eliminar {
            background-color: #5b0e0e;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            font-family: 'IM Fell English SC', serif;
            transition: background 0.3s ease;
            margin: 0 5px;
        }

        .btn-editar:hover,
        .btn-eliminar:hover {
            background-color: #370808;
        }

        .btn-volver {
            display: inline-block;
            margin-top: 20px;
            background-color: #5b0e0e;
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-family: 'IM Fell English SC', serif;
            transition: background 0.3s ease;
        }

        .btn-volver:hover {
            background-color: #370808;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>👤 Usuarios Registrados</h1>

        <table border="1">
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $fila['id'] ?></td>
                    <td><?= htmlspecialchars($fila['usuario']) ?></td>
                    <td><?= htmlspecialchars($fila['rol']) ?></td>
                    <td>
                        <a href="editar_usuario.php?id=<?= $fila['id'] ?>" class="btn-editar">✏️ Editar</a>
                        <a href="usuarios.php?eliminar=<?= $fila['id'] ?>" class="btn-eliminar" onclick="return confirm('¿Seguro que quieres eliminar este usuario?');">🗑️ Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <a href="admin.php" class="btn-volver">⬅️ Volver al Panel</a>
    </div>
</body>
</html>
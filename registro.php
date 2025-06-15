<?php
include("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $clave = $_POST['contrasena'] ?? '';
    $rol = $_POST['rol'] ?? 'consultor'; // Captura el rol del formulario

    if (empty($usuario) || empty($clave)) {
        die("❌ Usuario y contraseña son obligatorios.");
    }

    // Verifica si el usuario existe
    $sql_check = "SELECT id FROM usuarios WHERE usuario = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $usuario);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        die("❌ El usuario ya existe.");
    }

    // Hash de la contraseña
    $clave_hash = password_hash($clave, PASSWORD_DEFAULT);

    // Registra al usuario
    $sql_insert = "INSERT INTO usuarios (usuario, contrasena, rol) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("sss", $usuario, $clave_hash, $rol);

    if ($stmt_insert->execute()) {
        header("Location: index.php?registro=exitoso");
        exit();
    } else {
        die("❌ Error al registrar: " . $conn->error);
    }

    $stmt_check->close();
    $stmt_insert->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <style>
        body {
            font-family: 'Cardo', serif;
            background: url('imagenes/vinof.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            color: #f5f5f5;
        }
        .login-container {
            background-color: rgba(20, 20, 20, 0.95);
            padding: 40px;
            max-width: 400px;
            margin: 100px auto;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.7);
            text-align: center;
            border: 2px solid #5b0e0e;
        }
        h2 {
            font-family: 'UnifrakturCook', cursive;
            color: #e2c290;
        }
        input, select {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            background-color: #222;
            color: #f5f5f5;
            border: 1px solid #444;
            border-radius: 8px;
        }
        button {
            background-color: #5b0e0e;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #370808;
        }
        a {
            color: #e2c290;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Registro de Usuario</h2>
        <form method="POST" action="registro.php">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <select name="rol" required>
                <option value="admin">Administrador</option>
                <option value="consultor" selected>Consultor</option>
            </select>
            <button type="submit">Registrar</button>
        </form>
        <p><a href="index.php">¿Ya tienes cuenta? Inicia sesión</a></p>
    </div>
</body>
</html>
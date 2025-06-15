<?php
include("db.php");


// Consulta para obtener los datos de la bitácora
$sql = "SELECT id, usuario, fecha, estado FROM bitacora ORDER BY fecha DESC";
$resultado = $conn->query($sql);

// Verificar si hubo un error en la consulta
if (!$resultado) {
    die("Error al consultar la bitácora: " . $conn->error);
}
?>


?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bitácora de Accesos</title>
    <title>Usuarios</title>
    <link href="https://fonts.googleapis.com/css2?family=Cardo&family=IM+Fell+English+SC&display=swap" rel="stylesheet">
    <style>
       body {
    font-family: 'Cardo', serif;
    background: url('imagenes/vinof.jpg') no-repeat center center fixed; /* Ruta a la imagen local */
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
        }

        h1 {
            font-family: 'IM Fell English SC', serif;
            font-size: 32px;
            color: #e2c290;
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.05);
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #555;
            color: #4da6ff; /* Azul claro */
        }

        th {
            background-color: #1a1a1a;
            color: #bfa880;
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
        }

        .btn-editar:hover,
        .btn-eliminar:hover {
            background-color: #370808;
        }

        .btn-volver {
            display: block;
            margin: 30px auto 0;
            background-color: #5b0e0e;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-family: 'IM Fell English SC', serif;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
            text-decoration: none;
            text-align: center;
            width: 150px;
        }

        .btn-volver:hover {
            background-color: #370808;
        }
    </style>
</head>



 
<body>
    <div class="container">
        <h1>📜 Bitácora de Accesos</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                  
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= $fila['id'] ?></td>
                        <td><?= htmlspecialchars($fila['usuario']) ?></td>
                        <td><?= $fila['fecha'] ?></td>
                        <td><?= $fila['estado'] ?></td>
                   
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn-volver">🔙 Volver</a>
    </div>
</body>
</html>

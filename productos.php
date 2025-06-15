<?php
include("db.php");

// AGREGAR o ACTUALIZAR producto
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $especificaciones = $_POST['especificaciones'];
    $idEditar = isset($_POST['id_editar']) ? intval($_POST['id_editar']) : null;

    $imagen = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $nombreImagen = basename($_FILES['imagen']['name']);
        $rutaDestino = 'uploads/' . $nombreImagen;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino);
        $imagen = $rutaDestino;
    }

    if ($idEditar) {
        // Actualizar producto existente
        if ($imagen) {
            $sql = "UPDATE productos SET nombre=?, precio=?, stock=?, especificaciones=?, imagen=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdissi", $nombre, $precio, $stock, $especificaciones, $imagen, $idEditar);
        } else {
            $sql = "UPDATE productos SET nombre=?, precio=?, stock=?, especificaciones=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdisi", $nombre, $precio, $stock, $especificaciones, $idEditar);
        }
        $stmt->execute();
    } else {
        // Insertar nuevo producto
        $sql = "INSERT INTO productos (nombre, precio, stock, especificaciones, imagen) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdiss", $nombre, $precio, $stock, $especificaciones, $imagen);
        $stmt->execute();
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// ELIMINAR producto
if (isset($_POST['action']) && $_POST['action'] == 'eliminar') {
    $idEliminar = $_POST['id'];

    $stmt = $conn->prepare("SELECT imagen FROM productos WHERE id = ?");
    $stmt->bind_param("i", $idEliminar);
    $stmt->execute();
    $stmt->bind_result($imagenRuta);
    $stmt->fetch();
    $stmt->close();

    if ($imagenRuta && file_exists($imagenRuta)) {
        unlink($imagenRuta);
    }

    $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $idEliminar);
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Cargar datos para edición si existe id por GET
$productoEditar = null;
if (isset($_GET['editar'])) {
    $id = intval($_GET['editar']);
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $productoEditar = $resultado->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos - Vinos los Lex</title>
    <link href="https://fonts.googleapis.com/css2?family=Cardo&family=UnifrakturCook:wght@700&family=IM+Fell+English+SC&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>🍷 Vinos los Lex</h1>
        <h2>Gestión de Productos</h2>

        <div class="form-container">
            <h3><?= $productoEditar ? 'Editar Producto' : 'Agregar Nuevo Producto' ?></h3>
            <form method="POST" enctype="multipart/form-data">
                <?php if ($productoEditar): ?>
                    <input type="hidden" name="id_editar" value="<?= $productoEditar['id'] ?>">
                <?php endif; ?>
                <input type="text" name="nombre" placeholder="Nombre del vino" required value="<?= $productoEditar['nombre'] ?? '' ?>"><br>
                <input type="number" name="precio" step="0.01" placeholder="Precio ($)" required value="<?= $productoEditar['precio'] ?? '' ?>"><br>
                <input type="number" name="stock" placeholder="Stock disponible" required value="<?= $productoEditar['stock'] ?? '' ?>"><br>
                <textarea name="especificaciones" placeholder="Especificaciones"><?= $productoEditar['especificaciones'] ?? '' ?></textarea><br>
                <input type="file" name="imagen" accept="image/*"><br>
                <button type="submit"><?= $productoEditar ? '💾 Guardar Cambios' : '➕ Agregar Producto' ?></button>
            </form>
        </div>

        <div class="product-table-container">
            <h3>Inventario de Vinos</h3>
            <table class="product-table" border="1" cellpadding="5">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Especificaciones</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $resultado = $conn->query("SELECT * FROM productos ORDER BY nombre");
                    while ($fila = $resultado->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?= $fila['id'] ?></td>
                            <td>
                                <?php if (!empty($fila['imagen'])): ?>
                                    <img src="<?= htmlspecialchars($fila['imagen']) ?>" alt="Imagen del producto" width="50">
                                <?php else: ?>
                                    No imagen
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                            <td>$<?= number_format($fila['precio'], 2) ?></td>
                            <td><?= $fila['stock'] ?></td>
                            <td><?= htmlspecialchars($fila['especificaciones']) ?></td>
                            <td>
                                <form method="GET" style="display:inline;">
                                    <input type="hidden" name="editar" value="<?= $fila['id'] ?>">
                                    <button type="submit">✏️ Editar</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $fila['id'] ?>">
                                    <input type="hidden" name="action" value="eliminar">
                                    <button type="submit" onclick="return confirm('¿Eliminar este producto?')">🗑️ Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <a href="admin.php" class="btn-volver">⬅️ Volver</a>
    </div>
</body>
</html>


<link href="https://fonts.googleapis.com/css2?family=Cardo&family=IM+Fell+English+SC&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Cardo', serif;
        background: url('imagenes/vinof.jpg') no-repeat center center fixed;
        background-size: cover;
        margin: 0;
        padding: 0;
        color: #f5f5f5;
    }
    
    body::before {
    content: "";
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4); /* Capa oscura encima del fondo */
    z-index: -1;
}


    .form-container {
        background-color: rgba(20, 20, 20, 0.95);
        padding: 30px;
        max-width: 450px; /* Un cuarto más pequeño */
        margin: 40px auto;
        border-radius: 15px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.7);
        border: 2px solid #5b0e0e;
    }

    .form-container h2 {
        font-family: 'IM Fell English SC', serif;
        font-size: 26px;
        color: #e2c290;
        text-align: center;
        margin-bottom: 25px;
    }

    label {
        display: block;
        font-family: 'IM Fell English SC', serif;
        font-size: 17px;
        margin: 10px 0 5px;
        color: #bfa880;
    }

    input[type="text"],
    input[type="number"],
    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #555;
        background-color: #1a1a1a;
        color: #f5f5f5;
        border-radius: 8px;
        font-size: 16px;
        font-family: 'Cardo', serif;
    }

    input::placeholder,
    textarea::placeholder {
        color: #aaa;
        font-style: italic;
    }

    button[type="submit"] {
        background-color: #5b0e0e;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-family: 'IM Fell English SC', serif;
        font-size: 16px;
        margin-top: 20px;
        width: 100%;
        transition: background 0.3s ease;
    }

    button[type="submit"]:hover {
        background-color: #370808;
    }

    /* Estilo para la tabla del inventario */
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
        color: #4da6ff;
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


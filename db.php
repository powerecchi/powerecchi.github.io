<?php
// Conexión a la base de datos
$servidor = "sql207.byethost3.com";
$usuarioBD = "b3_39196622";       // tu usuario de base de datos
$contrasenaBD = "";        // la contraseña (vacía si usas XAMPP o Laragon)
$baseDeDatos = "b3_39196622_crud1"; // nombre de tu base de datos

$conn = new mysqli($servidor, $usuarioBD, $contrasenaBD, $baseDeDatos);

// Verificar si hay algún error
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>

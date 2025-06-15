<!DOCTYPE html>
<html>
<head>
  <title>Panel de Consultor</title>
</head>
<link rel="stylesheet" href="ruta/a/estilos.css">

<body>
  <h2>Bienvenido, Consultor</h2>
  <ul>
    <li><a href="carrito.php">Carro de compras</a></li>
  </ul>
</body>
</html>

><style>
/* Estilos generales que ya tienes */
@import url('https://fonts.googleapis.com/css2?family=Cardo&family=UnifrakturCook:wght@700&display=swap');

body {
    font-family: 'Cardo', serif;
    background: url('imagenes/vinof.jpg') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    padding: 20px;
    color: #f5f5f5;
    display: flex;
    flex-direction: column; /* Alinear elementos verticalmente en el body */
    align-items: center; /* Centrar horizontalmente */
    min-height: 100vh;
    text-align: center;
}

h2 {
    font-family: 'UnifrakturCook', cursive;
    font-size: 28px;
    color: #e2c290;
    margin-top: 20px;
    margin-bottom: 30px;
}

ul {
    list-style: none;
    padding: 0;
    margin: 0;
    background-color: rgba(20, 20, 20, 0.95);
    border-radius: 15px;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.7);
    border: 2px solid #5b0e0e;
    padding: 20px; /* Reducido el padding horizontal */
    display: inline-block; /* Se mantiene para el ancho ajustado */
    text-align: left; /* Alinear los items del menú a la izquierda */
}

li {
    margin-bottom: 15px;
}

li:last-child {
    margin-bottom: 0;
}

li a {
    display: block; /* Para que el enlace ocupe todo el ancho del li */
    color: #e2c290;
    text-decoration: none;
    font-weight: bold;
    font-size: 18px;
    padding: 10px 20px;
    border-radius: 8px;
    transition: background-color 0.3s ease;
}

li a:hover {
    background-color: #5b0e0e;
    color: #ffffff;
}

/* Estilos que ya tenías y podrían aplicarse aquí si es la misma página */
.container, .login-container {
    background-color: rgba(20, 20, 20, 0.95);
    padding: 20px 40px 40px 40px;
    max-width: 600px;
    margin: 50px auto;
    border-radius: 15px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.7);
    border: 2px solid #5b0e0e;
    text-align: center;
    position: relative;
}



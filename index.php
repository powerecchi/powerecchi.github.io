



<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  

</head>
<link rel="stylesheet" href="segundo/estilo.css">

<style>

@import url('https://fonts.googleapis.com/css2?family=Cardo&family=UnifrakturCook:wght@700&display=swap');

body {
    font-family: 'Cardo', serif;
    background: url('imagenes/vinol.jpg') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    padding: 0;
    color: #f5f5f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    text-align: center;
}

.container, .login-container {
    background-color: rgba(20, 20, 20, 0.95);
    padding: 20px 40px 40px 40px; /* Menos padding arriba */
    max-width: 600px;
    margin: 50px auto;
    border-radius: 15px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.7);
    border: 2px solid #5b0e0e;
    text-align: center;
    position: relative;
}

h2, h3 {
    font-family: 'UnifrakturCook', cursive;
    font-size: 28px;
    color: #e2c290;
    margin-top: 0;
    margin-bottom: 20px;
    position: relative;
    top: -10px; /* Lo empuja aún más hacia arriba */
}



input[type="text"],
input[type="password"],
input[type="number"],
textarea,
select {
    width: 90%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #444;
    background-color: #222;
    color: #f5f5f5;
    border-radius: 8px;
    font-size: 16px;
}

input::placeholder, textarea::placeholder {
    color: #aaa;
    font-style: italic;
}

button {
    background-color: #5b0e0e;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-family: 'IM Fell English SC', serif;
    font-size: 16px;
    transition: background 0.3s ease;
}

button:hover {
    background-color: #370808;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: rgba(50, 50, 50, 0.95);
    border: 1px solid #5b0e0e;
    color: #f5f5f5;
}

th, td {
    padding: 10px;
    border: 1px solid #333;
    text-align: left;
}

th {
    background-color: #5b0e0e;
    color: #e2c290;
}

tr:hover {
    background-color: rgba(100, 20, 20, 0.2);
}

a {
    color: #e2c290;
    text-decoration: none;
    font-weight: bold;
}
a:hover {
    color: #ffffff;
}

</style>
<body>
  <div class="login-container">
  <h2>Bienvenido a Vinos los Lex 🍷</h2>
  
  <form action="login.php" method="POST">
    Usuario: <input type="text" name="usuario"><br>
    Contraseña: <input type="password" name="contrasena"><br>

    <button type="submit">Inicio</button>
    <a href="registro.php"><button type="button">Regs.</button></a>
  </form>
</body>
</html>



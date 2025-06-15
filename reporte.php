<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gráfica de Ventas</title>
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
            background: rgba(0, 0, 0, 0.4);
            z-index: -1;
        }

 .container {
    background-color: rgba(20, 20, 20, 0.95);
    padding: 60px;
    max-width: 1200px;
    margin: 60px auto;
    border-radius: 15px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.7);
    border: 2px solid #5b0e0e;
}
s

        h2 {
            font-family: 'IM Fell English SC', serif;
            font-size: 32px;
            color: #e2c290;
            text-align: center;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-bottom: 15px;
            font-size: 18px;
        }

        input[type="date"] {
            padding: 10px;
            border: 1px solid #5b0e0e;
            border-radius: 8px;
            background-color: #1a1a1a;
            color: #f5f5f5;
            font-family: 'Cardo', serif;
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

     canvas {
    display: block;
    margin: 40px auto 0 auto;
    background-color: #1a1a1a;
    border-radius: 10px;
    border: 2px solid #5b0e0e;
    max-width: 100%;
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Selecciona un Rango de Fechas</h2>


<!-- Formulario -->
<form method="GET" action="graficas.php">
    <label>Fecha ini. <input type="date" name="fecha_ini" required value="<?php echo htmlspecialchars($fecha_ini); ?>"></label>
    <label>Fecha Final <input type="date" name="fecha_fin" required value="<?php echo htmlspecialchars($fecha_fin); ?>"></label>
    <button type="submit">Enviar</button>
</form>

<!-- Caja de la gráfica -->
<div class="grafica-box">
    <canvas id="graficaVentas" width="800" height="400"></canvas>
</div>


    <!-- Botón para imprimir -->
    <button onclick="imprimirGrafica()">Imprimir Gráfica</button>

    <!-- Librería Chart.js (si estás usando esta) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Aquí iría el código para generar la gráfica
        const ctx = document.getElementById('miGrafica').getContext('2d');
        const miGrafico = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo'],
                datasets: [{
                    label: 'Ventas',
                    data: [12, 19, 3],
                    backgroundColor: 'rgba(75, 192, 192, 0.5)'
                }]
            }
        });

        function imprimirGrafica() {
            const contenido = document.getElementById('grafica').innerHTML;
            const ventana = window.open('', '', 'height=600,width=800');
            ventana.document.write('<html><head><title>Imprimir Gráfica</title></head><body>');
            ventana.document.write(contenido);
            ventana.document.write('</body></html>');
            ventana.document.close();
            ventana.focus();
            ventana.print();
            ventana.close();
        }
    </script>
<?php
include("db.php");

// Validación de fechas y protección contra SQL injection
$fecha_ini = isset($_GET['fecha_ini']) ? mysqli_real_escape_string($conn, $_GET['fecha_ini']) : '';
$fecha_fin = isset($_GET['fecha_fin']) ? mysqli_real_escape_string($conn, $_GET['fecha_fin']) : '';

$datos = [];
$error = '';

if ($fecha_ini && $fecha_fin) {
    // Validar que las fechas sean correctas y estén en el formato YYYY-MM-DD
    $date_ini_obj = DateTime::createFromFormat('Y-m-d', $fecha_ini);
    $date_fin_obj = DateTime::createFromFormat('Y-m-d', $fecha_fin);

    if (!$date_ini_obj || !$date_fin_obj || $date_ini_obj->format('Y-m-d') !== $fecha_ini || $date_fin_obj->format('Y-m-d') !== $fecha_fin) {
        $error = "Por favor, introduce fechas válidas en formato YYYY-MM-DD.";
    } elseif ($date_ini_obj > $date_fin_obj) {
        $error = "La fecha inicial no puede ser mayor que la fecha final";
    } else {
        // Consulta preparada para mayor seguridad
        $query = "SELECT p.nombre, SUM(v.cantidad) AS total_vendidos, p.stock
                  FROM ventas v
                  INNER JOIN productos p ON v.id_producto = p.id
                  WHERE v.fecha BETWEEN ? AND ?
                  GROUP BY p.id
                  ORDER BY total_vendidos DESC";
        
        $stmt = mysqli_prepare($conn, $query);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $fecha_ini, $fecha_fin);
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($resultado)) {
                $datos[] = $row;
            }
            
            mysqli_stmt_close($stmt);
        } else {
            $error = "Error al preparar la consulta: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfica de Ventas</title>
    <link href="https://fonts.googleapis.com/css2?family=Cardo&family=IM+Fell+English+SC&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
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
            padding: 40px;
            max-width: 800px;
            margin: 60px auto;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.7);
            border: 2px solid #5b0e0e;
        }

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
            width: 100%;
            box-sizing: border-box;
        }

        button {
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

        button:hover {
            background-color: #370808;
        }

        .chart-container {
            position: relative;
            width: 100%;
            height: 70vh;
            min-height: 500px;
            margin: 40px auto;
        }

        #graficaVentas {
            width: 100% !important;
            height: 100% !important;
            background-color: #1a1a1a;
            border-radius: 10px;
            border: 2px solid #5b0e0e;
        }

        .error {
            color: #ff6b6b;
            text-align: center;
            margin: 15px 0;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 20px auto;
            }
            
            h2 {
                font-size: 24px;
            }
            
            .chart-container {
                height: 60vh;
                min-height: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Gráfica de Productos Vendidos</h2>

        <form method="GET" action="graficas.php">
            <label>Fecha Inicial <input type="date" name="fecha_ini" required value="<?php echo htmlspecialchars($fecha_ini); ?>"></label>
            <label>Fecha Final <input type="date" name="fecha_fin" required value="<?php echo htmlspecialchars($fecha_fin); ?>"></label>
            <button type="submit">Generar Gráfica</button>
        </form>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($datos)): ?>
            <div class="chart-container">
                <canvas id="graficaVentas"></canvas>
            </div>
            <button onclick="generarPDF()">Descargar Gráfica en PDF</button>
        <?php elseif ($fecha_ini && $fecha_fin && !$error): ?>
            <div class="error">No se encontraron datos para el rango de fechas seleccionado.</div>
        <?php endif; ?>
    </div>

    <script>
        // Configuración de la gráfica
        const datos = <?php echo json_encode($datos); ?>;
        const ctx = document.getElementById('graficaVentas')?.getContext('2d');
        let myChart;

        if (ctx && datos.length > 0) {
            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: datos.map(d => d.nombre),
                    datasets: [
                        {
                            label: 'Unidades Vendidas',
                            data: datos.map(d => d.total_vendidos),
                            backgroundColor: 'rgba(91, 14, 14, 0.7)',
                            borderColor: 'rgba(91, 14, 14, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Stock Disponible',
                            data: datos.map(d => d.stock),
                            backgroundColor: 'rgba(226, 194, 144, 0.7)',
                            borderColor: 'rgba(226, 194, 144, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Cantidad de Productos',
                                color: '#e2c290'
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#f5f5f5'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Productos',
                                color: '#e2c290'
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#f5f5f5'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    family: "'Cardo', serif",
                                    size: 14
                                },
                                color: '#e2c290'
                            }
                        },
                        tooltip: {
                            bodyFont: {
                                family: "'Cardo', serif"
                            },
                            titleFont: {
                                family: "'IM Fell English SC', serif"
                            },
                            backgroundColor: 'rgba(20, 20, 20, 0.9)',
                            titleColor: '#e2c290',
                            bodyColor: '#f5f5f5',
                            borderColor: '#5b0e0e',
                            borderWidth: 1
                        }
                    }
                }
            });
        }

        function generarPDF() {
            const canvas = document.getElementById('graficaVentas');
            if (!canvas) {
                console.error("Canvas 'graficaVentas' no encontrado.");
                return;
            }

            // Mostrar mensaje de carga
            const btnPDF = document.querySelector('button[onclick="generarPDF()"]');
            if (btnPDF) {
                const originalText = btnPDF.textContent;
                btnPDF.disabled = true;
                btnPDF.textContent = 'Generando PDF...';
                
                setTimeout(() => {
                    btnPDF.disabled = false;
                    btnPDF.textContent = originalText;
                }, 2000);
            }

            setTimeout(() => {
                // Configuración para mejor calidad
                const pdfWidth = 210; // Ancho de A4 en mm
                const pdfHeight = 297; // Alto de A4 en mm
                const padding = 20; // Espacio para márgenes y texto
                
                // Calcular dimensiones manteniendo la relación de aspecto
                const canvasRatio = canvas.width / canvas.height;
                let imgWidth = pdfWidth - padding;
                let imgHeight = imgWidth / canvasRatio;
                
                // Si la altura es demasiado grande, ajustar
                if (imgHeight > (pdfHeight - padding * 2)) {
                    imgHeight = pdfHeight - padding * 2;
                    imgWidth = imgHeight * canvasRatio;
                }

                // Crear el documento PDF
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF({
                    orientation: imgWidth > imgHeight ? 'landscape' : 'portrait',
                    unit: 'mm'
                });

                // Convertir el canvas a imagen con alta calidad
                const imgData = canvas.toDataURL('image/png', 1.0);
                
                // Agregar metadatos
                const fechaHora = new Date().toLocaleString('es-ES');
                const fechaIni = "<?php echo htmlspecialchars($fecha_ini); ?>";
                const fechaFin = "<?php echo htmlspecialchars($fecha_fin); ?>";
                
                pdf.setFont('helvetica', 'bold');
                pdf.setFontSize(16);
                pdf.text('GRÁFICA DE PRODUCTOS VENDIDOS', pdfWidth / 2, 15, { align: 'center' });
                pdf.setFont('helvetica', 'normal');
                pdf.setFontSize(12);
                pdf.text(`Período: ${fechaIni} al ${fechaFin}`, pdfWidth / 2, 22, { align: 'center' });
                pdf.setFontSize(10);
                pdf.text(`Fecha de generación: ${fechaHora}`, pdfWidth / 2, 27, { align: 'center' });
                
                // Agregar la imagen centrada
                const x = (pdf.internal.pageSize.getWidth() - imgWidth) / 2;
                const y = 35; // Posición vertical después del texto
                
                pdf.addImage(imgData, 'PNG', x, y, imgWidth, imgHeight);
                
                // Guardar el PDF
                pdf.save(`reporte_ventas_${fechaIni}_a_${fechaFin}.pdf`);
            }, 500); // Tiempo suficiente para renderizar
        }
    </script>
</body>
</html>
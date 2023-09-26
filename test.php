<?php
include 'confing.php';
$conexion = new mysqli($servername, $username, $password, $dbname,$port);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$registrosPorPagina = 25;

if (isset($_GET['page'])) {
    $paginaActual = $_GET['page'];
} else {
    $paginaActual = 1;
}

$inicio = ($paginaActual - 1) * $registrosPorPagina;

$sql = "SELECT * FROM prepack LIMIT $inicio, $registrosPorPagina";
$resultado = $conexion->query($sql);

echo "<style>
#list {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

#list td, #list th {
    border: 1px solid #ddd;
    padding: 8px;
}

#list tr:nth-child(even){background-color: #f2f2f2;}

#list tr:hover {background-color: #ddd;}

#list th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #04AA6D;
    color: white;
}
.pagination {
    display: inline-block;
}

.pagination a {
    color: black;
    float: left;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
    border: 1px solid #ddd;
    margin: 0 4px;
}

.pagination a.active {
    background-color: #4CAF50;
    color: white;
    border: 1px solid #4CAF50;
}

.pagination a:hover:not(.active) {background-color: #ddd;}
</style>
<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
</head>
<body>

<h1>Table</h1>
<input type='text' id='filtro' placeholder='Buscar en la columna'>
<table id=list>
<tr>
<th>Tracking</th>
<th>Creation Date</th>
<th>N Pack</th>
<th>Pack Type</th>
<th>Weight</th>
</tr>";
while ($fila = $resultado->fetch_assoc()) {
    echo "<tr><td>" . $fila['tracking'] . "</td><td>" . $fila['creationdate'] . "</td><td>" . $fila['npack'] . "</td><td>" . $fila['packtype'] . "</td><td>" . $fila['weight'] . "</td></tr>";
}
echo "</table>";

$sqlTotal = "SELECT COUNT(*) as total FROM prepack";
$resultadoTotal = $conexion->query($sqlTotal);
$totalRegistros = $resultadoTotal->fetch_assoc()['total'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

$enlacesPorPagina = 5;

$rangoInicio = max(1, $paginaActual - $enlacesPorPagina);
$rangoFin = min($totalPaginas, $paginaActual + $enlacesPorPagina);


echo "<div class='pagination'>";
if ($paginaActual > 1) {
    echo "<a href='test.php?page=" . ($paginaActual - 1) . "'>&laquo; Anterior</a>";
}

for ($i = $rangoInicio; $i <= $rangoFin; $i++) {
    $claseActiva = ($i == $paginaActual) ? "active" : "";
    echo "<a href='test.php?page=$i'>$i</a>";
}

if ($paginaActual < $totalPaginas) {
    echo "<a href='test.php?page=" . ($paginaActual + 1) . "'>Siguiente &raquo;</a>";
}

echo "</div>
<script>
$(document).ready(function(){
 
    $('#filtro').on('keyup', function() {
        filtrarTabla()
    });
    function filtrarTabla() {
        var tabla = $('tabla');
        var filas =$('tr');
        var valor=$('#filtro').val();
        console.log(valor);
        for (var i = 1; i < filas.length; i++) {
            var celda = filas[i].getElementsByTagName('td')[0]; 
            
            console.log(celda)
            if (celda) {
                var contenido = celda.textContent || celda.innerText;
                if (contenido.toLowerCase().indexOf(valor) > -1) {
                    filas[i].style.display = '';
                } else {
                    filas[i].style.display = 'none';
                }
            }
        }
    };

    
});
    </script>
    </body>";

$conexion->close();
?>
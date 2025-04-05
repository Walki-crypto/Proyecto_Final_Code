<?php
include 'includes/db.php';

try {
    echo "<h1>Estructura de la tabla 'ventas'</h1>";
    
    // Consultar estructura de la tabla ventas
    $query = "DESCRIBE ventas";
    $stmt = $conn->query($query);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background-color: #333; color: white;'><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th><th>Default</th><th>Extra</th></tr>";
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . ($row['Default'] !== null ? $row['Default'] : 'NULL') . "</td>";
        echo "<td>" . (isset($row['Extra']) ? $row['Extra'] : '') . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Ver algunos registros de ejemplo
    echo "<h1>Primeros 5 registros de la tabla 'ventas'</h1>";
    $query = "SELECT * FROM ventas LIMIT 5";
    $stmt = $conn->query($query);
    
    if ($stmt->rowCount() > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        
        // Obtener los nombres de las columnas
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<tr style='background-color: #333; color: white;'>";
        foreach ($row as $campo => $valor) {
            echo "<th>" . $campo . "</th>";
        }
        echo "</tr>";
        
        // Mostrar el primer registro
        echo "<tr>";
        foreach ($row as $valor) {
            echo "<td>" . (is_null($valor) ? 'NULL' : $valor) . "</td>";
        }
        echo "</tr>";
        
        // Mostrar los siguientes registros
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            foreach ($row as $valor) {
                echo "<td>" . (is_null($valor) ? 'NULL' : $valor) . "</td>";
            }
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>No hay registros en la tabla ventas</p>";
    }
    
} catch (PDOException $e) {
    echo "<div style='color: red; font-weight: bold;'>Error: " . $e->getMessage() . "</div>";
}
?> 
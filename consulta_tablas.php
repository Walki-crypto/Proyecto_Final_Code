<?php
include 'includes/db.php';

try {
    // Consultar estructura de la tabla ventas
    $query = "DESCRIBE ventas";
    $stmt = $conn->query($query);
    echo "<h2>Estructura de la tabla 'ventas'</h2>";
    echo "<table border='1'><tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th><th>Default</th><th>Extra</th></tr>";
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . (isset($row['Extra']) ? $row['Extra'] : '') . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Ver algunos registros de ejemplo
    $query = "SELECT * FROM ventas LIMIT 5";
    $stmt = $conn->query($query);
    echo "<h2>Primeros 5 registros de la tabla 'ventas'</h2>";
    
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<table border='1'><tr>";
        foreach ($row as $campo => $valor) {
            echo "<th>" . $campo . "</th>";
        }
        echo "</tr>";
        
        // Reiniciar el cursor
        $stmt = $conn->query("SELECT * FROM ventas LIMIT 5");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            foreach ($row as $valor) {
                echo "<td>" . $valor . "</td>";
            }
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>No hay registros en la tabla ventas</p>";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 
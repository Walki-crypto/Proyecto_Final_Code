<?php
include 'includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Obtener información del autor
    $query_autor = "SELECT nombre, apellido FROM autores WHERE id_autor = ?";
    $stmt_autor = $conn->prepare($query_autor);
    $stmt_autor->execute([$id]);
    $autor = $stmt_autor->fetch(PDO::FETCH_ASSOC);

    if ($autor) {
        echo "<h4 class='mb-4'>Libros de " . htmlspecialchars($autor['nombre'] . ' ' . $autor['apellido']) . "</h4>";

        // Obtener los libros del autor
        $query_libros = "SELECT t.* 
                        FROM titulos t 
                        INNER JOIN titulo_autor ta ON t.id_titulo = ta.id_titulo 
                        WHERE ta.id_autor = ?
                        ORDER BY t.titulo";
        
        $stmt_libros = $conn->prepare($query_libros);
        $stmt_libros->execute([$id]);
        
        if ($stmt_libros->rowCount() > 0) {
            echo "<div class='table-responsive'>";
            echo "<table class='table table-hover'>";
            echo "<thead class='table-light'>";
            echo "<tr>
                    <th>Título</th>
                    <th>Tipo</th>
                    <th>Precio</th>
                    <th>Fecha de Publicación</th>
                  </tr>";
            echo "</thead>";
            echo "<tbody>";
            
            while ($libro = $stmt_libros->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($libro['titulo']) . "</td>";
                echo "<td><span class='badge bg-info'>" . htmlspecialchars($libro['tipo']) . "</span></td>";
                echo "<td>$" . number_format($libro['precio'], 2) . "</td>";
                echo "<td>" . date('d/m/Y', strtotime($libro['fecha_pub'])) . "</td>";
                echo "</tr>";
            }
            
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        } else {
            echo "<div class='alert alert-info'>Este autor no tiene libros registrados.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Autor no encontrado.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>ID de autor no proporcionado.</div>";
}
?> 
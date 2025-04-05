<?php
include 'includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Obtener información del libro y autor
        $query = "SELECT t.*, a.nombre as autor_nombre, a.apellido as autor_apellido 
                  FROM titulos t 
                  LEFT JOIN titulo_autor ta ON t.id_titulo = ta.id_titulo
                  LEFT JOIN autores a ON ta.id_autor = a.id_autor
                  WHERE t.id_titulo = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);
        $libro = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($libro) {
            $response = [
                'titulo' => $libro['titulo'],
                'tipo' => $libro['tipo'],
                'precio' => number_format($libro['precio'], 2),
                'avance' => isset($libro['avance']) ? number_format($libro['avance'], 2) : null,
                'ventas' => 'N/A', // No disponible
                'fecha_pub' => date('d/m/Y', strtotime($libro['fecha_pub'])),
                'autor' => $libro['autor_nombre'] . ' ' . $libro['autor_apellido'],
                'contrato' => isset($libro['contrato']) ? (bool)$libro['contrato'] : false,
                'notas' => $libro['notas'] ?? 'No hay notas disponibles'
            ];
            echo json_encode($response);
        } else {
            echo json_encode(['error' => 'Libro no encontrado']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}
?> 
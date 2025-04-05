<?php
include 'includes/db.php';

if (!isset($_GET['id'])) {
    die(json_encode(['error' => 'ID de autor no proporcionado']));
}

try {
    $query = "SELECT a.*, 
              b.biografia,
              GROUP_CONCAT(DISTINCT CONCAT_WS('|', t.id_titulo, t.titulo, t.tipo, t.precio) SEPARATOR '||') as libros_info
              FROM autores a
              LEFT JOIN biografias b ON a.id_autor = b.id_autor
              LEFT JOIN titulo_autor ta ON a.id_autor = ta.id_autor
              LEFT JOIN titulos t ON ta.id_titulo = t.id_titulo
              WHERE a.id_autor = ?
              GROUP BY a.id_autor";

    $stmt = $conn->prepare($query);
    $stmt->execute([$_GET['id']]);
    $autor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$autor) {
        die(json_encode(['error' => 'Autor no encontrado']));
    }

    // Formatear la información de los libros
    $libros = [];
    if ($autor['libros_info']) {
        foreach (explode('||', $autor['libros_info']) as $libro) {
            $libro_parts = explode('|', $libro);
            // Verificar que tengamos los 4 elementos esperados
            if (count($libro_parts) >= 4) {
                list($id, $titulo, $tipo, $precio) = $libro_parts;
                $libros[] = [
                    'id' => $id,
                    'titulo' => htmlspecialchars_decode($titulo),
                    'tipo' => htmlspecialchars_decode($tipo),
                    'precio' => $precio
                ];
            }
        }
    }

    // Limpiar datos para JSON
    $response = [
        'autor' => [
            'nombre' => htmlspecialchars_decode(trim($autor['nombre'])),
            'apellido' => htmlspecialchars_decode(trim($autor['apellido'])),
            'telefono' => htmlspecialchars_decode($autor['telefono']),
            'direccion' => htmlspecialchars_decode($autor['direccion']),
            'ciudad' => htmlspecialchars_decode($autor['ciudad']),
            'estado' => htmlspecialchars_decode($autor['estado']),
            'pais' => htmlspecialchars_decode($autor['pais']),
            'cod_postal' => htmlspecialchars_decode($autor['cod_postal']),
            'biografia' => $autor['biografia'] ? htmlspecialchars_decode($autor['biografia']) : null
        ],
        'libros' => $libros
    ];

    // Enviar respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

} catch(PDOException $e) {
    // Asegurarse de no tener caracteres problemáticos en el mensaje de error
    $mensaje_error = 'Error al obtener los detalles del autor: ' . $e->getMessage();
    header('Content-Type: application/json');
    echo json_encode(['error' => $mensaje_error], JSON_UNESCAPED_UNICODE);
    exit;
}
?> 
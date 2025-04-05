<?php
$host = 'sql209.infinityfree.com';     // Host de InfinityFree
$dbname = 'if0_38667196_db_biblioteca';    // Nombre de la base de datos
$username = 'if0_38667196';            // Usuario
$password = '6g7Oles7APiLLN';          // Contraseña
$charset = 'utf8mb4';

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $conn = new PDO($dsn, $username, $password, $options);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
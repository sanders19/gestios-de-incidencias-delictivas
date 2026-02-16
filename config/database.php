<?php
// config/database.php

// Función para cargar variables de .env
function cargarEnv($ruta) {
    if (!file_exists($ruta)) {
        die("Error: archivo .env no encontrado en {$ruta}");
    }

    $lineas = file($ruta, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lineas as $linea) {
        // Ignorar líneas comentadas
        if (strpos(trim($linea), '#') === 0) continue;

        // Separar clave y valor
        if (strpos($linea, '=') !== false) {
            list($clave, $valor) = explode('=', $linea, 2);
            $clave = trim($clave);
            $valor = trim($valor);

            // Quitar comillas si las tiene
            $valor = trim($valor, "\"'");

            // Guardar en $_ENV
            $_ENV[$clave] = $valor;
        }
    }
}

// Cargar el archivo .env desde la raíz
cargarEnv(__DIR__ . '/../.env');

// Ahora usa las variables
$host = $_ENV['DB_HOST'] ?? 'localhost';
$dbname = $_ENV['DB_NAME'] ?? 'sistema_policial_huancavelica';
$username = $_ENV['DB_USER'] ?? 'root';
$password = $_ENV['DB_PASS'] ?? '';
$port = $_ENV['DB_PORT'] ?? '3306';

// Añadir un timeout corto para evitar que la conexión bloqueé por mucho tiempo
$pdoTimeout = 5; // segundos
?>
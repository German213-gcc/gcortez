<?php
$host = "127.0.0.1";
$port = "5432";
$dbname = "gcortez_db";
$user = "postgres";
$password = "";

try {
    // El 'connect_timeout=1' evita que el login se quede cargando infinitamente
    $pdo_pg = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;connect_timeout=1", $user, $password);
    $pdo_pg->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $pdo_pg = null; // Si falla Postgres, pdo_pg es nulo y la web carga rápido
}
?>

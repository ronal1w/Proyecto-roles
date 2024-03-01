<?php
$host = 'localhost'; // Cambia esto si tu base de datos está en un servidor diferente
$dbname = 'proyecto-roles'; // Nombre de tu base de datos
$username = 'root'; // Nombre de usuario de la base de datos
$password = ''; // Contraseña de la base de datos

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}


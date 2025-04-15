<?php
// Charge les variables depuis .env
$env = parse_ini_file(__DIR__ . '/../.env');

// Configuration PDO
$pdo = new PDO(
    "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']}",
    $env['DB_USER'],
    $env['DB_PASS']
);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Retourne l'instance PDO configurée
return $pdo;
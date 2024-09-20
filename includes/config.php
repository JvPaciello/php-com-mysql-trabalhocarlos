<?php

$host = 'localhost';
$db = 'loja'; // Nome do banco de dados
$user = 'root'; // Usuário do banco de dados
$pass = ''; // Senha do banco de dados
$conn = new mysqli($host, $user, $pass, $db);
try {
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
?>

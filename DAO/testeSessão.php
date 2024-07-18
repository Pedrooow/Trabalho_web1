<?php
session_start();
header('Content-Type: application/json');

error_log("Dados recebidos: " . print_r($_POST, true)); // Registra os dados recebidos


// Conexão com o banco de dados
$servername = "localhost:3307";
$username = "root"; // seu username do MySQL
$password = "123456"; // sua senha do MySQL
$dbname = "web1"; // o nome do banco de dados

$conn = new mysqli($servername, $username, $password, $dbname);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não está logado.']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Debug: Verifique o valor do user_id
echo "ID do Usuário: $user_id<br>";
?>
<?php
session_start();
header('Content-Type: application/json');


// Conexão com o banco de dados
$servername = "localhost:3307";
$username = "root"; // seu username do MySQL
$password = "123456"; // sua senha do MySQL
$dbname = "web1"; // o nome do banco de dados

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica conexão
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erro na conexão com o banco de dados.']);
    exit();
}

// Protege contra SQL Injection
$user = $conn->real_escape_string($_POST['username']);
$pass = $conn->real_escape_string($_POST['password']);

// Prepara e executa a consulta SQL
$sql = "SELECT idUsuário FROM Usuário WHERE usuario = ? AND senha = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Erro na preparação da consulta.']);
    exit();
}

$stmt->bind_param('ss', $user, $pass);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($idUsuário);
    $stmt->fetch();
    
    $_SESSION['user_id'] = $idUsuário;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Usuário ou senha inválidos.']);
}

$stmt->close();
$conn->close();
?>

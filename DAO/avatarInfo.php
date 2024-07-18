<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Certifique-se de iniciar a sessão

$servername = "localhost:3307";
$username = "root"; // seu username do MySQL
$password = "123456"; // sua senha do MySQL
$dbname = "web1"; // o nome do banco de dados

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifique se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não está logado.']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Debug: Verifique o valor do user_id
error_log("ID do Usuário: $user_id");

// Consulta para obter o avatar associado ao usuário
$sql = "SELECT Avatar.Pele, Avatar.Rosto, Avatar.Cabelo, Avatar.Torso, Avatar.Pernas
        FROM Avatar
        INNER JOIN Usuário ON Avatar.idAvatar = Usuário.Avatar_idAvatar
        WHERE Usuário.idUsuário = ?";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    error_log("Erro ao preparar a consulta: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Erro ao preparar a consulta.']);
    exit;
}

// Vincule o parâmetro do tipo inteiro
$stmt->bind_param("i", $user_id);

if (!$stmt->execute()) {
    error_log("Erro ao executar a consulta: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Erro ao executar a consulta.']);
    exit;
}

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $avatar = $result->fetch_assoc();
    echo json_encode([
        'success' => true,
        'avatar' => $avatar
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Nenhum avatar encontrado para o usuário.']);
}

$stmt->close();
$conn->close();
?>

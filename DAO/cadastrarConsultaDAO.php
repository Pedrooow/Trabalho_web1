<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost:3307";
$username = "root";
$password = "123456";
$dbname = "web1";

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erro na conexão com o banco de dados.']);
    exit();
}

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$child = sanitizeInput($_POST['child']);
$dentist = sanitizeInput($_POST['dentist']);
$date = sanitizeInput($_POST['date']);
$time = sanitizeInput($_POST['time']);
$procedure = sanitizeInput($_POST['procedure']);
$userId = $_SESSION['user_id'];
$Dentista_id = rand(1,5);

if (empty($child) || empty($dentist) || empty($date) || empty($time) || empty($procedure)) {
    echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios.']);
    exit();
}

// Montando a instrução SQL
$sql = "INSERT INTO consulta (Usuário_idUsuário, dentista_id, NomeDentista, NomeCriança, Data, Hora, procedimento) 
        VALUES ('$userId', '$Dentista_id', '$dentist', '$child', '$date', '$time', '$procedure')";

// Executando a instrução SQL
if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $sql . '<br>' . $conn->error]);
}

$conn->close();
?>

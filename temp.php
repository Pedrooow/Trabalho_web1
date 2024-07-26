<?php
session_start();
header('Content-Type: application/json');

$servername = "localhost:3307";
$username = "root";
$password = "123456";
$dbname = "web1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erro na conexão com o banco de dados.']);
    exit();
}

// Função para sanitizar dados de entrada
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$childName = sanitizeInput($_POST['childName']);
$childUser = sanitizeInput($_POST['childUser']);
$childPassword = password_hash(sanitizeInput($_POST['childPassword']), PASSWORD_BCRYPT);
$birthDate = sanitizeInput($_POST['birthDate']);
$gender = sanitizeInput($_POST['gender']);
$parentName = sanitizeInput($_POST['parentName']);
$contact = sanitizeInput($_POST['contact']);
$address = sanitizeInput($_POST['address']);

if (empty($childName)  empty($childUser)  empty($childPassword)  empty($birthDate)  empty($gender)  empty($parentName)  empty($contact) || empty($address)) {
    echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios.']);
    exit();
}

$sql = "INSERT INTO usuário (nome, usuario, senha, dt_nascimento, genero, nome_responsavel, tel_responsavel, Endereco) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Erro ao preparar a consulta.']);
    exit();
}

$stmt->bind_param("ssssssss", $childName, $childUser, $childPassword, $birthDate, $gender, $parentName, $contact, $address);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Cadastro realizado com sucesso.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao executar a consulta.']);
}

$stmt->close();
$conn->close();
?>
validação login
<?php
session_start();
header('Content-Type: application/json');

$servername = "localhost:3307";
$username = "root";
$password = "123456";
$dbname = "web1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erro na conexão com o banco de dados.']);
    exit();
}

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$user = sanitizeInput($_POST['username']);
$pass = sanitizeInput($_POST['password']);

if (empty($user) || empty($pass)) {
    echo json_encode(['success' => false, 'message' => 'Usuário e senha são obrigatórios.']);
    exit();
}

$sql = "SELECT idUsuário, senha FROM Usuário WHERE usuario = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Erro na preparação da consulta.']);
    exit();
}

$stmt->bind_param('s', $user);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($idUsuário, $hashedPassword);
    $stmt->fetch();

    if (password_verify($pass, $hashedPassword)) {
        $_SESSION['user_id'] = $idUsuário;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuário ou senha inválidos.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Usuário ou senha inválidos.']);
}

$stmt->close();
$conn->close();
?>
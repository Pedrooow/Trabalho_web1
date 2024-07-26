<?php
// Configurar o cabeçalho para JSON
header('Content-Type: application/json');

// Ler o corpo da requisição e decodificar o JSON
$data = json_decode(file_get_contents('php://input'), true);

// Verificar erros na decodificação JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'Erro na decodificação JSON: ' . json_last_error_msg()]);
    exit();
}

// Exibir dados recebidos para depuração
var_dump($data);

$servername = "localhost:3307";
$username = "root";
$password = "123456";
$dbname = "web1";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die(json_encode(['error' => 'Falha na conexão: ' . $conn->connect_error]));
}

$idUsuario = $data['idUsuario'];
$skinColor = $data['skinColor'];
$face = $data['face'];
$hair = $data['hair'];
$torso = $data['torso'];
$legs = $data['legs'];

// Verificação básica dos dados recebidos
if (empty($idUsuario) || empty($skinColor) || empty($face) || empty($hair) || empty($torso) || empty($legs)) {
    echo json_encode(['error' => 'Dados não podem estar vazios']);
    $conn->close();
    exit();
}

// Inserir ou atualizar o avatar no banco de dados
$sql = "INSERT INTO Avatar (Pele, Rosto, Cabelo, Torso, Pernas) 
        VALUES ('$skinColor', '$face', '$hair', '$torso', '$legs') 
        ON DUPLICATE KEY UPDATE 
        Pele='$skinColor', 
        Rosto='$face', 
        Cabelo='$hair', 
        Torso='$torso', 
        Pernas='$legs'";

if ($conn->query($sql) === TRUE) {
    // Obter o ID do Avatar inserido ou atualizado
    if ($conn->affected_rows > 0) {
        $idAvatar = $conn->insert_id;

        // Se o idAvatar foi atualizado, deve ser obtido a partir de uma nova consulta
        if ($idAvatar == 0) {
            // Consulta para obter o idAvatar atualizado
            $checkAvatarSql = "SELECT idAvatar FROM Avatar 
                               WHERE Pele='$skinColor' AND Rosto='$face' 
                               AND Cabelo='$hair' AND Torso='$torso' AND Pernas='$legs'";
            $result = $conn->query($checkAvatarSql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $idAvatar = $row['idAvatar'];
            } else {
                echo json_encode(['error' => 'Erro: O Avatar não foi encontrado.']);
                $conn->close();
                exit();
            }
        }

        // Atualizar a FK na tabela Usuário
        $updateUserSql = "UPDATE Usuário SET Avatar_idAvatar='$idAvatar' WHERE idUsuário='$idUsuario'";

        if ($conn->query($updateUserSql) === TRUE) {
            echo json_encode(['message' => 'Avatar salvo com sucesso!']);
        } else {
            echo json_encode(['error' => 'Erro ao atualizar o usuário: ' . $conn->error]);
        }
    } else {
        echo json_encode(['message' => 'Nenhuma linha afetada.']);
    }
} else {
    echo json_encode(['error' => 'Erro ao salvar o avatar: ' . $conn->error]);
}

$conn->close();
?>

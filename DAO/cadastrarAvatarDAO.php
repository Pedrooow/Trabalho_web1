<?php
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

$idUsuario = $_POST['idUsuario']; // Supondo que você está enviando o ID do usuário junto com o formulário
$skinColor = $_POST['skinColor'];
$face = $_POST['face'];
$hair = $_POST['hair'];
$torso = $_POST['torso'];
$legs = $_POST['legs'];

// Inserir ou atualizar o avatar no banco de dados
$sql = "INSERT INTO Avatar (Pele, Rosto, Cabelo, Torso, Pernas) VALUES ('$skinColor', '$face', '$hair', '$torso', '$legs') 
ON DUPLICATE KEY UPDATE Pele='$skinColor', Rosto='$face', Cabelo='$hair', Torso='$torso', Pernas='$legs'";

if ($conn->query($sql) === TRUE) {
  $idAvatar = $conn->insert_id;

  // Verificar se o idAvatar existe na tabela Avatar
  $checkAvatarSql = "SELECT idAvatar FROM Avatar WHERE idAvatar = '$idAvatar'";
  $result = $conn->query($checkAvatarSql);

  if ($result->num_rows > 0) {
    // Atualizar a FK na tabela usuário
    $updateUserSql = "UPDATE Usuário SET Avatar_idAvatar='$idAvatar' WHERE idUsuário='$idUsuario'";

    if ($conn->query($updateUserSql) === TRUE) {
      echo "Avatar salvo com sucesso!";
    } else {
      echo "Erro ao atualizar o usuário: " . $conn->error;
    }
  } else {
    echo "Erro: O Avatar com id $idAvatar não existe na tabela Avatar.";
  }
} else {
  echo "Erro ao salvar o avatar: " . $conn->error;
}

$conn->close();
?>

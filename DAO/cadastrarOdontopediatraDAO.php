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

// Obtendo os dados do formulário
$dentistName = $_POST['dentistName'];
$crm = $_POST['crm'];

// Preparando a instrução SQL para inserção
$sql = "INSERT INTO dentista (nome, crm) VALUES ('$dentistName', '$crm')";

// Executando a instrução SQL
if ($conn->query($sql) === TRUE) {
    echo "Cadastro realizado com sucesso!";
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}

// Fechando a conexão
$conn->close();
?>

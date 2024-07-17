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
$childName = $_POST['childName'];
$birthDate = $_POST['birthDate'];
$gender = $_POST['gender'];
$parentName = $_POST['parentName'];
$contact = $_POST['contact'];
$address = $_POST['address'];

// Preparando a instrução SQL para inserção
$sql = "INSERT INTO usuário (nome, dt_nascimento, genero, nome_responsavel, tel_responsavel, Endereco) 
        VALUES ('$childName', '$birthDate', '$gender', '$parentName', '$contact', '$address')";

// Executando a instrução SQL
if ($conn->query($sql) === TRUE) {
    echo "Cadastro realizado com sucesso!";
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}

// Fechando a conexão
$conn->close();
?>

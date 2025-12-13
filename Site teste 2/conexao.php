<?php
// Configurações do Banco de Dados (Padrão XAMPP/WAMP)
$servername = "localhost";
$username = "root";       // Usuário padrão do XAMPP/WAMP
$password = "";           // Senha padrão do XAMPP/WAMP (geralmente vazia)
$dbname = "brick_up";     // Nome que daremos ao seu banco de dados

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    // Para um site real, você não deve mostrar o erro completo ao usuário.
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Define o charset para evitar problemas com acentuação
$conn->set_charset("utf8");
?>
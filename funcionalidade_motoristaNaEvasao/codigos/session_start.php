<?php
session_start();

// Verifica se o usuário não está logado
if (!isset($_SESSION['user'])) {
    header("Location: ../index.html"); // Redireciona para a página de login se não estiver logado
    exit();
}

// Define o status do usuário (você precisa ajustar essa lógica conforme necessário)
$statusUsuario = $_SESSION['status']; // Supondo que o status do usuário esteja armazenado na sessão

?>
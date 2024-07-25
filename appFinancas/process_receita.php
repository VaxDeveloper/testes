<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $account = $_POST['account'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare('INSERT INTO transactions (type, amount, date, account_id, description) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute(['receita', $amount, $date, $account, $description]);

    // Atualiza o saldo da conta
    $stmt = $pdo->prepare('UPDATE accounts SET balance = balance + ? WHERE id = ?');
    $stmt->execute([$amount, $account]);

    header('Location: receitas.php');
}
?>
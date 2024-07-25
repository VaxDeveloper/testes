<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $account = $_POST['account'];
    $description = $_POST['description'];

    try {
        // Inicia uma transação
        $pdo->beginTransaction();

        // Insere a transação de despesa
        $stmt = $pdo->prepare('INSERT INTO transactions (type, amount, date, account_id, description) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute(['despesa', -$amount, $date, $account, $description]);

        // Atualiza o saldo da conta
        $stmt = $pdo->prepare('UPDATE accounts SET balance = balance - ? WHERE id = ?');
        $stmt->execute([$amount, $account]);

        // Confirma a transação
        $pdo->commit();

        // Redireciona para a página de despesas
        header('Location: despesas.php');
    } catch (Exception $e) {
        // Se houver um erro, reverte a transação
        $pdo->rollBack();
        echo "Falha no lançamento da despesa: " . $e->getMessage();
    }
}
?>
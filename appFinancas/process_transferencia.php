<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $from_account = $_POST['from_account'];
    $to_account = $_POST['to_account'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    // Verifica se as contas são diferentes
    if ($from_account != $to_account) {
        try {
            // Inicia uma transação
            $pdo->beginTransaction();

            // Insere a transação de saída (da conta de origem)
            $stmt = $pdo->prepare('INSERT INTO transactions (type, amount, date, account_id, description) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute(['transferencia', -$amount, $date, $from_account, $description]);

            // Insere a transação de entrada (na conta de destino)
            $stmt = $pdo->prepare('INSERT INTO transactions (type, amount, date, account_id, description) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute(['transferencia', $amount, $date, $to_account, $description]);

            // Atualiza o saldo da conta de origem
            $stmt = $pdo->prepare('UPDATE accounts SET balance = balance - ? WHERE id = ?');
            $stmt->execute([$amount, $from_account]);

            // Atualiza o saldo da conta de destino
            $stmt = $pdo->prepare('UPDATE accounts SET balance = balance + ? WHERE id = ?');
            $stmt->execute([$amount, $to_account]);

            // Confirma a transação
            $pdo->commit();

            // Redireciona para a página de transferências
            header('Location: transferencias.php');
        } catch (Exception $e) {
            // Se houver um erro, reverte a transação
            $pdo->rollBack();
            echo "Falha na transferência: " . $e->getMessage();
        }
    } else {
        echo "As contas de origem e destino devem ser diferentes.";
    }
}
?>
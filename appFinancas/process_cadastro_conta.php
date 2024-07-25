<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bank_name = $_POST['bank_name'];
    $account_number = $_POST['account_number'];
    $balance = $_POST['balance'];

    try {
        // Insere a nova conta no banco de dados
        $stmt = $pdo->prepare('INSERT INTO accounts (bank_name, account_number, balance) VALUES (?, ?, ?)');
        $stmt->execute([$bank_name, $account_number, $balance]);

        // Redireciona para a página de cadastro de contas com uma mensagem de sucesso
        header('Location: cadastro_conta.php?success=1');
    } catch (Exception $e) {
        echo "Falha no cadastro da conta: " . $e->getMessage();
    }
}
?>
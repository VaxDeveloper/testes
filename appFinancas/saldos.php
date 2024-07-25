<?php
include 'includes/db.php'; // Inclua o arquivo de conexão com o banco de dados

// Consulta para obter todos os saldos das contas
$sql = 'SELECT id, bank_name, account_number, balance FROM accounts';
$stmt = $pdo->query($sql);
$accounts = $stmt->fetchAll();

// Consulta para obter o saldo total
$totalBalanceQuery = 'SELECT SUM(balance) AS total_balance FROM accounts';
$totalStmt = $pdo->query($totalBalanceQuery);
$totalBalance = $totalStmt->fetch()['total_balance'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saldos das Contas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Saldos das Contas</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Banco</th>
                    <th>Número da Conta</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($accounts as $account): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($account['id']); ?></td>
                        <td><?php echo htmlspecialchars($account['bank_name']); ?></td>
                        <td><?php echo htmlspecialchars($account['account_number']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($account['balance'], 2, ',', '.')); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Card para saldo total -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Saldo Total</h5>
                <p class="card-text" style="font-size: 1.5rem;">
                    <?php echo 'R$ ' . number_format($totalBalance, 2, ',', '.'); ?>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
<?php
include 'includes/db.php'; // Inclua o arquivo de conexão com o banco de dados

$transactions = [];

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Consulta SQL para obter as transações no intervalo de datas
    $sql = 'SELECT t.id, t.account_id, a.bank_name, a.account_number, t.type, t.amount, t.date, t.description 
            FROM transactions t
            JOIN accounts a ON t.account_id = a.id
            WHERE t.date BETWEEN ? AND ?
            ORDER BY t.date';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$start_date, $end_date]);
    $transactions = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Movimentações</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Relatório de Movimentações</h2>
        
        <!-- Formulário de seleção de datas -->
        <form method="POST" class="mb-4">
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label for="start_date">Data Inicial</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>
                <div class="form-group col-md-5">
                    <label for="end_date">Data Final</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                </div>
                <div class="form-group col-md-2 align-self-end">
                    <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                </div>
            </div>
        </form>

        <!-- Tabela de transações -->
        <?php if (!empty($transactions)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Banco</th>
                        <th>Conta</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Data</th>
                        <th>Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($transaction['id']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['bank_name']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['account_number']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['type']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($transaction['amount'], 2, ',', '.')); ?></td>
                            <td><?php echo htmlspecialchars($transaction['date']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['description']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <p class="text-danger">Nenhuma transação encontrada para o período selecionado.</p>
        <?php endif; ?>
    </div>
</body>
</html>
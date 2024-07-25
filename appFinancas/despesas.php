<?php include 'includes/header.php'; ?>
<h2>Lançamento de Despesas</h2>
<form action="process_despesa.php" method="post">
    <div class="form-group">
        <label for="amount">Valor:</label>
        <input type="number" class="form-control" id="amount" name="amount" required>
    </div>
    <div class="form-group">
        <label for="date">Data:</label>
        <input type="date" class="form-control" id="date" name="date" required>
    </div>
    <div class="form-group">
        <label for="account">Conta:</label>
        <select class="form-control" id="account" name="account" required>
            <?php
            include 'includes/db.php';
            $stmt = $pdo->query('SELECT id, bank_name FROM accounts');
            while ($row = $stmt->fetch()) {
                echo "<option value=\"{$row['id']}\">{$row['bank_name']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="description">Descrição:</label>
        <textarea class="form-control" id="description" name="description" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Adicionar Despesa</button>
</form>
<?php include 'includes/footer.php'; ?>
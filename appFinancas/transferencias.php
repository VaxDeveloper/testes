<?php include 'includes/header.php'; ?>
<h2>Transferências</h2>
<form action="process_transferencia.php" method="post">
    <div class="form-group">
        <label for="from_account">De:</label>
        <select class="form-control" id="from_account" name="from_account" required>
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
        <label for="to_account">Para:</label>
        <select class="form-control" id="to_account" name="to_account" required>
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
        <label for="amount">Valor:</label>
        <input type="number" class="form-control" id="amount" name="amount" required>
    </div>
    <div class="form-group">
        <label for="date">Data:</label>
        <input type="date" class="form-control" id="date" name="date" required>
    </div>
    <div class="form-group">
        <label for="description">Descrição:</label>
        <textarea class="form-control" id="description" name="description" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Realizar Transferência</button>
</form>
<?php include 'includes/footer.php'; ?>
<?php include 'includes/header.php'; ?>
<h2>Cadastro de Conta Bancária</h2>
<form action="process_cadastro_conta.php" method="post">
    <div class="form-group">
        <label for="bank_name">Nome do Banco:</label>
        <input type="text" class="form-control" id="bank_name" name="bank_name" required>
    </div>
    <div class="form-group">
        <label for="account_number">Número da Conta:</label>
        <input type="text" class="form-control" id="account_number" name="account_number" required>
    </div>
    <div class="form-group">
        <label for="balance">Saldo Inicial:</label>
        <input type="number" class="form-control" id="balance" name="balance" required>
    </div>
    <button type="submit" class="btn btn-primary">Cadastrar Conta</button>
</form>
<?php include 'includes/footer.php'; ?>
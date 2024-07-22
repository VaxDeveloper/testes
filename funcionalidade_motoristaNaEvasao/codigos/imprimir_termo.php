<?php
// Configurações do banco de dados
$dbHost = "localhost";
$dbUser = "u219851065_admin";
$dbPassword = "Xavier364074$";
$dbName = "u219851065_smiguel";

// Criar conexão
$conexao = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

// Verificar conexão
if (!$conexao) {
    die("Falha na conexão: " . mysqli_connect_error());
}

if (isset($_GET['motorista']) && isset($_GET['valor']) && isset($_GET['datas'])) {
    $motorista = mysqli_real_escape_string($conexao, $_GET['motorista']);
    $valor_a_pagar = mysqli_real_escape_string($conexao, $_GET['valor']);
    $datas_ocorrencias = mysqli_real_escape_string($conexao, $_GET['datas']);
    
    // Buscar nome do motorista
    $query_motorista = "SELECT nome FROM cadastro_motorista WHERE matricula = '$motorista'";
    $resultado_motorista = mysqli_query($conexao, $query_motorista);
    $nome_motorista = '';
    if (mysqli_num_rows($resultado_motorista) > 0) {
        $row_motorista = mysqli_fetch_assoc($resultado_motorista);
        $nome_motorista = $row_motorista['nome'];
    }
    
    // Buscar ocorrências
    $query_ocorrencias = "SELECT id, data, motorista, descricao FROM ocorrencia_trafego WHERE motorista = '$motorista' AND ocorrencia = 'Evasão'";
    $resultado_ocorrencias = mysqli_query($conexao, $query_ocorrencias);
    $ocorrencias = [];
    if (mysqli_num_rows($resultado_ocorrencias) > 0) {
        while($row = mysqli_fetch_assoc($resultado_ocorrencias)) {
            $ocorrencias[] = $row;
        }
    }

    // Formatar a data atual
    $data_emissao = date('m/Y');
    $data_completa = date('d/m/Y');
} else {
    echo "Parâmetros não especificados.";
    exit;
}

mysqli_close($conexao);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emissão de Vales</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }

        #nav {
            margin-bottom: 30px;
        }

        img {
            max-width: 120px;
        }

        h1 {
            color: #dc3545; /* Cor vermelho para destacar o título */
        }
        .container {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }
        p {
            font-size: 1.0rem;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }

        .assinatura {
            display: flex;
            gap: 10%;
            padding-top: 10px;
        }

        .assinar {
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="nav">
            <img src="logo.png" alt="logo-sm">
            <h3 id="title">AUTORIZAÇÃO PARA DESCONTO EM FOLHA</h3>
        </div>

        <h3>Data: <strong><?php echo $data_completa; ?></strong></h3>

        <p>Pelo presente instrumento particular, que entre si fazem, de um lado como empregadora a firma 
            <strong>TRANSPORTE URBANO SÃO MIGUEL DE ILHÉUS LTDA</strong>, 
            representada pelo Dr. JOAO DUARTE ALVARENGA CARVALHO, e de outro lado o empregado Sr. 
            <strong><?php echo htmlspecialchars($nome_motorista); ?></strong>.
        </p><br>
        <p>O empregado no exercício de suas funções de MOTORISTA, autoriza a efetuar o desconto de <strong>R$ <?php echo number_format($valor_a_pagar, 2, ',', '.'); ?></strong> 
            em seu salario, através da Folha de Pagamento de <strong><?php echo $data_emissao; ?></strong>, referente
            à(s) <strong><?php echo count($ocorrencias); ?></strong> ocorrência(s) devidamente demonstrada(s) no relatório em anexo e comprovada(s) por
            gravação interna do veículo, as quais teve acesso.
        </p>
        <p>
            Declara o empregado que concorda com o relatado e com as orientações que lhe foram
            passadas no caso de reincidência.
        </p>
        <p>
            Declara ainda, estar ciente que em caso de demissão, terá descontados os referidos
            valores em sua rescisão contratual de trabalho.
        </p><br>
        
        <table>
            <tr>
                <th>OS</th>
                <th>Data</th>
                <th>Motorista</th>
                <th>Descrição</th>
            </tr>
            <?php foreach ($ocorrencias as $ocorrencia) { ?>
            <tr>
                <td><?php echo htmlspecialchars($ocorrencia['id']); ?></td>
                <td><?php echo htmlspecialchars($ocorrencia['data']); ?></td>
                <td><?php echo htmlspecialchars($ocorrencia['motorista']); ?></td>
                <td><?php echo htmlspecialchars($ocorrencia['descricao']); ?></td>
            </tr>
            <?php } ?>
        </table>
        
        <div class="assinatura">
            <div>
                <p class="assinar">Funcionário:</p>
                <P> ______________________________</P>
            </div>
            <div>
                <p class="assinar">Empresa:</p>
                <P>______________________________</P>
            </div>
        </div>
        <div class="assinatura">
            <div>
                <p class="assinar">Testemunha 1:</p>
                <P>______________________________</P>
                <p>CPF ou RG:</p>
                <P>______________________________</P>
            </div>
            <div>
                <p class="assinar">Testemunha 2:</p>
                <P>______________________________</P>
                <p>CPF ou RG:</p>
                <P>______________________________</P>
            </div>
        </div>
    </div>
</body>
</html>
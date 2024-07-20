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

if (isset($_GET['motorista'])) {
    $motorista = mysqli_real_escape_string($conexao, $_GET['motorista']);
    
    // Buscar detalhes do motorista
    $query = "SELECT * FROM ocorrencia_trafego WHERE motorista = '$motorista' AND ocorrencia = 'Evasão'";
    $resultado = mysqli_query($conexao, $query);

    if (mysqli_num_rows($resultado) > 0) {
        $total_ocorrencias = mysqli_num_rows($resultado);
        $valor_a_pagar = $total_ocorrencias * 4.80;
        
        echo "<h2>Detalhes do motorista: " . htmlspecialchars($motorista) . "</h2>";
        echo "<table border='1'>
                <tr>
                    <th>OS</th>
                    <th>Data</th>
                    <th>Motorista</th>
                    <th>Descrição</th>
                    <th>Vídeos</th>
                </tr>";
        while($row = mysqli_fetch_assoc($resultado)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['id']) . "</td>
                    <td>" . htmlspecialchars($row['data']) . "</td>
                    <td>" . htmlspecialchars($row['motorista']) . "</td>
                    <td>" . htmlspecialchars($row['descricao']) . "</td>
                    <td>";
            if ($row['video1']) {
                echo "<a href='visualizaVideoTr.php?video1=" . urlencode($row['id']) . "'>Vídeo-1</a><br>";
            }
            if ($row['video2']) {
                echo "<a href='visualizaVideoTr.php?video2=" . urlencode($row['id']) . "'>Vídeo-2</a><br>";
            }
            if ($row['video3']) {
                echo "<a href='visualizaVideoTr.php?video3=" . urlencode($row['id']) . "'>Vídeo-3</a><br>";
            }
            echo "</td>
                  </tr>";
        }
        echo "</table>";

        // Exibir o valor a pagar e o link para imprimir o termo
        echo "<p><strong>Total a pagar:</strong> R$" . number_format($valor_a_pagar, 2, ',', '.') . " <a href='imprimir_termo.html' target='_blank'>Imprimir Termo</a></p>";
    } else {
        echo "Nenhuma ocorrência de evasão encontrada para o motorista: " . htmlspecialchars($motorista);
    }
} else {
    echo "Motorista não especificado.";
}

mysqli_close($conexao);
?>
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
    $query_ocorrencia = "SELECT * FROM ocorrencia_trafego WHERE motorista = '$motorista' AND ocorrencia = 'Evasão'";
    $resultado_ocorrencia = mysqli_query($conexao, $query_ocorrencia);

    if (mysqli_num_rows($resultado_ocorrencia) > 0) {
        $total_ocorrencias = mysqli_num_rows($resultado_ocorrencia);
        $valor_a_pagar = $total_ocorrencias * 4.80;

        // Coletar as datas das ocorrências e contá-las
        $ocorrencias_por_data = [];
        while($row = mysqli_fetch_assoc($resultado_ocorrencia)) {
            $data = $row['data'];
            if (isset($ocorrencias_por_data[$data])) {
                $ocorrencias_por_data[$data]++;
            } else {
                $ocorrencias_por_data[$data] = 1;
            }
        }
        $datas_ocorrencias_str = '';
        foreach ($ocorrencias_por_data as $data => $quantidade) {
            $datas_ocorrencias_str .= $data ;
        }
        $datas_ocorrencias_str = rtrim($datas_ocorrencias_str, ', ');

        // Buscar nome do motorista
        $query_motorista = "SELECT nome FROM cadastro_motorista WHERE matricula = '$motorista'";
        $resultado_motorista = mysqli_query($conexao, $query_motorista);
        $nome_motorista = '';
        if (mysqli_num_rows($resultado_motorista) > 0) {
            $row_motorista = mysqli_fetch_assoc($resultado_motorista);
            $nome_motorista = $row_motorista['nome'];
        }
        
        echo "<h2>Detalhes do motorista: " . htmlspecialchars($nome_motorista) . "</h2>";
        echo "<table border='1'>
                <tr>
                    <th>OS</th>
                    <th>Data</th>
                    <th>Motorista</th>
                    <th>Descrição</th>
                    <th>Vídeos</th>
                </tr>";
        mysqli_data_seek($resultado_ocorrencia, 0);  // Resetar o ponteiro do resultado
        while($row = mysqli_fetch_assoc($resultado_ocorrencia)) {
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
        echo "<p><strong>Total a pagar:</strong> R$" . number_format($valor_a_pagar, 2, ',', '.') . " <a href='imprimir_termo.php?motorista=$motorista&valor=" . urlencode($valor_a_pagar) . "&datas=" . urlencode($datas_ocorrencias_str) . "' target='_blank'>Imprimir Termo</a></p>";
    } else {
        echo "Nenhuma ocorrência de evasão encontrada para o motorista: " . htmlspecialchars($motorista);
    }
} else {
    echo "Motorista não especificado.";
}

mysqli_close($conexao);
?>
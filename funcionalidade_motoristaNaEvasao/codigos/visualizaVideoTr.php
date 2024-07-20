<?php
// Verifica se pelo menos um dos parâmetros (video1, video2, video3) está presente na URL
if (isset($_GET['video1']) || isset($_GET['video2']) || isset($_GET['video3'])) {
    // Define qual vídeo será exibido
    if (isset($_GET['video1'])) {
        $video_id = intval($_GET['video1']);
        $video_column = 'video1';
    } elseif (isset($_GET['video2'])) {
        $video_id = intval($_GET['video2']);
        $video_column = 'video2';
    } else {
        $video_id = intval($_GET['video3']);
        $video_column = 'video3';
    }

    // Importa as configurações do banco de dados
    require_once 'database.php';

    if (!$conexao) {
        die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
    }

    // Inicializa as variáveis de caminho do arquivo
    $caminho_arquivo1 = '';
    $caminho_arquivo2 = '';
    $caminho_arquivo3 = '';

    // Consulta para obter o caminho do vídeo pelo ID
    $consulta_sql = "SELECT video1, video2, video3 FROM u219851065_smiguel.ocorrencia_trafego WHERE id = ?";
    $stmt = mysqli_prepare($conexao, $consulta_sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $video_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $caminho_arquivo1, $caminho_arquivo2, $caminho_arquivo3);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Depuração: Verificar os valores retornados
        // echo "video1: $caminho_arquivo1<br>";
        // echo "video2: $caminho_arquivo2<br>";
        // echo "video3: $caminho_arquivo3<br>";

        // Corrigir extensão do arquivo, se necessário
        if (strpos($caminho_arquivo1, '.mp4') === false) {
            $caminho_arquivo1 = preg_replace('/\.avi(-.mp4)?$/', '.mp4', $caminho_arquivo1);
        }
        if (strpos($caminho_arquivo2, '.mp4') === false) {
            $caminho_arquivo2 = preg_replace('/\.avi(-.mp4)?$/', '.mp4', $caminho_arquivo2);
        }
        if (strpos($caminho_arquivo3, '.mp4') === false) {
            $caminho_arquivo3 = preg_replace('/\.avi(-.mp4)?$/', '.mp4', $caminho_arquivo3);
        }

        // Verifica e exibe o vídeo correspondente
        if ($video_column == 'video1' && !empty($caminho_arquivo1)) {
            $video_path = realpath("../../bkp/_saomigueldeilheus/videos/" . basename($caminho_arquivo1));
        } elseif ($video_column == 'video2' && !empty($caminho_arquivo2)) {
            $video_path = realpath("../../bkp/_saomigueldeilheus/videos/" . basename($caminho_arquivo2));
        } elseif ($video_column == 'video3' && !empty($caminho_arquivo3)) {
            $video_path = realpath("../../bkp/_saomigueldeilheus/videos/" . basename($caminho_arquivo3));
        } else {
            $video_path = null;
        }

        // Depuração: Verificar o caminho do vídeo
        // echo "video_path: $video_path<br>";

        if ($video_path && file_exists($video_path)) {
            // Obter a URL do vídeo
            $video_url = str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', $video_path);
            $video_url = str_replace('\\', '/', $video_url); // Corrigir para caminhos no Windows

            // Exibe o vídeo
            echo '<video width="600" controls>
                    <source src="' . $video_url . '" type="video/mp4">
                    Seu navegador não suporta o elemento de vídeo.
                  </video>';
        } else {
            // Verificar se o caminho do vídeo é correto
            echo "Caminho do vídeo esperado: $video_path<br>";
            echo "Arquivo não encontrado ou vídeo não especificado corretamente.";
        }
    } else {
        echo "Erro na preparação da consulta: " . mysqli_error($conexao);
    }

    mysqli_close($conexao);
} else {
    echo "Nenhum vídeo especificado.";
}
?>
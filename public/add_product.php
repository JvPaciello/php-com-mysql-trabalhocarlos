<?php
session_start();
include '../includes/config.php';
include '../includes/functions.php';
include '../includes/header.php';
include '../includes/start_session.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = trim($_POST['preco']);
    $estoque = trim($_POST['estoque']);
    $fabricante = trim($_POST['fabricante']);
    $imagem = '';

    // Processa o upload da imagem
    if (isset($_FILES['imagem'])) {
        // Verifica o código de erro do upload
        if ($_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
            $imagem = $_FILES['imagem']['name'];
            $imagem_tmp = $_FILES['imagem']['tmp_name'];
            $imagem_destino = "../uploads/" . $imagem;

            // Verifica se o arquivo é realmente uma imagem
            $check = getimagesize($imagem_tmp);
            if ($check !== false) {
                // Move o arquivo para o diretório de uploads
                if (move_uploaded_file($imagem_tmp, $imagem_destino)) {
                    // Adiciona o produto
                    if (adicionar_produto($nome, $descricao, $preco, $imagem, $estoque, $fabricante)) {
                        header("Location: index.php");
                        exit;
                    } else {
                        $error = "Erro ao adicionar o produto!";
                    }
                } else {
                    $error = "Falha ao mover o arquivo para o diretório de uploads.";
                }
            } else {
                $error = "O arquivo enviado não é uma imagem válida.";
            }
        } else {
            // Trata erros específicos de upload
            switch ($_FILES['imagem']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $error = "O arquivo excede o tamanho máximo permitido.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $error = "O upload do arquivo foi feito parcialmente.";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $error = "Nenhum arquivo foi enviado.";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $error = "Pasta temporária ausente.";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $error = "Falha ao escrever o arquivo no disco.";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $error = "O upload do arquivo foi interrompido por uma extensão.";
                    break;
                default:
                    $error = "Erro desconhecido ao enviar o arquivo.";
                    break;
            }
        }
    } else {
        $error = "Erro: nenhum arquivo foi enviado.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Adicionar Produto</title>
</head>
<body>
    <main>
        <h1>Adicionar Produto</h1>
        <form method="post" enctype="multipart/form-data">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>
            
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" required></textarea>
            
            <label for="preco">Preço:</label>
            <input type="text" id="preco" name="preco" required>
            
            <label for="estoque">Estoque:</label>
            <input type="number" id="estoque" name="estoque" min="0" required>
            
            <label for="fabricante">Fabricante:</label>
            <input type="text" id="fabricante" name="fabricante" required>
            
            <label for="imagem">Imagem:</label>
            <input type="file" id="imagem" name="imagem" required>
            
            <button type="submit">Adicionar Produto</button>
            <?php if (isset($error)) echo "<p>$error</p>"; ?>
        </form>
    </main>
</body>
</html>

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
    $imagem = '';

    // Processa o upload da imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
        $imagem = $_FILES['imagem']['name'];
        $imagem_tmp = $_FILES['imagem']['tmp_name'];
        $imagem_destino = "../uploads/" . $imagem;

        // Move o arquivo para o diretório de uploads
        if (move_uploaded_file($imagem_tmp, $imagem_destino)) {
            // Adiciona o produto
            if (adicionar_produto($nome, $descricao, $preco, $imagem)) {
                header("Location: index.php");
                exit;
            } else {
                $error = "Erro ao adicionar o produto!";
            }
        } else {
            $error = "Falha ao mover o arquivo para o diretório de uploads.";
        }
    } else {
        $error = "Erro no upload da imagem.";
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
    <header>
        <a href="index.php">Início</a>
        <a href="login.php">Login</a>
        <a href="register.php">Cadastro</a>
        <a href="add_product.php">Adicionar Produto</a>
    </header>
    <main>
        <h1>Adicionar Produto</h1>
        <form method="post" enctype="multipart/form-data">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" required></textarea>
            <label for="preco">Preço:</label>
            <input type="text" id="preco" name="preco" required>
            <label for="imagem">Imagem:</label>
            <input type="file" id="imagem" name="imagem" required>
            <button type="submit">Adicionar Produto</button>
            <?php if (isset($error)) echo "<p>$error</p>"; ?>
        </form>
    </main>
</body>
</html>

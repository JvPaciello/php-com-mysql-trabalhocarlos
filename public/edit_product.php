<?php
include '../includes/config.php';
include '../includes/functions.php';
include '../includes/header.php';
include '../includes/start_session.php';

// Verifica se o usuário está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Verifica se o ID do produto foi passado via GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = trim($_POST['preco']);
    $imagem = $_FILES['imagem']['name'];

    if (!empty($nome) && !empty($descricao) && !empty($preco)) {
        // Verifica se há uma nova imagem e move para o diretório
        if (!empty($imagem)) {
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($imagem);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Checa se o arquivo é uma imagem real
            $check = getimagesize($_FILES['imagem']['tmp_name']);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $error = "O arquivo não é uma imagem.";
                $uploadOk = 0;
            }

            // Checa se o arquivo já existe
            if (file_exists($target_file)) {
                $error = "O arquivo já existe.";
                $uploadOk = 0;
            }

            // Limita o tamanho do arquivo
            if ($_FILES['imagem']['size'] > 500000) {
                $error = "O arquivo é muito grande.";
                $uploadOk = 0;
            }

            // Limita os tipos de arquivo permitidos
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $error = "Apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
                $uploadOk = 0;
            }

            // Verifica se $uploadOk é igual a 0 por algum erro
            if ($uploadOk == 0) {
                $error = "O arquivo não foi enviado.";
            } else {
                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $target_file)) {
                    // Atualiza o produto com a nova imagem
                    $stmt = $conn->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, imagem = ? WHERE id = ?");
                    $stmt->bind_param("ssdsi", $nome, $descricao, $preco, $imagem, $id);
                } else {
                    $error = "Erro ao enviar o arquivo.";
                }
            }
        } else {
            // Atualiza o produto sem alterar a imagem
            $stmt = $conn->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ? WHERE id = ?");
            $stmt->bind_param("ssdi", $nome, $descricao, $preco, $id);
        }

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Erro ao atualizar o produto.";
        }
    } else {
        $error = "Preencha todos os campos!";
    }
}

// Obtém os detalhes do produto
$stmt = $conn->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit;
}

$produto = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Editar Produto</title>
</head>
<body>
    <main>
        <h1>Editar Produto</h1>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
        <form method="post" enctype="multipart/form-data">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" required><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
            <label for="preco">Preço:</label>
            <input type="number" id="preco" name="preco" step="0.01" value="<?php echo htmlspecialchars($produto['preco']); ?>" required>
            <label for="imagem">Imagem:</label>
            <input type="file" id="imagem" name="imagem">
            <button type="submit">Atualizar Produto</button>
        </form>
    </main>
</body>
</html>

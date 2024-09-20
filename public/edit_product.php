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
    $estoque = trim($_POST['estoque']);
    $fabricante = trim($_POST['fabricante']);

    // Debug: imprime os valores recebidos
    var_dump($nome, $descricao, $preco, $estoque, $fabricante);

    if (!empty($nome) && !empty($descricao) && !empty($preco) && isset($estoque) && !empty($fabricante)) {
        // Atualiza o produto sem alterar a imagem
        if (editar_produto($id, $nome, $descricao, $preco, null, $estoque, $fabricante)) {
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
        <form method="post">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
            
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" required><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
            
            <label for="preco">Preço:</label>
            <input type="number" id="preco" name="preco" step="0.01" value="<?php echo htmlspecialchars($produto['preco']); ?>" required>
            
            <label for="estoque">Estoque:</label>
            <input type="number" id="estoque" name="estoque" min="0" value="<?php echo htmlspecialchars($produto['estoque']); ?>" required>
            
            <label for="fabricante">Fabricante:</label>
            <input type="text" id="fabricante" name="fabricante" value="<?php echo htmlspecialchars($produto['fabricante']); ?>" required>
            
            <button type="submit">Atualizar Produto</button>
        </form>
    </main>
</body>
</html>

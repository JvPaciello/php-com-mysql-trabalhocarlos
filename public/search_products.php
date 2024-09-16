<?php
include '../includes/start_session.php'; // Inclua a inicialização da sessão aqui
include '../includes/config.php';
include '../includes/functions.php';
include '../includes/header.php'; // Inclua o cabeçalho

// Inicialize variáveis de pesquisa
$id = '';
$descricao = '';
$preco = '';
$produtos = [];

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = trim($_POST['id'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $preco = trim($_POST['preco'] ?? '');

    // Construa a consulta SQL com base nos parâmetros fornecidos
    $sql = "SELECT * FROM produtos WHERE 1=1";
    $params = [];

    if (!empty($id)) {
        $sql .= " AND id = ?";
        $params[] = $id;
    }
    if (!empty($descricao)) {
        $sql .= " AND descricao LIKE ?";
        $params[] = "%" . $descricao . "%";
    }
    if (!empty($preco)) {
        $sql .= " AND preco = ?";
        $params[] = $preco;
    }

    $stmt = $conn->prepare($sql);
    
    // Bind os parâmetros
    if (!empty($params)) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $produtos = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Consultar Produtos</title>
</head>
<body>
    <main>
        <h1>Consultar Produtos</h1>
        <form method="post">
            <label for="id">ID:</label>
            <input type="text" id="id" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" name="descricao" value="<?php echo htmlspecialchars($descricao); ?>">
            <label for="preco">Preço:</label>
            <input type="number" id="preco" name="preco" step="0.01" value="<?php echo htmlspecialchars($preco); ?>">
            <button type="submit">Pesquisar</button>
        </form>

        <?php if (!empty($produtos)): ?>
            <h2>Resultados da Pesquisa</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Preço</th>
                        <th>Imagem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($produto['id']); ?></td>
                            <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                            <td><?php echo htmlspecialchars($produto['descricao']); ?></td>
                            <td><?php echo htmlspecialchars($produto['preco']); ?></td>
                            <td>
                                <?php if (!empty($produto['imagem'])): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="Imagem do Produto" width="100">
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <p>Nenhum produto encontrado.</p>
        <?php endif; ?>
    </main>
</body>
</html>

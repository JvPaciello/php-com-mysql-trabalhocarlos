<?php
include '../includes/config.php';
include '../includes/functions.php';
include '../includes/header.php';
include '../includes/start_session.php';
// Busca todos os produtos
$result = $conn->query("SELECT * FROM produtos");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Início</title>
</head>
<body>
    
    <main>
        <h1>Produtos</h1>
        <ul>
            <?php while ($produto = $result->fetch_assoc()): ?>
                <li>
                    <h2><?php echo htmlspecialchars($produto['nome']); ?></h2>
                    <p><?php echo htmlspecialchars($produto['descricao']); ?></p>
                    <p>Preço: R$ <?php echo htmlspecialchars($produto['preco']); ?></p>
                    <?php if ($produto['imagem']): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" style="max-width: 200px;">
                    <?php endif; ?>
                    <a href="edit_product.php?id=<?php echo $produto['id']; ?>">Editar</a>
                </li>
            <?php endwhile; ?>
        </ul>
    </main>
</body>
</html>

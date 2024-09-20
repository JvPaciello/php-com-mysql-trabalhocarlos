<?php

include '../includes/start_session.php'; // Inclua a inicialização da sessão aqui
include '../includes/config.php';
include '../includes/functions.php';
include '../includes/header.php'; // Inclua o cabeçalho

// Consulta os produtos
$sql = "SELECT * FROM produtos";
$result = $conn->query($sql);
$produtos = $result->fetch_all(MYSQLI_ASSOC);
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
    <!-- Botão de Filtro no canto superior esquerdo -->
    <a href="search_products.php" class="btn-filter" title="Filtrar Produtos">Filtrar Produtos</a>

    <main>
        <h1>Bem-vindo à Nossa Loja</h1>
        <div class="product-container">
            <?php foreach ($produtos as $produto): ?>
                <div class="product-card">
                    <?php if (!empty($produto['imagem'])): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="Imagem do Produto">
                    <?php endif; ?>
                    <div class="info">
                        <h2><?php echo htmlspecialchars($produto['nome']); ?></h2>
                        <p><?php echo htmlspecialchars($produto['descricao']); ?></p>
                        <p>Fabricante: <?php echo htmlspecialchars($produto['fabricante']); ?></p>
                        <p>Estoque: <?php echo htmlspecialchars($produto['estoque']); ?></p>
                        <p>R$ <?php echo htmlspecialchars(number_format($produto['preco'], 2, ',', '.')); ?></p>
                        <a href="edit_product.php?id=<?php echo htmlspecialchars($produto['id']); ?>" class="btn-edit">Editar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>

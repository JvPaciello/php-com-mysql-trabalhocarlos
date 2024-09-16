<?php
include 'start_session.php'; // Inclua a inicialização da sessão aqui
?>
<header>
    <nav>
        <a href="index.php">Início</a>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="search_products.php">Consultar Produtos</a>
            <a href="add_product.php">Adicionar Produto</a>
            <a href="logout.php">Sair</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Cadastro</a>
        <?php endif; ?>
    </nav>
</header>

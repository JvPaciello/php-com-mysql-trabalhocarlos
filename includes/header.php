<?php
include 'start_session.php'; // Inclui o arquivo de inicialização da sessão
?>
<header>
    <nav>
        <a href="index.php">Início</a>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="profile.php">Perfil</a>
            <a href="logout.php">Sair</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Cadastro</a>
        <?php endif; ?>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="add_product.php">Adicionar Produto</a>
        <?php endif; ?>
    </nav>
</header>

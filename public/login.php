<?php
session_start();
include '../includes/config.php';
include '../includes/functions.php';
include '../includes/header.php';
include '../includes/start_session.php';
// Redireciona se o usuário já estiver logado
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Verifique se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    // Verifica se o e-mail e a senha não estão vazios
    if (!empty($email) && !empty($senha)) {
        if (verificar_login($email, $senha)) {
            $_SESSION['username'] = $email;
            header("Location: index.php");
            exit;
        } else {
            $error = "E-mail ou senha inválidos!";
        }
    } else {
        $error = "Por favor, preencha todos os campos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Login</title>
</head>
<body>
   
    <main>
        <h1>Login</h1>
        <form method="post">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
            <button type="submit">Entrar</button>
            <?php if (isset($error)) echo "<p>$error</p>"; ?>
        </form>
    </main>
</body>
</html>

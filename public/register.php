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

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

    // Verifica se o e-mail e a senha não estão vazios
    if (!empty($email) && !empty($senha)) {
        // Verifica se o e-mail já está cadastrado
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            // Adiciona o novo usuário
            $stmt = $conn->prepare("INSERT INTO usuarios (email, senha) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $senha_hash);
            if ($stmt->execute()) {
                $_SESSION['username'] = $email;
                header("Location: index.php");
                exit;
            } else {
                $error = "Erro ao registrar o usuário!";
            }
        } else {
            $error = "E-mail já cadastrado!";
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
    <title>Cadastro</title>
</head>
<body>
    <main>
        <h1>Cadastro</h1>
        <form method="post">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
            <button type="submit">Cadastrar</button>
            <?php if (isset($error)) echo "<p>$error</p>"; ?>
        </form>
    </main>
</body>
</html>

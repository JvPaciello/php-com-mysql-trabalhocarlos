<?php
// Função para verificar login
function verificar_login($email, $senha) {
    global $conn;

    // Prepara a consulta para buscar o usuário pelo e-mail
    $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE email = ?");
    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o usuário existe
    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        $senha_hash = $usuario['senha'];

        // Verifica a senha fornecida com o hash armazenado
        if (password_verify($senha, $senha_hash)) {
            return true;
        }
    }

    return false;
}

// Função para adicionar produto
function adicionar_produto($nome, $descricao, $preco, $imagem) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO produtos (nome, descricao, preco, imagem) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . $conn->error);
    }
    $stmt->bind_param("ssis", $nome, $descricao, $preco, $imagem);
    return $stmt->execute();
}

// Função para editar produto
function editar_produto($id, $nome, $descricao, $preco, $imagem) {
    global $conn;
    if ($imagem) {
        $stmt = $conn->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, imagem = ? WHERE id = ?");
        if ($stmt === false) {
            die('Erro na preparação da consulta: ' . $conn->error);
        }
        $stmt->bind_param("ssisi", $nome, $descricao, $preco, $imagem, $id);
    } else {
        $stmt = $conn->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ? WHERE id = ?");
        if ($stmt === false) {
            die('Erro na preparação da consulta: ' . $conn->error);
        }
        $stmt->bind_param("ssii", $nome, $descricao, $preco, $id);
    }
    return $stmt->execute();
}
?>

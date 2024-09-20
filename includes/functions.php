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


    // Debugging log
    error_log("Tentativa de adicionar produto: $nome, $descricao, $preco, $imagem, $estoque, $fabricante");

    $stmt->bind_param("ssdiss", $nome, $descricao, $preco, $imagem, $estoque, $fabricante);
    
    if (!$stmt->execute()) {
        error_log("Erro ao adicionar produto: " . $stmt->error);
        return false;
    }

    return true;
}

// Função para editar produto
function editar_produto($id, $nome, $descricao, $preco, $imagem, $estoque, $fabricante) {
    global $conn;
    if ($imagem) {
        $stmt = $conn->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, imagem = ?, estoque = ?, fabricante = ? WHERE id = ?");
        if ($stmt === false) {
            die('Erro na preparação da consulta: ' . $conn->error);
        }
        $stmt->bind_param("ssdissi", $nome, $descricao, $preco, $imagem, $estoque, $fabricante, $id);
    } else {
        $stmt = $conn->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, estoque = ?, fabricante = ? WHERE id = ?");
        if ($stmt === false) {
            die('Erro na preparação da consulta: ' . $conn->error);
        }
        $stmt->bind_param("ssissi", $nome, $descricao, $preco, $estoque, $fabricante, $id);
    }
    return $stmt->execute();
}
?>

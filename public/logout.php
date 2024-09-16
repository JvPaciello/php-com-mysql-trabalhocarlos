<?php
session_start();

// Remove todas as variáveis de sessão
$_SESSION = array();

// Se você deseja destruir completamente a sessão, exclua o cookie de sessão também
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

// Por fim, destrua a sessão
session_destroy();

// Redireciona para a página inicial
header("Location: index.php");
exit;

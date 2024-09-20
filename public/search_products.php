<?php
include '../includes/config.php';
include '../includes/functions.php';
include '../includes/header.php';
include '../includes/start_session.php';


function buscarProdutosPorDescricao($descricao, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE descricao LIKE ?");
    $stmt->execute(['%' . $descricao . '%']);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarProdutosPorPreco($precoMin, $precoMax, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE preco BETWEEN ? AND ?");
    $stmt->execute([$precoMin, $precoMax]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarProdutosComEstoqueZerado($pdo) {
    $stmt = $pdo->query("SELECT * FROM produtos WHERE estoque = 0");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarProdutosPorFabricante($fabricante, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE fabricante LIKE ?");
    $stmt->execute(['%' . $fabricante . '%']);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$resultado = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoBusca = $_POST['tipo_busca'];

    switch ($tipoBusca) {
        case 'descricao':
            $descricao = $_POST['descricao'] ?? '';
            $resultado = buscarProdutosPorDescricao($descricao, $pdo);
            break;
        case 'preco':
            $precoMin = $_POST['preco_min'] ?? 0;
            $precoMax = $_POST['preco_max'] ?? 0;
            $resultado = buscarProdutosPorPreco($precoMin, $precoMax, $pdo);
            break;
        case 'estoque_zerado':
            $resultado = buscarProdutosComEstoqueZerado($pdo);
            break;
        case 'fabricante':
            $fabricante = $_POST['fabricante'] ?? '';
            $resultado = buscarProdutosPorFabricante($fabricante, $pdo);
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style2.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <title>Busca de Produtos</title>
</head>
<body>
<main>
    <h1>Buscar Produtos</h1>

    <form method="POST">
        <div>
            <label for="tipo_busca">Selecione o tipo de busca:</label>
            <select name="tipo_busca" id="tipo_busca" required>
                <option value="descricao">Buscar por descrição</option>
                <option value="preco">Buscar por preço</option>
                <option value="estoque_zerado">Produtos com estoque zerado</option>
                <option value="fabricante">Buscar por fabricante</option>
            </select>
        </div>

        <div id="busca_descricao" class="busca_opcao">
            <label for="descricao">Descrição:</label>
            <input type="text" name="descricao" id="descricao">
        </div>

        <div id="busca_preco" class="busca_opcao">
            <label for="preco_min">Preço mínimo:</label>
            <input type="number" step="0.01" name="preco_min" id="preco_min">

            <label for="preco_max">Preço máximo:</label>
            <input type="number" step="0.01" name="preco_max" id="preco_max">
        </div>

        <div id="busca_fabricante" class="busca_opcao">
            <label for="fabricante">Fabricante:</label>
            <input type="text" name="fabricante" id="fabricante">
        </div>

        <button type="submit">Buscar</button>
    </form>

    <h2>Resultados</h2>
    <?php if ($resultado): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Fabricante</th>
            </tr>
            <?php foreach ($resultado as $produto): ?>
            <tr>
                <td><?php echo $produto['id']; ?></td>
                <td><?php echo $produto['nome']; ?></td>
                <td><?php echo $produto['descricao']; ?></td>
                <td><?php echo $produto['preco']; ?></td>
                <td><?php echo $produto['estoque']; ?></td>
                <td><?php echo $produto['fabricante']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nenhum resultado encontrado.</p>
    <?php endif; ?>

    <script>
        document.getElementById('tipo_busca').addEventListener('change', function () {
            const tipoBusca = this.value;
            document.querySelectorAll('.busca_opcao').forEach(opcao => opcao.style.display = 'none');
            if (tipoBusca === 'descricao') {
                document.getElementById('busca_descricao').style.display = 'block';
            } else if (tipoBusca === 'preco') {
                document.getElementById('busca_preco').style.display = 'block';
            } else if (tipoBusca === 'fabricante') {
                document.getElementById('busca_fabricante').style.display = 'block';
            }
        });

        document.getElementById('tipo_busca').dispatchEvent(new Event('change'));
    </script>
</main>

</body>
</html>

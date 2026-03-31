<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../conexao.php");

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_cliente = $_POST["cliente"];
    $id_produto = $_POST["produto"];
    $quantidade = $_POST["quantidade"];

    // Buscar o produto no banco
    $sql_produto = "SELECT * FROM produtos WHERE id_produto = $id_produto";
    $resultado_produto = mysqli_query($conexao, $sql_produto);

    if ($resultado_produto && mysqli_num_rows($resultado_produto) > 0) {

        $produto = mysqli_fetch_assoc($resultado_produto);

        $nome_produto = $produto["nome"];
        $preco = $produto["preco"];
        $estoque_atual = $produto["estoque"];

        // Verificar estoque
        if ($quantidade <= 0) {
            $mensagem = "A quantidade deve ser maior que zero.";
        } elseif ($estoque_atual < $quantidade) {
            $mensagem = "Estoque insuficiente para o produto: " . $nome_produto;
        } else {

            $subtotal = $preco * $quantidade;

            // Inserir venda
            $sql_venda = "INSERT INTO vendas (id_cliente, valor_total)
                          VALUES ($id_cliente, $subtotal)";
            $resultado_venda = mysqli_query($conexao, $sql_venda);

            if ($resultado_venda) {

                // Pegar o id da venda criada
                $id_venda = mysqli_insert_id($conexao);

                // Inserir item da venda
                $sql_item = "INSERT INTO itens_venda (id_venda, id_produto, quantidade, subtotal)
                             VALUES ($id_venda, $id_produto, $quantidade, $subtotal)";
                $resultado_item = mysqli_query($conexao, $sql_item);

                if ($resultado_item) {

                    // Atualizar estoque
                    $novo_estoque = $estoque_atual - $quantidade;

                    $sql_estoque = "UPDATE produtos
                                    SET estoque = $novo_estoque
                                    WHERE id_produto = $id_produto";
                    $resultado_estoque = mysqli_query($conexao, $sql_estoque);

                    if ($resultado_estoque) {
                        $mensagem = "Venda registrada com sucesso!";
                    } else {
                        $mensagem = "Erro ao atualizar estoque: " . mysqli_error($conexao);
                    }

                } else {
                    $mensagem = "Erro ao inserir item da venda: " . mysqli_error($conexao);
                }

            } else {
                $mensagem = "Erro ao registrar venda: " . mysqli_error($conexao);
            }
        }

    } else {
        $mensagem = "Produto não encontrado.";
    }
}
?>

<h1>Registrar Venda</h1>

<?php
if ($mensagem != "") {
    echo "<p><strong>$mensagem</strong></p>";
}
?>

<form method="POST">

    Cliente:
    <select name="cliente">
        <?php
        $sql_clientes = "SELECT * FROM clientes";
        $resultado_clientes = mysqli_query($conexao, $sql_clientes);

        while ($c = mysqli_fetch_assoc($resultado_clientes)) {
            echo "<option value='" . $c["id_cliente"] . "'>" . $c["nome"] . "</option>";
        }
        ?>
    </select>

    <br><br>

    Produto:
    <select name="produto">
        <?php
        $sql_produtos = "SELECT * FROM produtos";
        $resultado_produtos = mysqli_query($conexao, $sql_produtos);

        while ($p = mysqli_fetch_assoc($resultado_produtos)) {
            echo "<option value='" . $p["id_produto"] . "'>" . $p["nome"] . "</option>";
        }
        ?>
    </select>

    <br><br>

    Quantidade:
    <input type="number" name="quantidade" required min="1">

    <br><br>

    <button type="submit">Registrar</button>

</form>
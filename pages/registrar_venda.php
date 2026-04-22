<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../conexao.php");
include("../includes/header.php");
include("../includes/menu.php");

if (!isset($_SESSION["carrinho"])) {
    $_SESSION["carrinho"] = [];
}

$mensagem = "";
$total_geral = 0;

// Adicionar item ao carrinho
if (isset($_POST["adicionar_item"])) {
    $id_cliente = $_POST["cliente"];
    $id_produto = $_POST["produto"];
    $quantidade = (int) $_POST["quantidade"];

    $sql_produto = "SELECT * FROM produtos WHERE id_produto = $id_produto";
    $resultado_produto = mysqli_query($conexao, $sql_produto);

    if ($resultado_produto && mysqli_num_rows($resultado_produto) > 0) {
        $produto = mysqli_fetch_assoc($resultado_produto);

        $nome_produto = $produto["nome"];
        $preco = (float) $produto["preco"];
        $estoque = (int) $produto["estoque"];

        if ($quantidade <= 0) {
            $mensagem = "A quantidade deve ser maior que zero.";
        } elseif ($quantidade > $estoque) {
            $mensagem = "Estoque insuficiente para o produto: " . $nome_produto;
        } else {
            $subtotal = $preco * $quantidade;

            $_SESSION["cliente_venda"] = $id_cliente;
            $_SESSION["carrinho"][] = [
                "id_produto" => $id_produto,
                "nome" => $nome_produto,
                "preco" => $preco,
                "quantidade" => $quantidade,
                "subtotal" => $subtotal
            ];

            $mensagem = "Item adicionado com sucesso.";
        }
    } else {
        $mensagem = "Produto não encontrado.";
    }
}

// Remover item do carrinho
if (isset($_GET["remover"])) {
    $indice = (int) $_GET["remover"];

    if (isset($_SESSION["carrinho"][$indice])) {
        unset($_SESSION["carrinho"][$indice]);
        $_SESSION["carrinho"] = array_values($_SESSION["carrinho"]);
        $mensagem = "Item removido com sucesso.";
    }
}

// Finalizar venda
if (isset($_POST["finalizar_venda"])) {
    if (!isset($_SESSION["cliente_venda"]) || count($_SESSION["carrinho"]) == 0) {
        $mensagem = "Adicione pelo menos um item antes de finalizar.";
    } else {
        $id_cliente = $_SESSION["cliente_venda"];
        $total_venda = 0;

        foreach ($_SESSION["carrinho"] as $item) {
            $total_venda += $item["subtotal"];
        }

        $sql_venda = "INSERT INTO vendas (id_cliente, valor_total) VALUES ($id_cliente, $total_venda)";
        $resultado_venda = mysqli_query($conexao, $sql_venda);

        if ($resultado_venda) {
            $id_venda = mysqli_insert_id($conexao);
            $erro = false;

            foreach ($_SESSION["carrinho"] as $item) {
                $id_produto = $item["id_produto"];
                $quantidade = $item["quantidade"];
                $subtotal = $item["subtotal"];

                $sql_busca = "SELECT estoque FROM produtos WHERE id_produto = $id_produto";
                $resultado_busca = mysqli_query($conexao, $sql_busca);
                $produto_atual = mysqli_fetch_assoc($resultado_busca);
                $estoque_atual = (int) $produto_atual["estoque"];

                if ($quantidade > $estoque_atual) {
                    $erro = true;
                    $mensagem = "Erro: estoque insuficiente durante a finalização da venda.";
                    break;
                }

                $sql_item = "INSERT INTO itens_venda (id_venda, id_produto, quantidade, subtotal)
                             VALUES ($id_venda, $id_produto, $quantidade, $subtotal)";
                $resultado_item = mysqli_query($conexao, $sql_item);

                if (!$resultado_item) {
                    $erro = true;
                    $mensagem = "Erro ao inserir item da venda: " . mysqli_error($conexao);
                    break;
                }

                $novo_estoque = $estoque_atual - $quantidade;
                $sql_update = "UPDATE produtos SET estoque = $novo_estoque WHERE id_produto = $id_produto";
                $resultado_update = mysqli_query($conexao, $sql_update);

                if (!$resultado_update) {
                    $erro = true;
                    $mensagem = "Erro ao atualizar estoque: " . mysqli_error($conexao);
                    break;
                }
            }

            if (!$erro) {
                $mensagem = "Venda finalizada com sucesso!";
                $_SESSION["carrinho"] = [];
                unset($_SESSION["cliente_venda"]);
            }
        } else {
            $mensagem = "Erro ao registrar venda: " . mysqli_error($conexao);
        }
    }
}
?>

<div class="container">
    <h2>Registrar Venda</h2>

    <?php if ($mensagem != "") { ?>
        <div class="mensagem"><?php echo $mensagem; ?></div>
    <?php } ?>

    <form method="POST">
        <label>Cliente</label>
        <select name="cliente" required>
            <?php
            $cliente_selecionado = $_SESSION["cliente_venda"] ?? "";
            $sql_clientes = "SELECT * FROM clientes";
            $resultado_clientes = mysqli_query($conexao, $sql_clientes);

            while ($c = mysqli_fetch_assoc($resultado_clientes)) {
                $selected = ($cliente_selecionado == $c["id_cliente"]) ? "selected" : "";
                echo "<option value='" . $c["id_cliente"] . "' $selected>" . $c["nome"] . "</option>";
            }
            ?>
        </select>

        <label>Produto</label>
        <select name="produto" required>
            <?php
            $sql_produtos = "SELECT * FROM produtos";
            $resultado_produtos = mysqli_query($conexao, $sql_produtos);

            while ($p = mysqli_fetch_assoc($resultado_produtos)) {
                echo "<option value='" . $p["id_produto"] . "'>" . $p["nome"] . " - R$ " . number_format($p["preco"], 2, ",", ".") . "</option>";
            }
            ?>
        </select>

        <label>Quantidade</label>
        <input type="number" name="quantidade" required min="1">

        <button type="submit" name="adicionar_item">Adicionar item</button>
    </form>

    <h2>Itens da Venda</h2>

    <?php if (count($_SESSION["carrinho"]) > 0) { ?>
        <table>
            <tr>
                <th>Produto</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Subtotal</th>
                <th>Ação</th>
            </tr>

            <?php foreach ($_SESSION["carrinho"] as $indice => $item) { ?>
                <?php $total_geral += $item["subtotal"]; ?>
                <tr>
                    <td><?php echo $item["nome"]; ?></td>
                    <td>R$ <?php echo number_format($item["preco"], 2, ",", "."); ?></td>
                    <td><?php echo $item["quantidade"]; ?></td>
                    <td>R$ <?php echo number_format($item["subtotal"], 2, ",", "."); ?></td>
                    <td>
                        <a class="link-botao" href="registrar_venda.php?remover=<?php echo $indice; ?>">Remover</a>
                    </td>
                </tr>
            <?php } ?>

            <tr>
                <td colspan="3"><strong>Total da Venda</strong></td>
                <td colspan="2"><strong>R$ <?php echo number_format($total_geral, 2, ",", "."); ?></strong></td>
            </tr>
        </table>

        <br>

        <form method="POST">
            <button type="submit" name="finalizar_venda">Finalizar venda</button>
        </form>
    <?php } else { ?>
        <p>Nenhum item adicionado.</p>
    <?php } ?>
</div>

<?php include("../includes/footer.php"); ?>
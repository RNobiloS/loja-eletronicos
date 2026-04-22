<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../conexao.php");
include("../includes/header.php");
include("../includes/menu.php");

$sql = "
SELECT
    v.id_venda,
    c.nome AS cliente,
    p.nome AS produto,
    iv.quantidade,
    iv.subtotal,
    v.data_venda
FROM vendas v
JOIN clientes c ON v.id_cliente = c.id_cliente
JOIN itens_venda iv ON v.id_venda = iv.id_venda
JOIN produtos p ON iv.id_produto = p.id_produto
ORDER BY v.data_venda DESC
";

$resultado = mysqli_query($conexao, $sql);

if (!$resultado) {
    die('Erro na consulta: ' . mysqli_error($conexao));
}
?>

<div class="container">
    <h2>Relatório de Vendas</h2>

    <?php $total_geral = 0; ?>

    <?php if (mysqli_num_rows($resultado) > 0) { ?>
        <table>
            <tr>
                <th>ID Venda</th>
                <th>Cliente</th>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Subtotal</th>
                <th>Data</th>
            </tr>

            <?php while ($venda = mysqli_fetch_assoc($resultado)) { ?>
                <?php $total_geral += $venda["subtotal"]; ?>
                <tr>
                    <td><?php echo $venda["id_venda"]; ?></td>
                    <td><?php echo $venda["cliente"]; ?></td>
                    <td><?php echo $venda["produto"]; ?></td>
                    <td><?php echo $venda["quantidade"]; ?></td>
                    <td>R$ <?php echo number_format($venda["subtotal"], 2, ",", "."); ?></td>
                    <td><?php echo $venda["data_venda"]; ?></td>
                </tr>
            <?php } ?>
        </table>

        <div class="total">
            Total Geral: R$ <?php echo number_format($total_geral, 2, ",", "."); ?>
        </div>
    <?php } else { ?>
        <p>Nenhuma venda registrada.</p>
    <?php } ?>
</div>

<?php include("../includes/footer.php"); ?>
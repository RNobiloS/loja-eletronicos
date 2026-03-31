<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../conexao.php");

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

<h2>Relatório de Vendas</h2>

<?php
if (mysqli_num_rows($resultado) > 0) {

    while ($venda = mysqli_fetch_assoc($resultado)) {
        echo "Venda ID: " . $venda["id_venda"] . "<br>";
        echo "Cliente: " . $venda["cliente"] . "<br>";
        echo "Produto: " . $venda["produto"] . "<br>";
        echo "Quantidade: " . $venda["quantidade"] . "<br>";
        echo "Subtotal: R$ " . number_format($venda["subtotal"], 2, ",", ".") . "<br>";
        echo "Data: " . $venda["data_venda"] . "<br>";
        echo "<hr>";
    }

} else {
    echo "Nenhuma venda registrada.";
}
?>
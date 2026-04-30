<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../conexao.php");
include("../includes/header.php");
include("../includes/menu.php");

$sql = "SELECT * FROM produtos";
$resultado = mysqli_query($conexao, $sql);

if (!$resultado) {
    die("Erro na consulta: " . mysqli_error($conexao));
}
?>

<div class="container">
    <h2>Lista de Produtos</h2>

    <a class="link-botao" href="cadastrar_produto.php">+ Novo Produto</a>

    <?php if (mysqli_num_rows($resultado) > 0) { ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Ações</th>
            </tr>

            <?php while ($produto = mysqli_fetch_assoc($resultado)) { ?>
                <tr>
                    <td><?php echo $produto["id_produto"]; ?></td>
                    <td><?php echo $produto["nome"]; ?></td>
                    <td>R$ <?php echo number_format($produto["preco"], 2, ",", "."); ?></td>
                    <td><?php echo $produto["estoque"]; ?></td>
                    <td>
    <a class="link-botao" href="editar_produto.php?id=<?php echo $produto['id_produto']; ?>">Editar</a>
    <a class="link-botao" href="excluir_produto.php?id=<?php echo $produto['id_produto']; ?>" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
</td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>Nenhum produto cadastrado.</p>
    <?php } ?>
</div>

<?php include("../includes/footer.php"); ?>
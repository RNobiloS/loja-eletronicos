<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../conexao.php");
include("../includes/header.php");
include("../includes/menu.php");

$id = $_GET["id"];

$sql = "SELECT * FROM produtos WHERE id_produto = $id";
$resultado = mysqli_query($conexao, $sql);
$produto = mysqli_fetch_assoc($resultado);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $descricao = $_POST["descricao"];
    $preco = $_POST["preco"];
    $estoque = $_POST["estoque"];

    $update = "UPDATE produtos 
               SET nome = '$nome', descricao = '$descricao', preco = '$preco', estoque = '$estoque'
               WHERE id_produto = $id";

    if (mysqli_query($conexao, $update)) {
        header("Location: listar_produtos.php");
        exit;
    } else {
        echo "Erro ao atualizar produto: " . mysqli_error($conexao);
    }
}
?>

<div class="container">
    <h2>Editar Produto</h2>

    <form method="POST">
        <label>Nome do Produto</label>
        <input type="text" name="nome" value="<?php echo $produto['nome']; ?>" required>

        <label>Descrição</label>
        <textarea name="descricao" required><?php echo $produto['descricao']; ?></textarea>

        <label>Preço</label>
        <input type="number" step="0.01" name="preco" value="<?php echo $produto['preco']; ?>" required>

        <label>Estoque</label>
        <input type="number" name="estoque" value="<?php echo $produto['estoque']; ?>" required>

        <button type="submit">Salvar Alterações</button>
    </form>
</div>

<?php include("../includes/footer.php"); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../conexao.php");
include("../includes/header.php");
include("../includes/menu.php");

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $descricao = $_POST["descricao"];
    $preco = $_POST["preco"];
    $estoque = $_POST["estoque"];

    $sql = "INSERT INTO produtos (nome, descricao, preco, estoque)
            VALUES ('$nome', '$descricao', '$preco', '$estoque')";

    if (mysqli_query($conexao, $sql)) {
        $mensagem = "Produto cadastrado com sucesso!";
    } else {
        $mensagem = "Erro ao cadastrar produto: " . mysqli_error($conexao);
    }
}
?>

<div class="container">
    <h2>Cadastrar Produto</h2>

    <?php if ($mensagem != "") { ?>
        <div class="mensagem"><?php echo $mensagem; ?></div>
    <?php } ?>

    <form method="POST">
        <label>Nome do produto</label>
        <input type="text" name="nome" required>

        <label>Descrição</label>
        <textarea name="descricao" required></textarea>

        <label>Preço</label>
        <input type="number" step="0.01" name="preco" required>

        <label>Estoque</label>
        <input type="number" name="estoque" required>

        <button type="submit">Cadastrar</button>
    </form>
</div>

<?php include("../includes/footer.php"); ?>
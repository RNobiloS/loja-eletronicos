<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../conexao.php");
include("../includes/header.php");
include("../includes/menu.php");

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];

    $sql = "INSERT INTO clientes (nome, email, telefone)
            VALUES ('$nome', '$email', '$telefone')";

    if (mysqli_query($conexao, $sql)) {
        $mensagem = "Cliente cadastrado com sucesso!";
    } else {
        $mensagem = "Erro ao cadastrar cliente: " . mysqli_error($conexao);
    }
}
?>

<div class="container">
    <h2>Cadastrar Cliente</h2>

    <?php if ($mensagem != "") { ?>
        <div class="mensagem"><?php echo $mensagem; ?></div>
    <?php } ?>

    <form method="POST">
        <label>Nome</label>
        <input type="text" name="nome" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Telefone</label>
        <input type="text" name="telefone" required>

        <button type="submit">Cadastrar</button>
    </form>
</div>

<?php include("../includes/footer.php"); ?>
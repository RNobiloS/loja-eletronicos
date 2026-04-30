<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../conexao.php");
include("../includes/header.php");
include("../includes/menu.php");

$id = $_GET["id"];

$sql = "SELECT * FROM clientes WHERE id_cliente = $id";
$resultado = mysqli_query($conexao, $sql);
$cliente = mysqli_fetch_assoc($resultado);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];

    $update = "UPDATE clientes 
               SET nome = '$nome', email = '$email', telefone = '$telefone'
               WHERE id_cliente = $id";

    if (mysqli_query($conexao, $update)) {
        header("Location: listar_clientes.php");
        exit;
    } else {
        echo "Erro ao atualizar cliente: " . mysqli_error($conexao);
    }
}
?>

<div class="container">
    <h2>Editar Cliente</h2>

    <form method="POST">
        <label>Nome</label>
        <input type="text" name="nome" value="<?php echo $cliente['nome']; ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?php echo $cliente['email']; ?>" required>

        <label>Telefone</label>
        <input type="text" name="telefone" value="<?php echo $cliente['telefone']; ?>" required>

        <button type="submit">Salvar Alterações</button>
    </form>
</div>

<?php include("../includes/footer.php"); ?>
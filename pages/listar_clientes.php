<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../conexao.php");
include("../includes/header.php");
include("../includes/menu.php");

$sql = "SELECT * FROM clientes";
$resultado = mysqli_query($conexao, $sql);

if (!$resultado) {
    die("Erro na consulta: " . mysqli_error($conexao));
}
?>

<div class="container">
    <h2>Lista de Clientes</h2>

    <a class="link-botao" href="cadastrar_cliente.php">+ Novo Cliente</a>

    <?php if (mysqli_num_rows($resultado) > 0) { ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>

            <?php while ($cliente = mysqli_fetch_assoc($resultado)) { ?>
                <tr>
                    <td><?php echo $cliente["id_cliente"]; ?></td>
                    <td><?php echo $cliente["nome"]; ?></td>
                    <td><?php echo $cliente["email"]; ?></td>
                    <td><?php echo $cliente["telefone"]; ?></td>
                    <td><?php echo $cliente["id_cliente"]; ?></td>
                    <td><?php echo $cliente["nome"]; ?></td>
                    <td><?php echo $cliente["email"]; ?></td>
                    <td><?php echo $cliente["telefone"]; ?></td>
                    <td>
    <a class="link-botao" href="editar_cliente.php?id=<?php echo $cliente['id_cliente']; ?>">Editar</a>
    <a class="link-botao" href="excluir_cliente.php?id=<?php echo $cliente['id_cliente']; ?>" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">Excluir</a>
</td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>Nenhum cliente cadastrado.</p>
    <?php } ?>
</div>

<?php include("../includes/footer.php"); ?>
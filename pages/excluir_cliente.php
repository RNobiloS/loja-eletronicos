<?php
include("../conexao.php");

$id = $_GET["id"];

$sql = "DELETE FROM clientes WHERE id_cliente = $id";

mysqli_query($conexao, $sql);

header("Location: listar_clientes.php");
exit;
?>
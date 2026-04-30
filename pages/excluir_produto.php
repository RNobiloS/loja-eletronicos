<?php
include("../conexao.php");

$id = $_GET["id"];

$sql = "DELETE FROM produtos WHERE id_produto = $id";

mysqli_query($conexao, $sql);

header("Location: listar_produtos.php");
exit;
?>
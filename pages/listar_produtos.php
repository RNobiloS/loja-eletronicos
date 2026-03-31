<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../conexao.php");

$sql = "SELECT * FROM produtos";
$resultado = mysqli_query($conexao, $sql);

if (!$resultado) {
    die("Erro na consulta: " . mysqli_error($conexao));
}
?>

<h1>Lista de Produtos</h1>

<?php

if (mysqli_num_rows($resultado) > 0) {

    while ($produto = mysqli_fetch_assoc($resultado)) {

        echo "ID: " . $produto["id_produto"] . "<br>";
        echo "Nome: " . $produto["nome"] . "<br>";
        echo "Preço: " . $produto["preco"] . "<br>";
        echo "Estoque: " . $produto["estoque"] . "<br>";
        echo "<hr>";

    }

} else {

    echo "Nenhum produto cadastrado.";

}

?>
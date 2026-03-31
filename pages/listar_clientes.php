<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../conexao.php");

$sql = "SELECT * FROM clientes";
$resultado = mysqli_query($conexao, $sql);

if (!$resultado) {
    die("Erro na consulta: " . mysqli_error($conexao));
}
?>

<h1>Lista de Clientes</h1>

<?php

if (mysqli_num_rows($resultado) > 0) {

    while ($cliente = mysqli_fetch_assoc($resultado)) {

        echo "ID: " . $cliente["id_cliente"] . "<br>";
        echo "Nome: " . $cliente["nome"] . "<br>";
        echo "Email: " . $cliente["email"] . "<br>";
        echo "Telefone: " . $cliente["telefone"] . "<br>";
        echo "<hr>";

    }

} else {

    echo "Nenhum cliente cadastrado.";

}

?>
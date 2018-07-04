<?php
// including the database connection file
include_once("../config.php");

$id = mysqli_real_escape_string($mysqli, $_POST['id']);
$descricao = mysqli_real_escape_string($mysqli, $_POST['descricao']);

//updating the table
$result = mysqli_query($mysqli, "UPDATE treino SET descricao='$descricao' WHERE id=$id");
echo "id=".$id;
echo "desc=".$descricao;
echo "Treino alterado com sucesso.";
?>

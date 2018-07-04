<?php
    include_once("../../config.php");
    
    $idAluno = mysqli_real_escape_string($mysqli, $_POST['idAluno']);
    $nome = mysqli_real_escape_string($mysqli, $_POST['name']);
    
    //insert data to database	
    $result = mysqli_query($mysqli, "INSERT INTO avaliacao_tecnica(nome, idAluno) VALUES('$nome', $idAluno)");
    $id = $mysqli->insert_id;

    echo $id;
?>
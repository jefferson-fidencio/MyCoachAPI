<?php
    //including the database connection file
    include_once("../config.php");
    
    $idAluno = mysqli_real_escape_string($mysqli, $_POST['idAluno']);
    $nome = mysqli_real_escape_string($mysqli, $_POST['name']);
    
    //insert data to database	
    $result = mysqli_query($mysqli, "INSERT INTO frequencia_metragem(nome,idAluno) VALUES('$nome',$idAluno)");
    $idAluno = $mysqli->insert_id;

    echo $idAluno;
?>
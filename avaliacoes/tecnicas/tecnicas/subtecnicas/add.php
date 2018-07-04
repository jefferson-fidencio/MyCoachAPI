<?php
    include_once("../../../../config.php");
    
    $idTecnica = mysqli_real_escape_string($mysqli, $_POST['idTecnica']);
    $nome = mysqli_real_escape_string($mysqli, $_POST['nome']);
    $idExecucao = mysqli_real_escape_string($mysqli, $_POST['idExecucao']);
    
    //insert data to database	
    $result = mysqli_query($mysqli, "INSERT INTO sub_tecnica(nome, idExecucao, idTecnica) VALUES('$nome',1,$idTecnica)");
    $id = $mysqli->insert_id;

    echo $id;
?>
<?php
    include_once("../../../config.php");
    
    $idAvaliacao = mysqli_real_escape_string($mysqli, $_POST['idAvaliacao']);
    $nome = mysqli_real_escape_string($mysqli, $_POST['nome']);
    $observacao = mysqli_real_escape_string($mysqli, $_POST['observacao']);
    $videoUrl = mysqli_real_escape_string($mysqli, $_POST['videoUrl']);
    $conceito = mysqli_real_escape_string($mysqli, $_POST['conceito']);
    
    //insert data to database	
    $result = mysqli_query($mysqli, "INSERT INTO tecnica(nome, ordem, observacao, videoURI, idAvaliacaoTecnica, conceito) VALUES('$nome', 1,'$observacao','$videoUrl',$idAvaliacao, '$conceito')");
    $id = $mysqli->insert_id;

    echo $id;
?>
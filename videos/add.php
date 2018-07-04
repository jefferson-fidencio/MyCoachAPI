<?php
    include_once("../../config.php");
    
    $idAluno = mysqli_real_escape_string($mysqli, $_POST['idAluno']);
    $nome = mysqli_real_escape_string($mysqli, $_POST['name']);
    $url = mysqli_real_escape_string($mysqli, $_POST['url']);
    
    //insert data to database	
    $result = mysqli_query($mysqli, "INSERT INTO video(nome, video, idAluno) VALUES('$nome', '$url',$idAluno)");
    $id = $mysqli->insert_id;

    echo "Vídeo criado com sucesso";
?>
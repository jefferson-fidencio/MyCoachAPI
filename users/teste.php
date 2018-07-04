<?php
    //including the database connection file
    include_once("../config.php");
    
    $alunoImgTmp = mysqli_real_escape_string($mysqli, $_POST["img"]);

    //cria imagem do aluno, se foi escolhida
    if ($alunoImgTmp != "")
    {
        //$tmp = explode('.',$alunoImgTmp);
        //$extensao = end($tmp);            
        $extensao = "png";
        $novoNomeImgAluno = "../../UploadedImages/Aluno/AlunoIDtesteImg.".$extensao;
        file_put_contents($novoNomeImgAluno, base64_decode($alunoImgTmp));
        
    }

    //display success message
    echo "Aluno adicionado com sucesso.";
?>
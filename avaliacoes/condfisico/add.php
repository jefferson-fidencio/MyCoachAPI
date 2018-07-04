<?php
    include_once("../../config.php");
    
    $idAluno = mysqli_real_escape_string($mysqli, $_POST['idAluno']);
    $nome = mysqli_real_escape_string($mysqli, $_POST['name']);
    
    //insert data to database	
    $result = mysqli_query($mysqli, "INSERT INTO avaliacao_cond_fisico(nome, idAluno) VALUES('$nome', $idAluno)");
    $id = $mysqli->insert_id;

    //########################################  cria imagem, se foi escolhida ####################################

    $tmp = explode('.',basename($_FILES["fileToUpload"]["name"]));
    $extensao = end($tmp);            
    $novoNomeImgAlunoSAveDisk = "../../../UploadedImages/CondFisico/AlunoID".$idAluno."AvaliacaoCondFisicoID".$id.".".$extensao;
    $novoNomeImgAlunoSaveBD = "../UploadedImages/CondFisico/AlunoID".$idAluno."AvaliacaoCondFisicoID".$id.".".$extensao; //gambiarra pra nao mexer no carregamento do listview
    
    $target_file = $novoNomeImgAlunoSAveDisk;
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $result = mysqli_query($mysqli, "UPDATE avaliacao_cond_fisico SET condicionamentoFisicoImg='$novoNomeImgAlunoSaveBD' WHERE id=$id");
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
?>
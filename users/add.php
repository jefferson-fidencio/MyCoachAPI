<?php
    //including the database connection file
    include_once("../config.php");
    
    $idCoach = mysqli_real_escape_string($mysqli, $_POST['idCoach']);
    $nome = mysqli_real_escape_string($mysqli, $_POST['name']);
    $Email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $Senha = mysqli_real_escape_string($mysqli, $_POST['password']);
    $modalidade = mysqli_real_escape_string($mysqli, $_POST['modalidade']);
    
    //insert data to database	
    $result = mysqli_query($mysqli, "INSERT INTO user(nome,email,senha) VALUES('$nome','$Email','$Senha')");
    $idAluno = $mysqli->insert_id;

    //########################################  cria imagem do aluno, se foi escolhida ####################################

    $tmp = explode('.',basename($_FILES["fileToUpload"]["name"]));
    $extensao = end($tmp);            
    $novoNomeImgAlunoSAveDisk = "../../UploadedImages/Aluno/AlunoID".$idAluno."Img.".$extensao;
    $novoNomeImgAlunoSaveBD = "../UploadedImages/Aluno/AlunoID".$idAluno."Img.".$extensao; //gambiarra pra nao mexer no carregamento do listview
    
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
            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    //######################################################################################


    if ($novoNomeImgAlunoSaveBD != "")
    {
        // atualiza no bd os nomes dos arquivos
        $result = mysqli_query($mysqli, "UPDATE user SET img='$novoNomeImgAlunoSaveBD' WHERE id=$idAluno");
    }
    
    //cria treino semanal vazio do aluno
    mysqli_query($mysqli, "INSERT INTO treino_semanal(idAluno) VALUES($idAluno)");
    //cria treino vazio de cada dia da semana
    $ImgMetragemSrc = $mysqli->insert_id;
    mysqli_query($mysqli, "INSERT INTO treino(dia,descricao,idTreinoSemanal) VALUES('Segunda-Feira','',$ImgMetragemSrc)");
    mysqli_query($mysqli, "INSERT INTO treino(dia,descricao,idTreinoSemanal) VALUES('Terça-Feira','',$ImgMetragemSrc)");
    mysqli_query($mysqli, "INSERT INTO treino(dia,descricao,idTreinoSemanal) VALUES('Quarta-Feira','',$ImgMetragemSrc)");
    mysqli_query($mysqli, "INSERT INTO treino(dia,descricao,idTreinoSemanal) VALUES('Quinta-Feira','',$ImgMetragemSrc)");
    mysqli_query($mysqli, "INSERT INTO treino(dia,descricao,idTreinoSemanal) VALUES('Sexta-Feira','',$ImgMetragemSrc)");
    mysqli_query($mysqli, "INSERT INTO treino(dia,descricao,idTreinoSemanal) VALUES('Sábado','',$ImgMetragemSrc)");
    mysqli_query($mysqli, "INSERT INTO treino(dia,descricao,idTreinoSemanal) VALUES('Domingo','',$ImgMetragemSrc)");


    // relaciona aluno com coach
    $result = mysqli_query($mysqli, "INSERT INTO user_user_modalidade(idCoach,idAluno,idModalidade) VALUES('$idCoach','$idAluno','$modalidade')");
    $idAluno = $mysqli->insert_id;

    //display success message
    echo "Aluno adicionado com sucesso.";
?>
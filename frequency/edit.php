<?php
include_once("../config.php");

$id = mysqli_real_escape_string($mysqli, $_POST['id']);
$idAluno = mysqli_real_escape_string($mysqli, $_POST['idAluno']);
$tipo = mysqli_real_escape_string($mysqli, $_POST['tipo']);

if ($tipo == 0) //frequencia
{
    $tmp = explode('.',basename($_FILES["fileToUpload"]["name"]));
    $extensao = end($tmp);            
    $novoNomeImgAlunoSAveDisk = "../../UploadedImages/Frequencias/AlunoID".$idAluno."FrequenciaMetragemID".$id.".".$extensao;
    $novoNomeImgAlunoSaveBD = "../UploadedImages/Frequencias/AlunoID".$idAluno."FrequenciaMetragemID".$id.".".$extensao; //gambiarra pra nao mexer no carregamento do listview

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
            $result = mysqli_query($mysqli, "UPDATE frequencia_metragem SET frequenciaImg='$novoNomeImgAlunoSaveBD' WHERE id=$id");
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
else
{
    $tmp = explode('.',basename($_FILES["fileToUpload"]["name"]));
    $extensao = end($tmp);            
    $novoNomeImgAlunoSAveDisk = "../../UploadedImages/Metragens/AlunoID".$idAluno."FrequenciaMetragemID".$id.".".$extensao;
    $novoNomeImgAlunoSaveBD = "../UploadedImages/Metragens/AlunoID".$idAluno."FrequenciaMetragemID".$id.".".$extensao; //gambiarra pra nao mexer no carregamento do listview

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
            $result = mysqli_query($mysqli, "UPDATE frequencia_metragem SET metragemImg='$novoNomeImgAlunoSaveBD' WHERE id=$id");
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

?>

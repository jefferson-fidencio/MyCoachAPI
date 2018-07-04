<?php
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Acesso negado';
        exit;
    }  
        
    //validar usuario e senha na API
    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];
    
    if ($username == null || $password == null)
    {
         header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Acesso negado';
        exit;
    }
    
    include_once("config.php");
    
    $fetch_alunos = mysqli_query($mysqli, "SELECT id FROM aluno WHERE email = '" . $username . "' AND senha = '".$password."'");
        while($row_alunos = mysqli_fetch_assoc($fetch_alunos)){
        $idAluno = $row_alunos['id'];
    }
    
    if(isset($idAluno)){
    
        header('Content-type: application/json; charset=utf-8');
        header('WWW-Authenticate: Basic realm="Sistema de Testes"');
        header('HTTP/1.0 200 OK');

        mysqli_query($mysqli,'SET NAMES utf8;');
        
        // dados aluno
        $aluno = null;
        $fetch_alunos = mysqli_query($mysqli, "SELECT * FROM aluno WHERE id = " . $idAluno);
        $row_alunos = mysqli_fetch_assoc($fetch_alunos);
        $aluno['id'] = $row_alunos['id'];
        $aluno['nome'] = $row_alunos['nome'];
        $aluno['email'] = $row_alunos['email'];
        $aluno['senha'] = $row_alunos['senha'];
        $aluno['alunoImg'] = $row_alunos['alunoImg'];
        $aluno['treino_semanal'] = array();
        $aluno['frequencias_metragens'] = array();
        $aluno['avaliacoes_cond_fisico'] = array();
        $aluno['avaliacoes_tecnicas'] = array();
        $aluno['videos'] = array();
        $aluno['outros_videos'] = array();

        // dados treino semanal do aluno
        $treino_semanal = array();
        $fetch_treino_semanal = mysqli_query($mysqli, "SELECT * FROM treino_semanal WHERE idAluno = " . $idAluno);
        while($row_treino_semanal = mysqli_fetch_assoc($fetch_treino_semanal)){
            $treino_semanal['id'] = $row_treino_semanal['id'];
            $treino_semanal['treinos'] = array();

            // dados treinos do treino semanal do aluno
            $treinos = array();
            $fetch_treinos = mysqli_query($mysqli, "SELECT * FROM treino WHERE idTreinoSemanal = " . $row_treino_semanal['id'] . " ORDER BY id");
            while($row_treinos = mysqli_fetch_assoc($fetch_treinos)){
                $treinos['id'] = $row_treinos['id'];
                $treinos['dia'] = $row_treinos['dia'];
                $treinos['descricao'] = $row_treinos['descricao'];
                array_push($treino_semanal['treinos'],$treinos);
            }
            array_push($aluno['treino_semanal'],$treino_semanal);
        }

        // dados frequencias e metragens do aluno
        $frequencias_metragens = array();
        $fetch_frequencias_metragens = mysqli_query($mysqli, "SELECT * FROM frequencia_metragem WHERE idAluno = " . $idAluno);
        while($row_frequencias_metragens = mysqli_fetch_assoc($fetch_frequencias_metragens)){
                $frequencias_metragens['id'] = $row_frequencias_metragens['id'];
                $frequencias_metragens['nome'] = $row_frequencias_metragens['nome'];
                $frequencias_metragens['frequenciaImg'] = $row_frequencias_metragens['frequenciaImg'];
                $frequencias_metragens['metragemImg'] = $row_frequencias_metragens['metragemImg'];
                array_push($aluno['frequencias_metragens'],$frequencias_metragens);
        }

        // dados avaliacoes cond fisico do aluno
        $avaliacoes_cond_fisico = array();
        $fetch_avaliacoes_cond_fisico = mysqli_query($mysqli, "SELECT * FROM avaliacao_cond_fisico WHERE idAluno = " . $idAluno ." ORDER BY id DESC");
        while($row_avaliacoes_cond_fisico = mysqli_fetch_assoc($fetch_avaliacoes_cond_fisico)){
                $avaliacoes_cond_fisico['id'] = $row_avaliacoes_cond_fisico['id'];
                $avaliacoes_cond_fisico['nome'] = $row_avaliacoes_cond_fisico['nome'];
                $avaliacoes_cond_fisico['condicionamentoFisicoImg'] = $row_avaliacoes_cond_fisico['condicionamentoFisicoImg'];
                array_push($aluno['avaliacoes_cond_fisico'],$avaliacoes_cond_fisico);
        }

        // dados avaliacoes tecnicas do aluno
        $avaliacoes_tecnicas = array();
        $fetch_avaliacoes_tecnicas = mysqli_query($mysqli, "SELECT * FROM avaliacao_tecnica WHERE idAluno = " . $idAluno ." ORDER BY id DESC");
        while($row_avaliacoes_tecnica = mysqli_fetch_assoc($fetch_avaliacoes_tecnicas)){
                $avaliacoes_tecnicas['id'] = $row_avaliacoes_tecnica['id'];
                $avaliacoes_tecnicas['nome'] = $row_avaliacoes_tecnica['nome'];
                $avaliacoes_tecnicas['tecnicas'] = array();

                // dados tecnicas das avaliacoes tecnicas do aluno
                $tecnicas = array();
                $fetch_tecnicas = mysqli_query($mysqli, "SELECT * FROM tecnica WHERE idAvaliacaoTecnica = " . $row_avaliacoes_tecnica['id'] ." ORDER BY ordem");
                while($row_tecnica = mysqli_fetch_assoc($fetch_tecnicas)){
                        $tecnicas['id'] = $row_tecnica['id'];
                        $tecnicas['ordem'] = $row_tecnica['ordem'];
                        $tecnicas['nome'] = $row_tecnica['nome'];
                        $tecnicas['observacao'] = $row_tecnica['observacao'];
                        $tecnicas['conceito'] = $row_tecnica['conceito'];
                        $tecnicas['videoURI'] = $row_tecnica['videoURI'];
                        $tecnicas['subtecnicas'] = array();

                        // dados sub-tecnicas das tecnicas das avaliacoes tecnicas do aluno
                        $subtecnicas = array();
                        $fetch_subtecnicas = mysqli_query($mysqli, "SELECT * FROM sub_tecnica WHERE idTecnica = " . $row_tecnica['id']);
                        while($row_subtecnica = mysqli_fetch_assoc($fetch_subtecnicas)){
                                $subtecnicas['id'] = $row_subtecnica['id'];
                                $subtecnicas['nome'] = $row_subtecnica['nome'];

                                // dados execucao das sub-tecnicas das tecnicas das avaliacoes tecnicas do aluno
                                $execucao = array();
                                $fetch_execucao = mysqli_query($mysqli, "SELECT * FROM execucao WHERE id = " . $row_subtecnica['idExecucao']);
                                while($row_execucao = mysqli_fetch_assoc($fetch_execucao)){
                                        $execucao['id'] = $row_execucao['id'];
                                        $execucao['nome'] = $row_execucao['nome'];
                                        $subtecnicas['execucao'] = $execucao;
                                }
                                array_push($tecnicas['subtecnicas'],$subtecnicas);
                        }
                        array_push($avaliacoes_tecnicas['tecnicas'],$tecnicas);
                }
                array_push($aluno['avaliacoes_tecnicas'],$avaliacoes_tecnicas);
        }
        
        // dados videos do aluno
        $videos = array();
        $fetch_videos = mysqli_query($mysqli, "SELECT * FROM video WHERE idAluno = " . $idAluno ." ORDER BY id DESC");
        while($row_video = mysqli_fetch_assoc($fetch_videos)){
                $videos['id'] = $row_video['id'];
                $videos['nome'] = $row_video['nome'];
                $videos['video'] = $row_video['video'];
                array_push($aluno['videos'],$videos);
        }
        
        // dados videos do aluno
        $outros_videos = array();
        $fetch_outros_videos = mysqli_query($mysqli, "SELECT * FROM outro_video WHERE idAluno = " . $idAluno ." ORDER BY id DESC");
        while($row_outro_video = mysqli_fetch_assoc($fetch_outros_videos)){
                $outros_videos['id'] = $row_outro_video['id'];
                $outros_videos['nome'] = $row_outro_video['nome'];
                $outros_videos['video'] = $row_outro_video['video'];
                array_push($aluno['outros_videos'],$outros_videos);
        }
        
        //imprime resultado
        echo json_encode($aluno, JSON_PRETTY_PRINT);
    }
    else
    {
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Acesso negado';
        exit;
    }
?>

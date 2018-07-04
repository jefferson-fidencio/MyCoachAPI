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
    
    $fetch_alunos = mysqli_query($mysqli, "SELECT id FROM user WHERE email = '" . $username . "' AND senha = '".$password."'");
        while($row_alunos = mysqli_fetch_assoc($fetch_alunos)){
        $idUser = $row_alunos['id'];
    }
    
    if(isset($idUser)){
    
        header('Content-type: application/json; charset=utf-8');
        header('WWW-Authenticate: Basic realm="Sistema de Testes"');
        header('HTTP/1.0 200 OK');

        mysqli_query($mysqli,'SET NAMES utf8;');
        
        // dados user
        $user = null;
        $fetch_alunos = mysqli_query($mysqli, "SELECT * FROM user WHERE id = " . $idUser);
        $row_alunos = mysqli_fetch_assoc($fetch_alunos);
        $user['id'] = $row_alunos['id'];
        $user['nome'] = $row_alunos['nome'];
        $user['email'] = $row_alunos['email'];
        $user['senha'] = $row_alunos['senha'];
        $user['img'] = $row_alunos['img'];
        $user['categoria'] = $row_alunos['categoria'];

        if ($row_alunos['categoria'] == 1)
        {
                $user['treino_semanal'] = array();
                $user['frequencias_metragens'] = array();
                $user['avaliacoes_cond_fisico'] = array();
                $user['avaliacoes_tecnicas'] = array();
                $user['videos'] = array();
                $user['outros_videos'] = array();

                // dados treino semanal do aluno
                $treino_semanal = array();
                $fetch_treino_semanal = mysqli_query($mysqli, "SELECT * FROM treino_semanal WHERE idAluno = " . $idUser);
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
                        array_push($user['treino_semanal'],$treino_semanal);
                }

                // dados frequencias e metragens do aluno
                $frequencias_metragens = array();
                $fetch_frequencias_metragens = mysqli_query($mysqli, "SELECT * FROM frequencia_metragem WHERE idAluno = " . $idUser);
                while($row_frequencias_metragens = mysqli_fetch_assoc($fetch_frequencias_metragens)){
                        $frequencias_metragens['id'] = $row_frequencias_metragens['id'];
                        $frequencias_metragens['nome'] = $row_frequencias_metragens['nome'];
                        $frequencias_metragens['frequenciaImg'] = $row_frequencias_metragens['frequenciaImg'];
                        $frequencias_metragens['metragemImg'] = $row_frequencias_metragens['metragemImg'];
                        array_push($user['frequencias_metragens'],$frequencias_metragens);
                }

                // dados avaliacoes cond fisico do aluno
                $avaliacoes_cond_fisico = array();
                $fetch_avaliacoes_cond_fisico = mysqli_query($mysqli, "SELECT * FROM avaliacao_cond_fisico WHERE idAluno = " . $idUser ." ORDER BY id DESC");
                while($row_avaliacoes_cond_fisico = mysqli_fetch_assoc($fetch_avaliacoes_cond_fisico)){
                        $avaliacoes_cond_fisico['id'] = $row_avaliacoes_cond_fisico['id'];
                        $avaliacoes_cond_fisico['nome'] = $row_avaliacoes_cond_fisico['nome'];
                        $avaliacoes_cond_fisico['condicionamentoFisicoImg'] = $row_avaliacoes_cond_fisico['condicionamentoFisicoImg'];
                        array_push($user['avaliacoes_cond_fisico'],$avaliacoes_cond_fisico);
                }

                // dados avaliacoes tecnicas do aluno
                $avaliacoes_tecnicas = array();
                $fetch_avaliacoes_tecnicas = mysqli_query($mysqli, "SELECT * FROM avaliacao_tecnica WHERE idAluno = " . $idUser ." ORDER BY id DESC");
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
                        array_push($user['avaliacoes_tecnicas'],$avaliacoes_tecnicas);
                }
                
                // dados videos do aluno
                $videos = array();
                $fetch_videos = mysqli_query($mysqli, "SELECT * FROM video WHERE idAluno = " . $idUser ." ORDER BY id DESC");
                while($row_video = mysqli_fetch_assoc($fetch_videos)){
                        $videos['id'] = $row_video['id'];
                        $videos['nome'] = $row_video['nome'];
                        $videos['video'] = $row_video['video'];
                        array_push($user['videos'],$videos);
                }
                
                // dados videos do aluno
                $outros_videos = array();
                $fetch_outros_videos = mysqli_query($mysqli, "SELECT * FROM outro_video WHERE idAluno = " . $idUser ." ORDER BY id DESC");
                while($row_outro_video = mysqli_fetch_assoc($fetch_outros_videos)){
                        $outros_videos['id'] = $row_outro_video['id'];
                        $outros_videos['nome'] = $row_outro_video['nome'];
                        $outros_videos['video'] = $row_outro_video['video'];
                        array_push($user['outros_videos'],$outros_videos);
                }
        }
        else //coach
        {
                $user['alunos'] = array();
                $fetch_alunos = mysqli_query($mysqli, "SELECT * FROM user_user_modalidade WHERE idCoach = " . $idUser);
                while($row_aluno = mysqli_fetch_assoc($fetch_alunos))
                {

                        // dados aluno
                        $aluno = null;
                        $idAluno = $row_aluno['idAluno'];
                        $fetch_aluno = mysqli_query($mysqli, "SELECT * FROM user WHERE id = " . $idAluno);
                        $row_alunos = mysqli_fetch_assoc($fetch_aluno);
                        $aluno['id'] = $row_alunos['id'];
                        $aluno['nome'] = $row_alunos['nome'];
                        $aluno['email'] = $row_alunos['email'];
                        $aluno['senha'] = $row_alunos['senha'];
                        $aluno['img'] = $row_alunos['img'];
                        $aluno['idModalidade'] = $row_aluno['idModalidade'];
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
                        
                        array_push($user['alunos'],$aluno);
                }
        }
       
        //imprime resultado
        echo json_encode($user, JSON_PRETTY_PRINT);
    }
    else
    {
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Acesso negado';
        exit;
    }
?>

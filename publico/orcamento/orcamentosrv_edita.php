<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /publico/orcamento/orcamentosrv_edita.php
 ***** Conteúdo: 07-orcamento
 ***** Data criação: set/2026
 ***** Disponível em: reciboexpress.com.br
 *****
 **************************************************
 *****
 ***** DESENVOLVEDOR
 ***** Turma A2026S2N2
 ***** Projeto Integrador II
 ***** Univesp
 ***** Universidade Virtual do Estado de São Paulo
 ***** Matéria obrigatória do curso de
 ***** Análise de Sistemas e 
 ***** Engenharia da Computação
 ***** São Paulo - SP - 2026
 ***** 
 **************************************************
 **************************************************
 */
//header('Content-Type: application/json; charset=utf-8');
session_start();
$Raiz = "../../";
include($Raiz . "include/php/header.php");
include($Raiz . "include/php/funcoes.php");
include($Raiz . "conexao/db.php");
VerificaSessao();
// Variáveis de controle
$ok = true;
$html = "";
$chave_perfil = $_SESSION["CHAVE_PERFIL"];
$chave_orcamento = "1";
$chave_servico = "1";
$acao = "NOVO";
// EOF Variáveis de controle
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  try {    
    if (isset($_POST["acao"])) {
      $acao = $_POST["acao"];
    }    
    if ($acao == "NOVO") {
      $chave_orcamento = $_POST["chave_orcamento"];
      $chave_servico = $_POST["chave_servico"];
      if ($chave_orcamento != "" && $chave_servico != "") {
        abre_db();
        $qtd_orcamentosrv = 1;
        $valor_servico = 0;
        $caixa_orcamentosrv = 1;
        $strsql = "
        select
        tservico.valor_servico 
        from
        tservico 
        where 
        tservico.chave_servico = :vchave_servico and
        tservico.chave_perfil = :vchave_perfil and
        tservico.caixa_servico = 1
        ";
        $qservico = $pdo->prepare($strsql);
        $qservico->bindParam(":vchave_servico", $chave_servico);
        $qservico->bindParam(":vchave_perfil", $chave_perfil);
        $qservico->execute();
        if ($tservico = $qservico->fetch(PDO::FETCH_ASSOC)) {
          $valor_servico = $tservico["valor_servico"];
        }
        $strsql = "
        insert 
        into 
        torcamentosrv
        (chave_orcamento
        ,chave_servico
        ,qtd_orcamentosrv
        ,vlu_orcamentosrv
        ,vlt_orcamentosrv
        ,caixa_orcamentosrv
        ) 
        values 
        (:vchave_orcamento
        ,:vchave_servico
        ,:vqtd_orcamentosrv
        ,:vvlu_orcamentosrv
        ,:vvlt_orcamentosrv
        ,:vcaixa_orcamentosrv
        )";
        $qorcamentosrv = $pdo->prepare($strsql);
        $qorcamentosrv->bindParam(":vchave_orcamento", $chave_orcamento);
        $qorcamentosrv->bindParam(":vchave_servico", $chave_servico);
        $qorcamentosrv->bindParam(":vqtd_orcamentosrv", $qtd_orcamentosrv);
        $qorcamentosrv->bindParam(":vvlu_orcamentosrv", $valor_servico);
        $qorcamentosrv->bindParam(":vvlt_orcamentosrv", $valor_servico);
        $qorcamentosrv->bindParam(":vcaixa_orcamentosrv", $caixa_orcamentosrv);
        $qorcamentosrv->execute();
        $chave_orcamentosrv = $pdo->lastInsertId();
        fecha_diario("torcamentosrv", "chave_orcamentosrv", $chave_orcamentosrv, $abre_diario = array(), $campoexcluidos = array(""));
        header("Location: /publico/orcamento/orcamento_edita.php", false);
        die();
      }
    }
    if ($acao == "EDITA") {     
      $chave_orcamento = $_SESSION["CHAVE_ORCAMENTO"];
      $chave_orcamentosrv = $_POST["chave_orcamentosrv"];
      $chave_servico = $_POST["chave_servico"];
      $qtd_orcamentosrv = $_POST["qtd_orcamentosrv"];
      if ($chave_orcamento != "" && $chave_orcamentosrv != "") {
        abre_db();        
        $strsql = "
        select
        tservico.valor_servico 
        from
        tservico 
        where 
        tservico.chave_servico = :vchave_servico and
        tservico.chave_perfil = :vchave_perfil and
        tservico.caixa_servico = 1
        ";
        $qservico = $pdo->prepare($strsql);
        $qservico->bindParam(":vchave_servico", $chave_servico);
        $qservico->bindParam(":vchave_perfil", $chave_perfil);
        $qservico->execute();
        if ($tservico = $qservico->fetch(PDO::FETCH_ASSOC)) {
          $valor_servico = $tservico["valor_servico"];
        }
        $abre_diario = abre_diario("torcamentosrv", "chave_orcamentosrv", $chave_orcamentosrv, $campoexcluidos = array(""));
        //inner join torcamento on torcamentosrv.chave_orcamento = torcamento.chave_orcamento 
        //torcamento.chave_perfil = :vchave_perfil and
        $strsql = "
        update 
        torcamentosrv        
        set 
        qtd_orcamentosrv = :vqtd_orcamentosrv
        ,vlu_orcamentosrv = :vvlu_orcamentosrv
        ,vlt_orcamentosrv = :vvlt_orcamentosrv
        where 
        torcamentosrv.chave_orcamentosrv = :vchave_orcamentosrv and        
        torcamentosrv.caixa_orcamentosrv = 1
        ";
        $qorcamentosrv = $pdo->prepare($strsql);
        $qorcamentosrv->bindParam(":vchave_orcamentosrv", $chave_orcamentosrv);
        //$qorcamentosrv->bindParam(":vchave_perfil", $chave_perfil);        
        $qorcamentosrv->bindParam(":vqtd_orcamentosrv", $qtd_orcamentosrv);
        $qorcamentosrv->bindParam(":vvlu_orcamentosrv", $valor_servico);
        $qorcamentosrv->bindParam(":vvlt_orcamentosrv", $valor_servico);        
        $qorcamentosrv->execute();        
        fecha_diario("torcamentosrv", "chave_orcamentosrv", $chave_orcamentosrv, $abre_diario, $campoexcluidos = array(""));
        header("Location: /publico/orcamento/orcamento_edita.php", false);
        die();
      }
    }
        
    // 4. Retorna a resposta de sucesso em formato JSON
    echo json_encode([
      'status' => 'sucesso',
      'mensagem' => 'Dados recebidos com sucesso!',
      'dados' => [
        'chave_orcamento' => $chave_orcamento,
        'chave_servico' => $chave_servico,
        'qtd_orcamentosrv' => $qtd_orcamentosrv,
        'vlu_orcamentosrv' => $valor_servico,
        'vlt_orcamentosrv' => $valor_servico
      ]
    ]);
  }
  catch (Exception $e) {
    // Retorna erro em formato JSON
    // http_response_code($e CossCode() ?: 400);
    echo json_encode([
      'status' => 'erro',
      'mensagem' => $e->getMessage()
    ]);
  }
}
?>
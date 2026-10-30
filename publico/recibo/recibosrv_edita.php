<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /publico/recibo/recibosrv_edita.php
 ***** Conteúdo: 07-recibo
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
$chave_recibo = "1";
$chave_servico = "1";
$acao = "NOVO";
// EOF Variáveis de controle
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  try {    
    if (isset($_POST["acao"])) {
      $acao = $_POST["acao"];
    }    
    if ($acao == "NOVO") {
      $chave_recibo = $_POST["chave_recibo"];
      $chave_servico = $_POST["chave_servico"];
      if ($chave_recibo != "" && $chave_servico != "") {
        abre_db();
        $valor_servico = 0;
        $qtd_recibosrv = 1;
        $valor_servico = 0;
        $caixa_recibosrv = 1;
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
          $valor_servico = floatval($tservico["valor_servico"]);
        }
        $vvlu_recibosrv = $valor_servico;
        $vvlt_recibosrv = $qtd_recibosrv * $valor_servico;
        $strsql = "
        insert 
        into 
        trecibosrv
        (chave_recibo
        ,chave_servico
        ,qtd_recibosrv
        ,vlu_recibosrv
        ,vlt_recibosrv
        ,caixa_recibosrv
        ) 
        values 
        (:vchave_recibo
        ,:vchave_servico
        ,:vqtd_recibosrv
        ,:vvlu_recibosrv
        ,:vvlt_recibosrv
        ,:vcaixa_recibosrv
        )";
        $qrecibosrv = $pdo->prepare($strsql);
        $qrecibosrv->bindParam(":vchave_recibo", $chave_recibo);
        $qrecibosrv->bindParam(":vchave_servico", $chave_servico);
        $qrecibosrv->bindParam(":vqtd_recibosrv", $qtd_recibosrv);
        $qrecibosrv->bindParam(":vvlu_recibosrv", $vvlu_recibosrv);
        $qrecibosrv->bindParam(":vvlt_recibosrv", $vvlt_recibosrv);
        $qrecibosrv->bindParam(":vcaixa_recibosrv", $caixa_recibosrv);
        $qrecibosrv->execute();
        $chave_recibosrv = $pdo->lastInsertId();
        fecha_diario("trecibosrv", "chave_recibosrv", $chave_recibosrv, $abre_diario = array(), $campoexcluidos = array(""));
        header("Location: /publico/recibo/recibo_edita.php", false);
        die();
      }
    }
    if ($acao == "EDITA") {     
      $chave_recibo = $_SESSION["CHAVE_RECIBO"];
      $chave_recibosrv = $_POST["chave_recibosrv"];
      $chave_servico = $_POST["chave_servico"];
      $qtd_recibosrv = $_POST["qtd_recibosrv"];
      if ($chave_recibo != "" && $chave_recibosrv != "") {
        $valor_servico = 0;
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
          $valor_servico = floatval($tservico["valor_servico"]);
        }
        $abre_diario = abre_diario("trecibosrv", "chave_recibosrv", $chave_recibosrv, $campoexcluidos = array(""));

        $vlu_servico = $valor_servico;
        $vlt_servico = $valor_servico * $qtd_recibosrv;
        $strsql = "
        update 
        trecibosrv        
        set 
        qtd_recibosrv = :vqtd_recibosrv
        ,vlu_recibosrv = :vvlu_recibosrv
        ,vlt_recibosrv = :vvlt_recibosrv
        where 
        trecibosrv.chave_recibosrv = :vchave_recibosrv and        
        trecibosrv.caixa_recibosrv = 1
        ";
        $qrecibosrv = $pdo->prepare($strsql);
        $qrecibosrv->bindParam(":vchave_recibosrv", $chave_recibosrv);
        $qrecibosrv->bindParam(":vqtd_recibosrv", $qtd_recibosrv);
        $qrecibosrv->bindParam(":vvlu_recibosrv", $vlu_servico);
        $qrecibosrv->bindParam(":vvlt_recibosrv", $vlt_servico);        
        $qrecibosrv->execute();        
        fecha_diario("trecibosrv", "chave_recibosrv", $chave_recibosrv, $abre_diario, $campoexcluidos = array(""));
        header("Location: /publico/recibo/recibo_edita.php", false);
        die();
      }
    }
        
    // 4. Retorna a resposta de sucesso em formato JSON
    echo json_encode([
      'status' => 'sucesso',
      'mensagem' => 'Dados recebidos com sucesso!',
      'dados' => [
        'chave_recibo' => $chave_recibo,
        'chave_servico' => $chave_servico,
        'qtd_recibosrv' => $qtd_recibosrv,
        'vlu_recibosrv' => $valor_servico,
        'vlt_recibosrv' => $valor_servico
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
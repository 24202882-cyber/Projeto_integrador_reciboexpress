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
header('Content-Type: application/json; charset=utf-8');
session_start();
$Raiz = "../../";
include($Raiz . "include/php/header.php"); // Configurações HEADER
include($Raiz . "include/php/funcoes.php"); // Funcoes de Usuário PHP
include($Raiz . "conexao/db.php");
VerificaSessao();
// Variáveis de controle
$ok = true;
$html = "";
$chave_perfil = $_SESSION["CHAVE_PERFIL"];
$chave_orcamento = "";
$chave_servico = "";
$acao = "";
// EOF Variáveis de controle
if ($_SERVER['REQUEST_METHOD'] == "POST") {  
  try {  
    $chave_orcamento = $_POST["chave_orcamento"];
    $chave_servico = $_POST["chave_servico"];  
    
    //if (isset($_POST["acao"])) {
    //  $acao = $_POST["acao"];
    //}
    $acao = "NOVO";
    if ($acao == "NOVO") {
      if ($chave_orcamento != "" && $chave_servico != "") {
        abre_db();
        $qtd_orcamentosrv = "1";
        $caixa_orcamentosrv = "1";
        $strsql = "
        insert 
        into 
        torcamentosrv
        (chave_orcamento
        ,chave_servico
        ,qtd_orcamentosrv
        ,caixa_orcamentosrv
        ) 
        values 
        (:vchave_orcamento
        ,:vchave_servico
        ,:vqtd_orcamentosrv
        ,:vcaixa_orcamentosrv
        )";
        $qorcamentosrv = $pdo->prepare($strsql);
        $qorcamentosrv->bindParam(":vchave_orcamento", $chave_orcamento);
        $qorcamentosrv->bindParam(":vchave_servico", $chave_servico);
        $qorcamentosrv->bindParam(":vqtd_orcamentosrv", $qtd_orcamentosrv);
        $qorcamentosrv->bindParam(":vcaixa_orcamentosrv", $caixa_orcamentosrv);
        $qorcamentosrv->execute();
        $chave_orcamentosrv = $pdo->lastInsertId();
        fecha_diario("torcamentosrv", "chave_orcamentosrv", $chave_orcamentosrv, $abre_diario = array(), $campoexcluidos = array(""));
      }
    }
    // 4. Retorna a resposta de sucesso em formato JSON
    echo json_encode([
      'status'  => 'sucesso',
      'mensagem' => 'Dados recebidos com sucesso!',
      'dados'   => [
          'nome'    => $nome,
          'email'   => $email,
          'arquivo' => $arquivoInfo
      ]
    ]);
  }
  catch (Exception $e) {
  // Retorna erro em formato JSON
  // http_response_code($e CossCode() ?: 400);
    echo json_encode([
        'status'   => 'erro',
        'mensagem' => $e->getMessage()
    ]);    
  }
}
?>

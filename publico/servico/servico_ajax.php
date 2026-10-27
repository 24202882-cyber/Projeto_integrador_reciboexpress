<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /publico/servico/servico_ajax.php
 ***** Conteúdo: Retorno pesquisa de Servido
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
$Home = $Raiz . "publico/servico/index.php";
$ok = true;
VerificaSessao();
//VerificaAdmin($Raiz);
// FONTE PARA ENTENDIMENTO FUNCIONAMENTO DE POST POR JSON
//https://sqkhor.medium.com/php-reading-json-from-javascript-fetch-api-and-axios-7c29804f0796

//********************
//********************
//**** DECODIFICA JSON PARA $_POST
//********************
//********************
if ($php_input = json_decode(trim(file_get_contents("php://input")), true)) {
  $_POST = array_merge_recursive($_POST, $php_input);
}
//********************
//********************
//**** $_POST NORMAL
//********************
//********************
$retorno = "falha";
$chave_perfil = $_SESSION["CHAVE_PERFIL"];
$chave_servico = "1";
$descr_servico = "";
$cobranca_servico = "";
$valor_servico = "";
$resumo = "";
if (isset($_POST["chave_servico"])) {
  $chave_servico = $_POST["chave_servico"];
  if ($chave_servico == "0") {
    $chave_servico = "";
  }
  if ($chave_servico == "NOVO") {
    $chave_servico = "";
  }  
}
if ($chave_servico != "") {
  abre_db();
  $strsql = "
  select
  tservico.* 
  from
  tservico
  where 
  tservico.chave_perfil = :vchave_perfil and 
  tservico.chave_servico = :vchave_servico and 
  tservico.caixa_servico = 1
  order by tservico.chave_servico
  ";
  $qservico = $pdo->prepare($strsql);
  $qservico->bindParam(":vchave_perfil", $chave_perfil);
  $qservico->bindParam(":vchave_servico", $chave_servico);
  $qservico->execute();
  if ($tservico = $qservico->fetch(PDO::FETCH_ASSOC)) {
    $chave_servico = $tservico["chave_servico"];
    $descr_servico = $tservico["descr_servico"];
    $cobranca_servico = $tservico["cobranca_servico"];
    $valor_servico = $tservico["valor_servico"];
    $resumo = "Serviço já cadastrado.";
    $retorno = "ok";    
  }
  else {
    $resumo = "";
    $retorno = "falha";    
  }
  $var = array(
    "retorno" => $retorno
    ,"chave_servico" => "{$chave_servico}"
    ,"descr_servico" => "{$descr_servico}"
    ,"cobranca_servico" => "{$cobranca_servico}"
    ,"valor_servico" => "{$valor_servico}"    
    ,"msg" => "{$resumo}"
  );
}
else {
  $var = array(
    "retorno" => "falha"    
    ,"chave_servico" => "{$chave_servico}"
    ,"msg" => "Chave do serviço não informados para pesquisa."
  );
}
$var_json = json_encode($var);
echo $var_json;
?>
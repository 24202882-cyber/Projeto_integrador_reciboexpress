<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /page/cadastro/cadastro_ajax.php
 ***** Conteúdo: Retorno pesquisa de Cadastro do Perfil Profissional
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
$Home = $Raiz . "publico/cadastro/index.php";
$ok = true;
//VerificaSessao();
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
$chave_perfil = "";
$cpf_perfil = "";
$cpf_perfil2 = "";
$email_perfil = "";
$resumo = "";
if (isset($_POST["chave_perfil"])) {
  $chave_perfil = $_POST["chave_perfil"];
  if ($chave_perfil == "0") {
    $chave_perfil = "";
  }
  if ($chave_perfil == "NOVO") {
    $chave_perfil = "";
  }  
}
if (isset($_POST["cpf_perfil"])) {
  $cpf_perfil = $_POST["cpf_perfil"];
  $cpf_perfil2 = so_numeros($cpf_perfil, "");
}
if (isset($_POST["email_perfil"])) {
  $email_perfil = $_POST["email_perfil"];
}
if ($cpf_perfil != "" || $chave_perfil != "" || $email_perfil != "") {  
  abre_db();
  $strsql = "
  select
  tperfil.chave_perfil
  ,tperfil.nome_perfil
  from
  tperfil
  where " .
  (($chave_perfil != "") ? " tperfil.chave_perfil = :vchave_perfil and " : "") .
  (($cpf_perfil != "") ? " (tperfil.cpf_perfil = :vcpf_perfil or tperfil.cpf_perfil = :vcpf_perfil2) and " : "") . 
  (($email_perfil != "") ? " tperfil.email_perfil = :vemail_perfil and " : "") .
  "tperfil.caixa_perfil = 1
  order by chave_perfil
  ";
  $qperfil = $pdo->prepare($strsql);
  if ($chave_perfil != "") {
    $qperfil->bindParam(":vchave_perfil", $chave_perfil);
  }
  if ($cpf_perfil != "") {
    $qperfil->bindParam(":vcpf_perfil", $cpf_perfil);
    $qperfil->bindParam(":vcpf_perfil2", $cpf_perfil2);
  }
  if ($email_perfil != "") {
    $qperfil->bindParam(":vemail_perfil", $email_perfil);
  }
  $qperfil->execute();
  if ($tperfil = $qperfil->fetch(PDO::FETCH_ASSOC)) {
    $chave_perfil = $tperfil["chave_perfil"];    
    $resumo = "Registro já cadastrado com este CPF.";
    $retorno = "falha";
  }
  else {
    $resumo = "";
    $retorno = "ok";
  }
  $var = array(
    "retorno" => $retorno
    ,"cpf" => "{$cpf_perfil}"
    ,"chave_perfil" => "{$chave_perfil}"
    ,"msg" => "{$resumo}"
  );
}
else {
  $var = array(
    "retorno" => "falha"
    ,"cpf" => "{$cpf_perfil}"
    ,"chave_perfil" => "{$chave_perfil}"
    ,"msg" => "CPF ou chave do Perfil Profissional não informados para pesquisa."
  );
}
$var_json = json_encode($var);
echo $var_json;
?>
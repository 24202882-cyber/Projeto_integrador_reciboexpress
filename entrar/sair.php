<?php
/*
**************************************************
**************************************************
***** 
***** APLICAÇÃO
***** Recibo Express
***** Arquivo: /acesso/sair.php
***** Conteúdo: Logout ou encerramento da sessão do usuário
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
session_start();
$Raiz = "../";
include($Raiz . "include/php/header.php"); // Configurações HEADER
include($Raiz . "include/php/funcoes.php"); // Funcoes de Usuário PHP
include($Raiz . "conexao/db.php");
$_SESSION["CHAVE_PERFIL"] = "";
$_SESSION["CHAVE_USUARIO"] = "";
$_SESSION["NOME_USUARIO"] = "";
$_SESSION["LOGIN_USUARIO"] = "";
$_SESSION["TIPO_USUARIO"] = "";
$_SESSION["CPF_PERFIL"] = "";
$_SESSION["CHAVE_USUARIOLOGIN"] = "";
$_SESSION["NOME_USUARIOLOGIN"] = "";
$_SESSION["LOGIN_USUARIOLOGIN"] = "";
$_SESSION["SESSAO_INICIO"] = "";
$_SESSION["SESSAO_EXPIRA"] = "";
fecha_db();
session_destroy();
header("Location: /index.php");
die();
?>
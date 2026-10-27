<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: index.php
 ***** Conteúdo: Home page
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
$Raiz = "";
$Titulo = "Entrar";
include($Raiz . "conexao/db.php");
include($Raiz . "include/php/header.php");
include($Raiz . "include/php/funcoes.php");
$VerificaSessaoLogin = "NAO";
if (isset($_SESSION['SESSAO_INICIO']) && isset($_SESSION['SESSAO_EXPIRA'])) {
  if ((time() - $_SESSION['SESSAO_INICIO']) > $_SESSION['SESSAO_EXPIRA']) {
    session_destroy();
    $VerificaSessaoLogin = "SIM";
  } else {
    $_SESSION['SESSAO_INICIO'] = time();
  }
} else {
  $VerificaSessaoLogin = "SIM";
}
if ($VerificaSessaoLogin == "NAO") {
  header("Location: /publico/geral");
  die();
}
VerificaSessao();

//********************
//********************
//**** Prepara ambiente para execuçao...incluir na pagina de login tambem.
//********************
//********************
if (!isset($_SESSION['AMBIENTE'])) {
  if (strtoupper($_SERVER['SERVER_NAME']) === "LOCALHOST") {
    $_SESSION['AMBIENTE'] = "DESENVOLVIMENTO";
  } else {
    $_SESSION['AMBIENTE'] = "PRODUCAO";
  }
}
//********************
//********************
//**** EOF Prepara ambiente para execuçao...incluir na pagina de login tambem.
//********************
//********************
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Recibo Express - Home</title>
  <?php include($Raiz . "include/html/header.html"); ?>
</head>
<body>
  <div class="container pt-2">
    <div class="row mt-3">
      <div class="col">
        <div class="rounded-2 d-flex justify-content-between align-items-center">
          <a href="/"><img src="/identidade/logo-reciboexpress-texto.svg" style="max-width:200px" alt="Recibo Express" class="d-inline-block align-text-top"></a>
          <div class="d-flex justify-content-end align-items-center">
            <span class="texto14-admin" style="color:#163B5C">Entrar</span>
          </div>
        </div>
        <hr>
      </div>
    </div>
    <div style="padding-bottom:80px;">
      <div class="row">
        <div class="col-6">
          <div class="col-sm-12 mb-5">
            <span class="small" style="color:#287A5B; font-weight: 700;">PARA QUEM TRABALHA POR CONTA PRÓPRIA</span>
          </div>
          <div class="col-sm-12 mb-5">
            <span class="manchete">
              Recibos profissionais, sem complicação</span>.
            </span>
          </div>
          <div class="col-sm-12 mb-4">
            <span class="texto-corpo">
              Organize seus serviços, emita recibos em PDF e compartilhe com o pagador em poucos passos.
            </span>
          </div>

          <div class="col-sm-12 mb-3">
            <button type="button" class="btn btn-custom" id="BTN_CADASTRO" name="BTN_CADASTRO" onclick="javascript:location='/publico/cadastro';">Criar conta</button>
            <button type="button" class="btn btn-light" id="BTN_LOGIN" name="BTN_LOGIN" onclick="javascript:location='/entrar';">Entrar</button>
          </div>

          <div class="col-sm-12 mt-3">
            <span class="small" style="color:#9AA6AE">Já tem cadastro? Escolha “Entrar”.</span>
          </div>

          <div class="col-sm-12 mt-4 text-center">

          </div>
        </div>
        <div class="col-6 d-flex justify-content-center">
          <div class="card rounded-3 border-1 pt-2 pb-2 ps-2 pe-2" style="background-color: #163B5C; border-color: #58151c; max-width: 70%;">
            <div class="card-body">
              <p style="color: #7FA3C4">VISÃO DO RECIBO</p>
              <p style="color: #7FA3C4">Recibo de serviços</p>
              <h3 style="color: #fff">R$ 320,00</h3>
              <p style="color: #7FA3C4">Diarista · aguardando confirmação</p>
              <hr>
              <div class="row">
                <div class="col-6">
                  <p style="color: #7FA3C4;">Limpeza residencial</p>
                </div>
                <div class="col-6 d-flex justify-content-end">
                  <p style="color: #7FD3AA; font-weight: 700;">R$ 320,00</p>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <p style="color: #7FA3C4;">Armazenamento</p>
                </div>
                <div class="col-6 d-flex justify-content-end">
                  <p style="color: #7FD3AA; font-weight: 700">Na núvem</p>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="card rounded-3 border-1" style="background-color: #294b69;">
                    <div class="card-body">
                      <span style="color: #7FA3C4">Link seguro pronto para compartilhar pelo WhatsApp.</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include($Raiz . "include/php/rodape.php"); ?>
</body>
</html>
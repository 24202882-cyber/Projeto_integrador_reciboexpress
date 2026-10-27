<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /publico/recibo/recibo-pronto.php
 ***** Conteúdo: 09-recibo-pronto
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

$Raiz = "../../";
include($Raiz . "include/php/header.php"); // Configurações HEADER
include($Raiz . "include/php/funcoes.php"); // Funcoes de Usuário PHP
include($Raiz . "conexao/db.php");
// Variáveis de controle
$ok = true;
$html = "";
$html_tit = "";
$html_msg = "";
$html_lnk = "";
$html_btn = "";
$email_usuario = "";
// EOF Variáveis de controle
$aftergopage = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Recibo Express - Recibo Pronto</title>
  <?php include($Raiz . "include/html/header.html"); ?>

  <style>
    /* Estilos base inspirados na imagem */
    :root {
      --sidebar-bg: #173b5d;
      /* Azul escuro da barra lateral */
      --body-bg: #f8f8f6;
      /* Fundo suave da área principal */
    }

    body {
      background-color: var(--body-bg);
      min-height: 100vh;
    }

    /* Sidebar Fixo no Desktop */
    .sidebar {
      background-color: var(--sidebar-bg);
      min-height: 100vh;
      color: #ffffff;
    }

    .sidebar .nav-link {
      color: rgba(255, 255, 255, 0.75);
      padding: 0.75rem 1rem;
      border-radius: 0.375rem;
      margin-bottom: 0.25rem;
      transition: all 0.2s ease;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      color: #ffffff;
      background-color: rgba(255, 255, 255, 0.15);
    }

    .sidebar .nav-link i {
      margin-right: 0.5rem;
      font-size: 1.1rem;
    }

    /* Ajuste do conteúdo principal */
    .main-content {
      padding: 10px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row">
      <?php include($Raiz . "include/php/menu_lateral.php"); ?>

      <!-- CONTEÚDO PRINCIPAL DA PÁGINA -->
      <main class="col-12 col-md-9 col-lg-10">
        <!-- Cabeçalho da Página -->
        <?php include($Raiz . "include/php/menu_superior.php"); ?>
        <div class="main-content">
          <div class="row">
            <!--<div class="col-12 d-flex align-items-center justify-content-center" style="min-height: 300px;">-->
              <div class="col=12">
              <div class="row mt-3 mb-3">
                <div class="col-12 text-center">
                  <img src="/identidade/logo_reciboexpress-retangular.svg" style="min-height: 48px; min-width: 48px;">
                </div>
              </div>
              <div class="row mt-3 mb-3">
                <div class="col-12 text-center">
                  <span class="texto11">RECIBO PRONTO · SALVO NA NUVEM</span>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col-12 text-center">
                  <span class="texto48">R$ 180,00</span>
                </div>
              </div>
              <div class="row">
                <div class="col-12 text-center">
                  <span class="texto17-regular">Mariana Lopes · Faxina residencial</span><br>
                  <span class="texto12">Nº 0038 · 17 SET 2026 · 16:42</span>                  
                </div>
              </div>
              <div class="row mt-3">
                <div class="col-12 text-center">
                  <button class="btn btn-success" onclick="javascript:alert('teste')"><i class="ci ci-acao-whatsapp" style="background-color:#fff"></i> Enviar Via WhatsApp</button>
                  <button class="btn btn-light" onclick="javascript:alert('baixar PDF')"><i class="ci ci-acao-download" style="background-color:#1E2933"></i> Baixar PDF</button>
                  <button class="btn btn-light" onclick="javascript:alert('baixar PDF')"><i class="ci ci-acao-link" style="background-color:#1E2933"></i> Copiar link</button>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col-12 text-center">                  
                  <button class="btn btn-sm btn-light" onclick="javascript:alert('baixar PDF')"><i class="ci ci-acao-salvar2" style="background-color:#1E2933"></i> Salvar</button>
                  <button class="btn btn-sm btn-light" onclick="javascript:location='/publico/geral'"><i class="ci ci-acao-visao-geral" style="background-color:#1E2933"></i> Ir para Visão Geral</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
  <!-- FIM DA ÁREA DE CONTEÚDO -->
  <?php include($Raiz . "include/php/rodape.php"); ?>
</body>

</html>
<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /admin/nuvem/index.php
 ***** Conteúdo: 13-admin-armazenamento
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
  <title>Recibo Express - Administração do Sistema - Armazenamento na Nuvem</title>
  <?php include($Raiz . "include/html/header.html"); ?>


  <style>
    /* Estilos base inspirados na imagem */
    :root {
      --sidebar-bg: #ffcc00;      
      /* Azul escuro da barra lateral */
      --body-bg: #0F1F2E;
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
      <?php include($Raiz . "include/php/menu_admin_lateral.php"); ?>

      <!-- CONTEÚDO PRINCIPAL DA PÁGINA -->
      <main class="col-12 col-md-9 col-lg-10">
        <!-- Cabeçalho da Página -->
        <?php include($Raiz . "include/php/menu_admin_superior.php"); ?>
        <div class="main-content">
          <div class="row mt-3">
            <div class="col-12">
              <span class="texto24-admin">Armazenamento na nuvem</span><br>
              <span class="texto13-admin-regular">File Storage S3 / PDFs dos recibos, referência persistida no banco.</span>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-4">
              <div class="card" style="border-color: #1E3348; min-height:120px">
                <div class="card-body" style="background-color: #132638;">
                  <span class="texto10-admin">BUCKET</span><br>
                  <span class="texto16-admin">recibo-express-prod</span><br>
                  <span class="texto11-admin-regular2">Todas ativas em schema</span>
                </div>
              </div>
            </div>
            <div class="col-4">
              <div class="card" style="border-color: #1E3348;">
                <div class="card-body" style="background-color: #132638; min-height:120px">
                  <span class="texto10-admin">ARQUIVOS</span><br>
                  <span class="texto26-admin">3</span><br>
                  <span class="texto11-admin-regular2">PDFs</span>
                </div>
              </div>
            </div>
            <div class="col-4">
              <div class="card" style="border-color: #1E3348;">
                <div class="card-body" style="background-color: #132638; min-height:120px">
                  <span class="texto10-admin">USO</span><br>
                  <span class="texto26-admin">84</span><span class="texto15" style="color: #5F7790"> MB</span><br>
                  <span class="texto11-admin-regular2">saudável</span>
                </div>
              </div>
            </div>
          </div>
          <div class="row mt-3" style="padding-top:20px">
            <div class="col-6">
              <span class="texto14-admin">Visão Geral das Tabelas</span>
            </div>
            <div class="col-6 text-end">
              <span class="texto11-admin-regular" style="color:#849BB0">7 entidades mapeadas</span>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-12">
              <div class="card" style="border-color: #132638;">
                <div class="card-body p-2" style="background-color: #132638;">
                  <table class="table tabela-admin" style="--bs-table-bg: #132638; '--bs-table-color: #C7D2DD;">
                    <thead>
                      <tr>
                        <th scope="col"><span class="texto11-admin">OBJETOS RECENTES</span></th>
                      </tr>
                    </thead>
                    <tbody style="background-color:#1E3348">
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>
                              <div class="d-flex align-items-center gap-2">
                                <div class="p-2 rounded shadow-sm border" style="background-color:#0F1F2E; min-height:32px; min-width: 32px; border-color:#24405A !important;">
                                  <span class="texto9" style="color:#E9784A">PDF</span>
                                </div>
                                <div>
                                  <span class="texto13-admin-regular2">recibos/2026/09/recibo-0038.pdf</span><br>
                                  <span class="texto11-admin-regular2">17 SET · 16:42 · aguardando confirmação</span>
                                </div>
                              </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">
                              <span class="texto11-admin" style="color:#CBD6DF">pendente</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>
                              <div class="d-flex align-items-center gap-2">
                                <div class="p-2 rounded shadow-sm border" style="background-color:#0F1F2E; min-height:32px; min-width: 32px; border-color:#24405A !important;">
                                  <span class="texto9" style="color:#E9784A">PDF</span>
                                </div>
                                <div>
                                  <span class="texto13-admin-regular2">recibos/2026/09/recibo-0038.pdf</span><br>
                                  <span class="texto11-admin-regular2">17 SET · 16:42 · aguardando confirmação</span>
                                </div>
                              </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">
                              <span class="texto11-admin" style="color:#CBD6DF">armazenado</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>
                              <div class="d-flex align-items-center gap-2">
                                <div class="p-2 rounded shadow-sm border" style="background-color:#0F1F2E; min-height:32px; min-width: 32px; border-color:#24405A !important;">
                                  <span class="texto9" style="color:#E9784A">PDF</span>
                                </div>
                                <div>
                                  <span class="texto13-admin-regular2">recibos/2026/09/recibo-0038.pdf</span><br>
                                  <span class="texto11-admin-regular2">17 SET · 16:42 · aguardando confirmação</span>
                                </div>
                              </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">
                              <span class="texto11-admin" style="color:#CBD6DF">armazenado</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>
                              <div class="d-flex align-items-center gap-2">
                                <div class="p-2 rounded shadow-sm border" style="background-color:#0F1F2E; min-height:32px; min-width: 32px; border-color:#24405A !important;">
                                  <span class="texto9" style="color:#E9784A">PDF</span>
                                </div>
                                <div>
                                  <span class="texto13-admin-regular2">recibos/2026/09/recibo-0038.pdf</span><br>
                                  <span class="texto11-admin-regular2">17 SET · 16:42 · aguardando confirmação</span>
                                </div>
                              </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">
                              <span class="texto11-admin" style="color:#CBD6DF">armazenado</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
  <!-- FIM DA ÁREA DE CONTEÚDO -->
  <?php include($Raiz . "include/php/rodape_admin.php"); ?>
</body>

</html>
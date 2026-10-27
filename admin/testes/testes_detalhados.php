<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /admin/testes/testes_detalhados.php
 ***** Conteúdo: 14-admin-testes
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
  <title>Recibo Express - Administração do Sistema - Testes Automarizados</title>
  <?php include($Raiz . "include/html/header.html"); ?>


  <style>
    /* Estilos base inspirados na imagem */
    :root {
      --sidebar-bg: #ffcc00;
      /* #173b5d; */
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

<body class="body-admin">
  <div class="container">
    <div class="row">
      <?php include($Raiz . "include/php/menu_admin_lateral.php"); ?>

      <!-- CONTEÚDO PRINCIPAL DA PÁGINA -->
      <main class="col-12 col-md-9 col-lg-10">
        <!-- Cabeçalho da Página -->
        <?php include($Raiz . "include/php/menu_admin_superior.php"); ?>
        <div class="main-content">
          <div class="col-12 mt-3">
            <span class="texto11">TESTE</span>
          </div>
          <div class="row">
            <div class="col-12">
              <span class="texto24-admin">Testes Detalhados</span><br>
              <span class="texto13-admin-regular">orcamentos_emitir.spec.ts · 12 asserções · última execução há 2 min.</span>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-3">
              <div class="card" style="border-color: #1E3348; min-height:120px">
                <div class="card-body" style="background-color: #132638;">
                  <span class="texto10-admin">RESULTADO</span><br>
                  <span class="texto26-admin" style="color: #34D399">Aprovado</span><br>
                  <i class="ci ci-marcador-verde2" style="margin-right:7px;"></i>                  
                  <span class="texto11-admin-regular2" style="color: #34D399">0 falhas . 0 pulados</span>
                </div>
              </div>
            </div>
            <div class="col-3">
              <div class="card" style="border-color: #1E3348;">
                <div class="card-body" style="background-color: #132638; min-height:120px">
                  <span class="texto10-admin">ASSERÇÕES</span><br>
                  <span class="texto26-admin">12</span><span class="texto26-admin" style="color: #5F7790"> / 12</span><br>
                  <i class="ci ci-marcador-verde2" style="margin-right:7px;"></i>
                  <span class="texto11-admin-regular2">todas verificadas</span>
                </div>
              </div>
            </div>
            <div class="col-3">
              <div class="card" style="border-color: #1E3348;">
                <div class="card-body" style="background-color: #132638; min-height:120px">
                  <span class="texto10-admin">DURAÇÃO</span><br>
                  <span class="texto26-admin">1,24</span><span class="texto14-admin" style="color: #5F7790"> s</span><br>
                  <span class="texto11-admin-regular2">Runner CI #482</span>
                </div>
              </div>
            </div>
            <div class="col-3">
              <div class="card" style="border-color: #1E3348;">
                <div class="card-body" style="background-color: #132638; min-height:120px">
                  <span class="texto10-admin">AMBIENTE</span><br>
                  <span class="texto26-admin">staging</span><br>
                  <span class="texto11-admin-regular2">Node 20.11 · commit a1b2c3d</span>
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
            <div class="col-8">
              <div class="card" style="border-color: #132638;">
                <div class="card-body p-2" style="background-color: #132638;">
                  <table class="table tabela-admin" style="--bs-table-bg: #132638; '--bs-table-color: #C7D2DD;">
                    <thead>
                      <tr>
                        <th scope="col">
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>
                              <span class="texto11-admin">ETAPAS E ASSERÇÕES</span>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">                              
                              <span class="texto11-admin-regular" style="color:#34D399">12 / 12 aprovadas</span>
                            </div>
                          </div>
                        </th>
                      </tr>
                    </thead>
                    <tbody style="background-color:#1E3348">
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>
                              <div class="d-flex align-items-center gap-2">
                                <div class="p-2" style="min-height:32px; min-width: 32px;">
                                  <i class="bi bi-check-circle fs-6" style="color:#34D399"></i>
                                </div>
                                <div>
                                  <span class="texto14-admin-regular">Preenche dados do cliente</span><br>
                                  <span class="texto11-admin-regular2">expect(form.cliente).toBeValid()</span>
                                </div>
                              </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">
                              <span class="texto11-admin-regular" style="color:#CBD6DF">120 ms</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>
                              <div class="d-flex align-items-center gap-2">
                                <div class="p-2" style="min-height:32px; min-width: 32px;">
                                  <i class="bi bi-check-circle fs-6" style="color:#34D399"></i>
                                </div>
                                <div>
                                  <span class="texto14-admin-regular">Seleciona serviço</span><br>
                                  <span class="texto11-admin-regular2">expect(servico).toBe('Faxina residencial')</span>
                                </div>
                              </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">
                              <span class="texto11-admin-regular" style="color:#CBD6DF">88 ms</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>
                              <div class="d-flex align-items-center gap-2">
                                <div class="p-2" style="min-height:32px; min-width: 32px;">
                                  <i class="bi bi-check-circle fs-6" style="color:#34D399"></i>
                                </div>
                                <div>
                                  <span class="texto14-admin-regular">Calcula valor total</span><br>
                                  <span class="texto11-admin-regular2">expect(total).toBe('R$ 180,00')</span>
                                </div>
                              </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">
                              <span class="texto11-admin-regular" style="color:#CBD6DF">64 ms</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>
                              <div class="d-flex align-items-center gap-2">
                                <div class="p-2" style="min-height:32px; min-width: 32px;">
                                  <i class="bi bi-check-circle fs-6" style="color:#34D399"></i>
                                </div>
                                <div>
                                  <span class="texto14-admin-regular">Gera número do recibo</span><br>
                                  <span class="texto11-admin-regular2">expect(numero).toMatch(/RE-2025-\d+/)</span>
                                </div>
                              </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">
                              <span class="texto11-admin-regular" style="color:#CBD6DF">96 ms</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>
                              <div class="d-flex align-items-center gap-2">
                                <div class="p-2" style="min-height:32px; min-width: 32px;">
                                  <i class="bi bi-check-circle fs-6" style="color:#34D399"></i>
                                </div>
                                <div>
                                  <span class="texto14-admin-regular">Renderiza PDF do recibo</span><br>
                                  <span class="texto11-admin-regular2">expect(pdf.pages).toBe(1)</span>
                                </div>
                              </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">
                              <span class="texto11-admin-regular" style="color:#CBD6DF">210 ms</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>
                              <div class="d-flex align-items-center gap-2">
                                <div class="p-2" style="min-height:32px; min-width: 32px;">
                                  <i class="bi bi-check-circle fs-6" style="color:#34D399"></i>
                                </div>
                                <div>
                                  <span class="texto14-admin-regular">Envia para o Amazon S3</span><br>
                                  <span class="texto11-admin-regular2">expect(upload.status).toBe(200)</span>
                                </div>
                              </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">
                              <span class="texto11-admin-regular" style="color:#CBD6DF">340 ms</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>
                              <div class="d-flex align-items-center gap-2">
                                <div class="p-2" style="min-height:32px; min-width: 32px;">
                                  <i class="bi bi-check-circle fs-6" style="color:#34D399"></i>
                                </div>
                                <div>
                                  <span class="texto14-admin-regular">Gera link do WhatsApp</span><br>
                                  <span class="texto11-admin-regular2">expect(link).toContain('wa.me')</span>
                                </div>
                              </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">
                              <span class="texto11-admin-regular" style="color:#CBD6DF">72 ms</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>
                              <div class="d-flex align-items-center gap-2">
                                <div class="p-2" style="min-height:32px; min-width: 32px;">
                                  <i class="bi bi-check-circle fs-6" style="color:#34D399"></i>
                                </div>
                                <div>
                                  <span class="texto14-admin-regular">Marca como aguardando confirmação</span><br>
                                  <span class="texto11-admin-regular2">expect(status).toBe('aguardando')</span>
                                </div>
                              </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">
                              <span class="texto11-admin-regular" style="color:#CBD6DF">110 ms</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>


            <div class="col-4">
              <div class="card" style="border-color: #132638;">
                <div class="card-body p-2" style="background-color: #132638;">
                  <table class="table tabela-admin" style="--bs-table-bg: #132638; '--bs-table-color: #C7D2DD;">
                    <thead>
                      <tr>
                        <th scope="col">
                          <span class="texto11-admin">RESUMO DA EXCEUÇÃO</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody style="background-color:#1E3348">
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>                              
                              <span class="texto12-admin-regular">Ambiente</span>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">                              
                              <span class="texto12-admin-regular">staging</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>                              
                              <span class="texto12-admin-regular">Runner</span>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">                              
                              <span class="texto12-admin-regular">CI #482</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>                              
                              <span class="texto12-admin-regular">Commit</span>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">                              
                              <span class="texto12-admin-regular">a1b2c3d</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>                              
                              <span class="texto12-admin-regular">Cobertura</span>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">                              
                              <span class="texto12-admin-regular">92%</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>                              
                              <span class="texto12-admin-regular">Executado</span>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">                              
                              <span class="texto12-admin-regular">há 2 min</span>
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
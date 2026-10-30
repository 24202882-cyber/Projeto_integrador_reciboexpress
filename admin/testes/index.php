<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /admin/testes/index.php
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
<script>
  //window.carregarResultadosTestes = function() {
async function carregarResultadosTestes() {
  const response = await fetch('/api/run-tests', { method: 'POST' });
  const { data } = await response.json();

  // Atualiza os Cards Superiores
  document.getElementById('total-suites').innerText = data.testSuites;
  document.getElementById('total-tests').innerText = data.totalTests;
  document.getElementById('duracao').innerText = `${data.duration} s`;

  // Renderiza a lista de arquivos .spec na tabela
  const container = document.getElementById('lista-testes');
  container.innerHTML = data.testResults.map(spec => `
    <div class="teste-row">
      <span class="status-dot ${spec.status}"></span>
      <span class="nome-arquivo">${spec.name}</span>
      <span class="duracao">${spec.duration} ms</span>
      <button onclick="verDetalhes('${spec.name}')">Visualizar</button>
    </div>
  `).join('');
}
</script>
<body class="body-admin">
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
              <span class="texto24-admin">Testes Automatizados</span><br>
              <span class="texto13-admin-regular">Vitest - cálculo, PDF, API, confirmação, autorização, ViaCEP e interface do tomador.</span>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-3">
              <div class="card" style="border-color: #1E3348; min-height:120px">
                <div class="card-body" style="background-color: #132638;">
                  <span class="texto10-admin">CONJUNTOS DE TESTES</span><br>
                  <span class="texto26-admin">7</span><br>
                  <i class="ci ci-marcador-verde2" style="margin-right:7px;"></i>                  
                  <span class="texto11-admin-regular2" style="color: #4FB98A">PASS (7 de 7 suítes)</span>
                </div>
              </div>
            </div>
            <div class="col-3">
              <div class="card" style="border-color: #1E3348;">
                <div class="card-body" style="background-color: #132638; min-height:120px">
                  <span class="texto10-admin">TESTES</span><br>
                  <span class="texto26-admin">30</span><span class="texto18-admin">/30</span><br>
                  <i class="ci ci-marcador-verde2" style="margin-right:7px;"></i>
                  <span class="texto11-admin-regular2" style="color: #4FB98A">100% aprovados</span>
                </div>
              </div>
            </div>
            <div class="col-3">
              <div class="card" style="border-color: #1E3348;">
                <div class="card-body" style="background-color: #132638; min-height:120px">
                  <span class="texto10-admin">DURAÇÃO</span><br>
                  <span class="texto26-admin">1,9</span><span class="texto14-admin" style="color: #5F7790"> s</span><br>
                  <span class="texto11-admin-regular2">última execução há 6 min</span>
                </div>
              </div>
            </div>
            <div class="col-3">
              <div class="card" style="border-color: #1E3348;">
                <div class="card-body" style="background-color: #132638; min-height:120px">
                  <span class="texto10-admin">COBERTURA & ASSERTIONS</span><br>
                  <span class="texto26-admin">98,4</span><span class="texto14-admin" style="color: #5F7790"> %</span><br>
                  <span class="texto11-admin-regular2">142 assertions verificadas</span>
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
                        <th scope="col">
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>
                              <span class="texto11-admin">EXECUÇÃO: HÁ 6 MIN</span>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">
                              <span class="texto11-admin" style="color:#34D399">7 PASS </span>
                              <span class="texto11-admin" style="color:#94A3B8">| 0 FAIL</span>                              
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
                              <i class="ci ci-marcador-verde2" style="color:#background-color: #94A3B8; margin-right:7px;"></i>
                              <span class="texto13-admin-regular2">calculo.subtotais.spec</span><span class="texto13-admin-regular"> - subtotais e total</span>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">                              
                              <span class="texto12-admin-regular" style="color:#94A3B8">9 ms</span>
                              <button class="btn btn-sm btn-custom" onclick="javascript:carregarResultadosTestes();">Visualizar</button>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>
                              <i class="ci ci-marcador-verde2" style="color:#background-color: #94A3B8; margin-right:7px;"></i>
                              <span class="texto13-admin-regular2">cpf.validation.spec</span><span class="texto13-admin-regular">- dígitos verificadores</span>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">                              
                              <span class="texto12-admin-regular" style="color:#94A3B8">8 ms</span>
                              <button class="btn btn-sm btn-custom" onclick="javascript:location='testes_detalhados.php'">Visualizar</button>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>
                              <i class="ci ci-marcador-verde2" style="color:#background-color: #94A3B8; margin-right:7px;"></i>
                              <span class="texto13-admin-regular2">viacep.autofill.spec</span><span class="texto13-admin-regular">- busca de CEP</span>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">                              
                              <span class="texto12-admin-regular" style="color:#94A3B8">22 ms</span>
                              <button class="btn btn-sm btn-custom" onclick="javascript:location='testes_detalhados.php'">Visualizar</button>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>
                              <i class="ci ci-marcador-verde2" style="color:#background-color: #94A3B8; margin-right:7px;"></i>
                              <span class="texto13-admin-regular2">pdf.render.spec</span><span class="texto13-admin-regular"> - geração do recibo</span>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">                              
                              <span class="texto12-admin-regular" style="color:#94A3B8">54 ms</span>
                              <button class="btn btn-sm btn-custom" onclick="javascript:location='testes_detalhados.php'">Visualizar</button>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>
                              <i class="ci ci-marcador-verde2" style="color:#background-color: #94A3B8; margin-right:7px;"></i>
                              <span class="texto13-admin-regular2">confirmacao.flow.spec</span><span class="texto13-admin-regular">- hash de evidência</span>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">                              
                              <span class="texto12-admin-regular" style="color:#94A3B8">31 ms</span>
                              <button class="btn btn-sm btn-custom" onclick="javascript:location='testes_detalhados.php'">Visualizar</button>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>
                              <i class="ci ci-marcador-verde2" style="color:#background-color: #94A3B8; margin-right:7px;"></i>
                              <span class="texto13-admin-regular2">autorizacao.spec</span><span class="texto13-admin-regular">- acesso por usuário</span>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">                              
                              <span class="texto12-admin-regular" style="color:#94A3B8">17 ms</span>
                              <button class="btn btn-sm btn-custom" onclick="javascript:location='testes_detalhados.php'">Visualizar</button>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="rounded-2 d-flex justify-content-between align-items-center">
                            <div>
                              <i class="ci ci-marcador-verde2" style="color:#background-color: #94A3B8; margin-right:7px;"></i>
                              <span class="texto13-admin-regular2">interface.tomador.spec</span><span class="texto13-admin-regular">- página pública</span>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">                              
                              <span class="texto12-admin-regular" style="color:#94A3B8">28 ms</span>
                              <button class="btn btn-sm btn-custom" onclick="javascript:location='testes_detalhados.php'">Visualizar</button>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <div id="lista-testes">
                        <div id="total-sites"></div>
                        <div id="total-tests"></div>
                        <div id="duracao"></div>
                      </div>
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
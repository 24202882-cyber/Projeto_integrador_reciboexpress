<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /admin/db/index.php
 ***** Conteúdo: 12-admin-banco
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
  <title>Recibo Express - Administração do Sistema - Banco de Dados</title>
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
          <div class="row mt-3">
            <div class="col-12">
              <span class="texto24-admin">Banco de Dados</span><br>
              <span class="texto13-admin-regular">Visão geral do banco · schema versionado.</span>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-3">
              <div class="card" style="border-color: #1E3348;">
                <div class="card-body" style="background-color: #132638;">
                  <span class="texto10-admin">TABELAS MONITORADAS</span><br>
                  <span class="texto26-admin">7</span><br>
                  <span class="texto11-admin-regular">Todas ativas em schema</span>
                </div>
              </div>
            </div>
            <div class="col-3">
              <div class="card" style="border-color: #1E3348;">
                <div class="card-body" style="background-color: #132638;">
                  <span class="texto10-admin">USUÁRIOS</span><br>
                  <span class="texto26-admin">1</span><br>
                  <span class="texto11-admin-regular">1 autenticação ativa</span>
                </div>
              </div>
            </div>
            <div class="col-3">
              <div class="card" style="border-color: #1E3348;">
                <div class="card-body" style="background-color: #132638;">
                  <span class="texto10-admin">RECIBOS</span><br>
                  <span class="texto26-admin">2</span><br>
                  <span class="texto11-admin-regular">Sincronizado no storage</span>
                </div>
              </div>
            </div>
            <div class="col-3">
              <div class="card" style="border-color: #1E3348;">
                <div class="card-body" style="background-color: #132638;">
                  <span class="texto10-admin">CONFIRMAÇÕES</span><br>
                  <span class="texto26-admin">1</span><br>
                  <span class="texto11-admin-regular">100% hash válidos</span>
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
                <div class="card-body" style="background-color: #132638;">
                  <table class="table tabela-admin" style="--bs-table-bg: #132638; '--bs-table-color: #C7D2DD;">
                    <thead>
                      <tr>
                        <th scope="col"><span class="texto11-admin">TABELA</span></th>
                        <th scope="col" class="text-center"><span class="texto11-admin">REGISTROS</span></th>
                        <th scope="col" class="text-center"><span class="texto11-admin">CLASSIFICAÇÃO</span></th>
                        <th scope="col" class="text-center"><span class="texto11-admin">ESTADO</span></th>
                        <th scope="col" class="text-end"><span class="texto11-admin">AÇÕES RÁPIDAS</span></th>                        
                      </tr>
                    </thead>
                    <tbody style="background-color:#1E3348">
                      <tr>
                        <td><span class="texto135-admin-regular">Usuários</span></td>
                        <td class="text-center"><span class="texto11-regular">1</span></td>
                        <td class="text-center"><span class="texto11-regular">identidade</span></td>
                        <td class="text-center"><span class="texto11-regular" style="color:#34D399">disponível</span></td>
                        <td class="text-end"><button class="btn btn-sm btn-custom me-1" onclick="javascript:location='db_edita.php'">Visualizar</button><button class="btn btn-sm btn-custom" onclick="javascript:location='db_edita.php'"">Editar</button></td>
                      </tr>
                      <tr>
                        <td><span class="texto135-admin-regular">Perfis profissionais</span></td>
                        <td class="text-center"><span class="texto11-regular">1</span></td>
                        <td class="text-center"><span class="texto11-regular">dados pessoais</span></td>
                        <td class="text-center"><span class="texto11-regular" style="color:#34D399">disponível</span></td>
                        <td class="text-end"><button class="btn btn-sm btn-custom me-1" onclick="javascript:location='db_edita.php'"">Visualizar</button><button class="btn btn-sm btn-custom" onclick="javascript:location='db_edita.php'"">Editar</button></td>
                      </tr>
                      <tr>
                        <td><span class="texto135-admin-regular">Serviços</span></td>
                        <td class="text-center"><span class="texto11-regular">1</span></td>
                        <td class="text-center"><span class="texto11-regular">operacional</span></td>
                        <td class="text-center"><span class="texto11-regular" style="color:#34D399">disponível</span></td>
                        <td class="text-end"><button class="btn btn-sm btn-custom me-1" onclick="javascript:location='db_edita.php'"">Visualizar</button><button class="btn btn-sm btn-custom" onclick="javascript:location='db_edita.php'"">Editar</button></td>
                      </tr>
                      <tr>
                        <td><span class="texto135-admin-regular">Recibos</span></td>
                        <td class="text-center"><span class="texto11-regular">1</span></td>
                        <td class="text-center"><span class="texto11-regular">financeiro</span></td>
                        <td class="text-center"><span class="texto11-regular" style="color:#34D399">disponível</span></td>
                        <td class="text-end"><button class="btn btn-sm btn-custom me-1" onclick="javascript:location='db_edita.php'"">Visualizar</button><button class="btn btn-sm btn-custom" onclick="javascript:location='db_edita.php'"">Editar</button></td>
                      </tr>
                      <tr>
                        <td><span class="texto135-admin-regular">Itens de recibo</span></td>
                        <td class="text-center"><span class="texto11-regular">1</span></td>
                        <td class="text-center"><span class="texto11-regular">financeiro</span></td>
                        <td class="text-center"><span class="texto11-regular" style="color:#34D399">disponível</span></td>
                        <td class="text-end"><button class="btn btn-sm btn-custom me-1" onclick="javascript:location='db_edita.php'"">Visualizar</button><button class="btn btn-sm btn-custom" onclick="javascript:location='db_edita.php'"">Editar</button></td>
                      </tr>
                      <tr>
                        <td><span class="texto135-admin-regular">Confirmações</span></td>
                        <td class="text-center"><span class="texto11-regular">1</span></td>
                        <td class="text-center"><span class="texto11-regular">evidência</span></td>
                        <td class="text-center"><span class="texto11-regular" style="color:#34D399">disponível</span></td>
                        <td class="text-end"><button class="btn btn-sm btn-custom me-1" onclick="javascript:location='db_edita.php'"">Visualizar</button><button class="btn btn-sm btn-custom" onclick="javascript:location='db_edita.php'"">Editar</button></td>
                      </tr>
                      <tr>
                        <td><span class="texto135-admin-regular">Salvas dos Tomadores</span></td>
                        <td class="text-center"><span class="texto11-regular">1</span></td>
                        <td class="text-center"><span class="texto11-regular">dados pessoais</span></td>
                        <td class="text-center"><span class="texto11-regular" style="color:#34D399">disponível</span></td>
                        <td class="text-end"><button class="btn btn-sm btn-custom me-1" onclick="javascript:location='db_edita.php'"">Visualizar</button><button class="btn btn-sm btn-custom" onclick="javascript:location='db_edita.php'"">Editar</button></td>
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
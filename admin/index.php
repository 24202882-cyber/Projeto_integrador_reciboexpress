<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /admin/index.php
 ***** Conteúdo: 11-admin
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
  <title>Recibo Express - Administração do Sistema</title>
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
              <span class="texto24-admin">Visão do Sistema</span><br>
              <span class="texto13-admin-regular">Filtre por status, período e cliente.</span>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-3">
              <div class="card" style="border-color: #1E3348;">
                <div class="card-body" style="background-color: #132638;">
                  <span class="texto10-admin">STATUS</span><br>
                  <span class="texto26-admin">UP</span><br>
                  <span class="texto11-admin-regular"><i class="ci ci-marcador-branco"></i> todos no ar</span>
                </div>
              </div>
            </div>
            <div class="col-3">
              <div class="card" style="border-color: #1E3348;">
                <div class="card-body" style="background-color: #132638;">
                  <span class="texto10-admin">USUÁRIOS</span><br>
                  <span class="texto26-admin">1</span><br>
                  <span class="texto11-admin-regular">1 usuários ativos</span>
                </div>
              </div>
            </div>
            <div class="col-3">
              <div class="card" style="border-color: #1E3348;">
                <div class="card-body" style="background-color: #132638;">
                  <span class="texto10-admin">RECIBOS</span><br>
                  <span class="texto26-admin">3</span><br>
                  <span class="texto11-admin-regular">registrados</span>
                </div>
              </div>
            </div>
            <div class="col-3">
              <div class="card" style="border-color: #1E3348;">
                <div class="card-body" style="background-color: #132638;">
                  <span class="texto10-admin">PDFs CATALOGADOS</span><br>
                  <span class="texto26-admin">3</span><br>
                  <span class="texto11-admin-regular">PDFs · 84 MB</span>
                </div>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-12">
              <div class="card" style="border-color: #132638;">
                <div class="card-body" style="background-color: #132638;">
                  <table class="table tabela-admin" style="--bs-table-bg: #132638; '--bs-table-color: #C7D2DD;">
                    <thead>
                      <tr>
                        <th scope="col"><span class="texto11-admin">RESUMO</span></th>
                        <th scope="col" class="text-end"><span class="texto11"></span></th>
                      </tr>
                    </thead>
                    <tbody style="background-color:#1E3348">
                      <tr>
                        <td><span class="texto135-admin-regular">Banco de Dados</span></td>
                        <td class="text-end"><span class="texto11-regular" style="color:#4FB98A">Ok</span></td>
                      </tr>
                      <tr>
                        <td><span class="texto135-admin-regular">Emissão de Recibos</span></td>
                        <td class="text-end"><span class="texto11-regular" style="color:#4FB98A">Ok</span></td>
                      </tr>
                      <tr>
                        <td><span class="texto135-admin-regular">Cadastro de perfis</span></td>
                        <td class="text-end"><span class="texto11-regular" style="color:#4FB98A">Ok</span></td>
                      </tr>
                      <tr>
                        <td><span class="texto135-admin-regular">Última execução de testes</span></td>
                        <td class="text-end"><span class="texto135-admin">Há 6 min</span><span class="texto11-regular" style="color:#4FB98A">&nbsp;&nbsp;100%</span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-12">
              <div class="card" style="border-color: #132638;">
                <div class="card-body" style="background-color: #132638;">
                  <div class="row mt-1 ps-2 pe-2">
                    <div class="col-12">
                      <i class="ci ci-banco-dados fs-5" style="background-color: #4FB98A"></i><span class="texto16-admin"> Rotinas</span>
                    </div>
                  </div>
                  <div class="row mt-3 ps-2 pe-2">
                    <div class="col-12">
                      <input type="text" class="form-control custom-dark-input" placeholder="Filtrar rotinas" />                      
                    </div>
                  </div>
                  <div class="row mt-3 ps-2 pe-2">
                    <div class="col-12">
                      <div class="card" style="border-color: #1E3348;">
                        <div class="card-body" style="background-color: #112233;">
                          <div class="col-12 text-center">
                            <img src="/design/cilindro.svg">
                          </div>
                          <div class="col-12 text-center">
                            <span class="texto14" style="color:#fff">Serviço de rotina indisponível no momento.</span>
                          </div>
                          <div class="col-12 mb-3 text-center">
                            <span class="texto12-admin-regular">O daemon de execução local ou worker Redis não está respondendo. As execuções automáticas estão temporariamente suspensas.</span>
                          </div>
                          <div class="col-12 mt-3 text-center">
                            <button class="btn btn-sm btn-primary" onclick="javascript:alert('teste');"><i class="ci ci-reciclar" style="background-color:#FFFFFF"></i> Tentar Reconectar</button>
                            <button class="btn btn-sm btn-custom" onclick="javascript:alert('teste');"><i class="ci ci-log" style="background-color:#FFFFFF"></i> Ver Logs do Worker</button>
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
      </main>
    </div>
  </div>
  <!-- FIM DA ÁREA DE CONTEÚDO -->
  <?php include($Raiz . "include/php/rodape_admin.php"); ?>
</body>

</html>
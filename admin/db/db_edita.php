<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /admin/db/db_edita.php
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
  <title>Recibo Express - Administração do Sistema - Banco de Dados - Visualização/Edição</title>
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

<style>
  .header-personalizado {
    --bs-table-bg: #0f1c2c;
  }
</style>

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
            <div class="col-5">
              <span class="texto24-admin">Recibos</span><br>
              <span class="texto13-admin-regular" style="color: #94A3B8">Visualização completa dos registros da tabela</span><span class="texto13-admin-regular" style="color:#CBD5E1"> · 2 registros encontrados </span>
            </div>
            <div class="col-2">
              <div class="d-flex flex-column h-100">
                <button class="btn btn-sm btn-custom mt-auto" onclick="javascript:alert('')"><span class="texto12-admin-regular">Exportar (CSV / JSON)</span></button>
              </div>
            </div>
            <div class="col-2">
              <div class="d-flex flex-column h-100">
                <button class="btn btn-sm btn-primary mt-auto" onclick="javascript:location='/admin/db')"><span class="texto12-admin-regular2">Inserir Registro</span></button>
              </div>
            </div>
            <div class="col-3">
              <div class="d-flex flex-column h-100">
                <button class="btn btn-sm btn-custom mt-auto" onclick="javascript:location='/admin/db';"><i class="bi bi-chevron-left texto12-admin-regular"></i><span class="texto12-admin-regular"> Voltar para Banco de Dados</span></button>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-12">
              <div class="col-12 p-3 rounded-2 d-flex justify-content-between align-items-center" style="background-color: #132638;">
                <input type="text" class="form-control form-control-sm w-50 custom-dark-input2" placeholder="Filtrar por ID, tomador, competência ou valor..." />
                <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">
                  <button class="btn btn-sm btn-custom" onclick="javascript:alert('')"><i class="ci ci-colunas fs-6 texto12-admin-regular"></i><span class="texto12-admin-regular"> Colunas visíveis</span></button>
                  <span class="texto12-admin-regular"> Mostrando 2 de 2 registros</span>
                </div>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-12">
              <div class="col-12 p-2 rounded" style="background-color: #132638;">
                <table class="table table-responsive tabela-admin">
                  <thead class="header-personalizado">
                    <tr>
                      <th scope="col"><span class="texto11-admin">ID</span></th>
                      <th scope="col"><span class="texto11-admin">Competência</span></th>
                      <th scope="col" class="text-end"><span class="texto11-admin">Valor</span></th>
                      <th scope="col"><span class="texto11-admin">Tomador</span></th>
                      <th scope="col"><span class="texto11-admin">CPF / CNPJ</span></th>
                      <th scope="col"><span class="texto11-admin">Status</span></th>
                      <th scope="col"><span class="texto11-admin">Criado em</span></th>
                      <th scope="col"><span class="texto11-admin">Ações</span></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><span class="texto11-regular">e8b1a472-9c31-4a8f-897d-60dfba61c92a</span></td>
                      <td><span class="texto11-regular">Setembro 2026</span></td>
                      <td class="text-end"><span class="texto11-regular">R$ 180,00</span></td>
                      <td><span class="texto11-regular" style="color:#E2E8F0">Mariana Lopes</span></td>
                      <td><span class="texto11-regular">123.456.789-45</span></td>
                      <td><span class="texto11-regular">Emitido/Confirmado</span></td>
                      <td><span class="texto11-regular">17/09/2026 16:42</span></td>
                      <td class="text-end"><button class="btn btn-sm btn-custom" onclick="javascript:alert('teste')">Editar</button></td>
                    </tr>
                    <tr>
                      <td><span class="texto11-regular">3f92d110-1b22-42da-a5a4-9271887e5b53</span></td>
                      <td><span class="texto11-regular">Setembro 2026</span></td>
                      <td class="text-end"><span class="texto11-regular">R$ 320,00</span></td>
                      <td><span class="texto11-regular" style="color:#E2E8F0">Carlos Almeida</span></td>
                      <td><span class="texto11-regular">987.654.321-00</span></td>
                      <td><span class="texto11-regular">Aguardando envio</span></td>
                      <td><span class="texto11-regular">14/09/2026 11:15</span></td>
                      <td class="text-end"><button class="btn btn-sm btn-custom" onclick="javascript:alert('teste')">Editar</button></td>
                    </tr>
                  </tbody>
                  <tfoot style="background-color: #132638;">
                    <tr>
                      <td colspan="8">
                        <div class="rounded-2 d-flex justify-content-between align-items-center">
                          <span class="texto12-admin-regular">Página 1 de 1 - 25 linhas por página</span>
                          <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">
                            <button class="btn btn-sm btn-custom" onclick="javascript:alert('')"><span class="texto12-admin-regular">Anterior</span></button>
                            <button class="btn btn-sm btn-custom" onclick="javascript:alert('')"><span class="texto12-admin-regular">Próximo</span></button>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-12 mb-3">
              <div class="col-12 p-3 rounded" style="background-color: #132638;">
                <div class="rounded-2 d-flex justify-content-between align-items-center">
                  <div>
                    <span class="texto14-admin">Editar Registro</span>
                    <span class="texto12-admin-regular"> (e8b1a472-9c31-4a8f-897d-60dfba61c92a)</span>
                  </div>
                  <div class="d-grid gap-2 d-md-flex justify-content-end align-items-center">
                    <button class="btn btn-sm btn-custom" onclick="javascript:alert('')"><span class="texto12-admin-regular">Cancelar</span></button>
                    <button class="btn btn-sm btn-primary" onclick="javascript:alert('')"><span class="texto12-admin-regular2">Salvar alterações</span></button>
                  </div>
                </div>
                <div class="col-12 mt-3">
                  <form class="row g-3">
                    <div class="col-3">
                      <label for="inputCity" class="form-label texto12-admin-regular mb-1">Competência</label>
                      <input type="text" class="form-control custom-dark-input2" id="inputCity" value="Setembro / 2026">
                    </div>
                    <div class="col-3">
                      <label for="inputState" class="form-label texto12-admin-regular mb-1">Valor Total (R$)</label>
                      <input type="text" class="form-control custom-dark-input2" id="inputState" value="180,00">
                    </div>
                    <div class="col-3">
                      <label for="inputZip" class="form-label texto12-admin-regular mb-1">Nome do Tomador</label>
                      <input type="text" class="form-control custom-dark-input2" id="inputZip" value="Mariana Lopes">
                    </div>
                    <div class="col-3">
                      <label for="inputZip" class="form-label texto12-admin-regular mb-1">CPF / CNPJ</label>
                      <input type="text" class="form-control custom-dark-input2" id="inputZip" value="123.456.789-45">
                    </div>
                    <div class="col-3">
                      <label for="inputZip" class="form-label texto12-admin-regular mb-1">Status</label>
                      <input type="text" class="form-control custom-dark-input2" id="inputZip" value="Emitido / Confirmado">
                    </div>
                  </form>
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
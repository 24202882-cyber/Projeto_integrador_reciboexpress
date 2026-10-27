<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /publico/recibo/index.php
 ***** Conteúdo: 08-recibo
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
  <title>Recibo Express - Recibos</title>
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
          <div class="col-12 mt-3">
            <span class="texto11">RECIBOS . EMISSÃO</span>
          </div>
          <div class="row">
            <div class="col-12">
              <span style="color:#1E2933; font-weight:700; font-size: 1.75em;">Emitir recibo</span><br>
              <span class="texto-corpo">Confira à esquerda; o documento se atualiza à direita antes de gerar o PDF.</span>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-8">
              <div class="row">
                <div class="col-12 mb-2">
                  <div class="card rounded-3 border-1 p-4" style="background-color: #FFF; border-color: #CCC;">
                    <span class="texto12 mb-3">PAGADOR</span>
                    <form method="post" id="FCLIENTE_EDITA" name="FCLIENTE_EDITA" action="cliente_edita.php">
                      <div class="row mb-3">
                        <div class="col-12">
                          <label for="NOME_CLI" class="form-label mb-1 texto-campo">CLIENTE</label>
                          <input type="text" class="form-control" id="NOME_CLI" name="NOME_CLI" placeholder="Nome completo" value="Mariana Lopes">
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-6">
                          <label for="CPF_CLI" class="form-label mb-1 texto-campo">CPF DO PAGADOR</label>
                          <input type="text" class="form-control" id="CPF_CLI" name="CPF_CLI" placeholder="CPF" value="987.000.000-00">
                        </div>
                        <div class="col-6">
                          <label for="CEP_CLI" class="form-label mb-1 texto-campo">CEP</label>
                          <input type="text" class="form-control" id="CEP_CLI" name="CEP_CLI" placeholder="CEP" value="00001-000">
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12 mb-2">
                  <div class="card rounded-3 border-1 p-4" style="background-color: #FFF; border-color: #CCC;">
                    <span class="texto12 mb-3">SERVIÇOS & DATA</span>

                    <div class="row">
                      <div class="col-8">
                        <span class="texto17">Faxina completa</span><br>
                        <span class="texto12-regular">por diária - R$ 180,00</span>
                      </div>
                      <div class="col-4 d-flex justify-content-end">
                        <div class="row g-3 align-items-center">
                          <div class="col-auto">
                            <input type="text" class="form-control" id="valorserv1" name="valorserv1" value="1" style="max-width:50px">
                          </div>
                          <div class="col-auto">
                            <span id="valorserv1" class="texto16">
                              R$ 180,00
                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="col-12">
                        <hr>
                      </div>
                      <div class="col-12 mb-3">
                        <button class="btn btn-outline-custom">
                          + Adicionar serviço
                        </button>
                      </div>
                      <div class="col-12 mt-3">
                        <div class="row">
                          <div class="col-6">
                            Subtotal
                          </div>
                          <div class="col-6 text-end">
                            R$ 180,00
                          </div>
                        </div>
                      </div>
                      <div class="col-12">
                        <hr>
                      </div>
                      <div class="col-12">
                        <div class="row">
                          <div class="col-6">
                            TOTAL DO RECIBO
                          </div>
                          <div class="col-6 text-end">
                            <span class="texto28">R$ 180,00</span>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 mt-3 mb-3">
                        <i class="ci ci-menu-armazenamento"></i>
                        <span class="texto-regular"> O PDF será gerado e salvo na nuvem do seu perfil ao emitir.</span>
                      </div>
                      <div class="col-12 mt-2">
                        <button class="btn btn-custom" style="width: 100%" onclick="javascript:location='/publico/recibo/recibo-pronto.php';">
                          <i class="ci ci-menu-recibos"></i> Gerar recibo e enviar para aprovação do cliente
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-4">
              <div class="row">
                <div class="col-12 mb-2">
                  <span class="texto11">PRÉVIA DO DOCUMENTO</span>
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-6 mt-2 mb-2">
                          <span class="texto10" style="color:#163B5C">RECIBO</span>
                        </div>
                        <div class="col-6 mt-2 mb-2 text-end">
                          <span class="texto9">RECIBO Nº</span><br>
                          <span class="texto18" style="color:#E9784A">000038</span>
                        </div>
                      </div>  
                      <div class="row">
                        <div class="col-12">
                          <span class="texto9">RECEBI DE</span>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <span class="texto21">Mariana Lopes</span><br>
                          <span class="texto9">A IMPORTÂNCIA DE</span>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <span class="texto19" style="color: #697783; vertical-align: super;">R$</span><span class="texto40">180,00</span>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <span class="texto9">REFERENTE A</span><br>
                          <span class="texto13">Faxina residencial</span>
                        </div>
                        <div class="col-6 text-end">
                          <span class="texto9">REALIZADA EM</span><br>
                          <span class="texto13">17 SET 2026</span>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <hr>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <span class="texto15">Elenir</span><br>
                          <span class="texto11-regular">CPF ...,...,201-..</span>
                        </div>
                        <div class="col-6 d-flex flex-column justify-content-end text-end">
                          <span class="texto9">PRÉVIA</span>
                          <span class="texto9">NÃO EMITIDO</span>
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
  <?php include($Raiz . "include/php/rodape.php"); ?>
</body>

</html>
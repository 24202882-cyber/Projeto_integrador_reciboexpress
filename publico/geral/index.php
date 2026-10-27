<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /publico/geral/index.php
 ***** Conteúdo: 04-visao-geral
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
// Corpo
// EOF Corpo

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Recibo Express - Visão Geral</title>
  <?php include($Raiz . "include/html/header.html"); ?>
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
            <span class="texto11">VISÃO GERAL</span>
          </div>
          <div class="row">
            <div class="col-sm-10">
              <span style="color:#1E2933; font-weight:700; font-size: 1.75em;">Olá, <?php echo RetornaNomeUsuario(); ?></span><br>
              <span class="texto-corpo">Aqui está o que está acontecendo com seu trabalho hoje.</span>
            </div>
            <div class="col-sm-2">
              <div class="d-flex flex-column h-100">
                <button class="btn btn-custom float-end mt-auto">
                  <i class="bi bi-plus-lg me-1"></i> Emitir Recibo
                </button>     
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-8">              
              <div class="row">
                <div class="col-12 mt-2 mb-2">
                  <span class="texto-pirulito">HOJE</span>
                  <span class="texto-pirulito float-end"><a href="" class="link-padrao">VER AGENDA</a></span>              
                </div>
              </div>
              <div class="card border-0 shadow-sm p-3">
                <div class="row">
                  <div class="col-3">
                    <span style="color:#163B5C; font-weight: 700; font-size: 0.93em">08:00</span><br>
                    <span class="texto-menor4">MANHÃ</span>
                  </div>
                  <div class="col-6">
                    <span class="texto-linha-tab">Faxina residencial</span><br>
                    <span class="texto-corpo">Mariana Lopes</span>
                  </div>
                  <div class="col-3 text-end">
                    <span class="texto-linha-tab">R$ 180,00</span>
                    <i class="ci ci-marcador-amarelo"></i><span style="font-size:0.66em; color:#B4531F; font-weight:700">A FAZER</span>
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="col-3">
                    <span style="color:#163B5C; font-weight: 700; font-size: 0.93em">14:30</span><br>
                    <span class="texto-menor4">TARDE</span>
                  </div>
                  <div class="col-6">
                    <span class="texto-linha-tab">Limpeza de vidros</span><br>
                    <span class="texto-corpo">Carlos Almeida</span>
                  </div>
                  <div class="col-3 text-end">
                    <span class="texto-linha-tab">R$ 60,00</span>
                    <i class="ci ci-marcador-amarelo"></i><span style="font-size:0.66em; color:#B4531F; font-weight:700">A FAZER</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-4">
              <div class="card border-1 shadow-sm p-3" style="border-color:#F1D3C4; background-color:#FBEDE6">
                <div class="row">
                  <div class="col-12 mb-2">
                    <div class="d-flex align-items-center gap-2">                      
                      <div class="bg-white p-2 rounded shadow-sm">
                        <i class="ci ci-status-relogio"></i>
                      </div>                      
                      <span class="texto15">2 aguardando confirmação</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-8">
                    <span class="texto135-negrito">Mariana Lopes</span><span class="texto135"> . #0038</span>
                  </div>
                  <div class="col-4 text-end">
                    <span class="texto135-negrito">R$ 180,00</span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-8">
                    <span class="texto135-negrito">Carlos Almeida</span><span class="texto135"> . #0035</span>
                  </div>
                  <div class="col-4 text-end">
                    <span class="texto135-negrito">R$ 60,00</span>
                  </div>
                </div>                
              </div>              
            </div>            
          </div>          
          <div class="row mt-3">
            <div class="col-8">              
              <div class="row">
                <div class="col-12 mt-2 mb-2">
                  <span class="texto-pirulito">ÚLTIMA ATIVIDADE</span>
                </div>
              </div>
              <div class="card border-0 shadow-sm p-3">
                <div class="row">
                  <div class="col-12 mb-2">
                    <i class="ci ci-marcador-verde"></i><span class="texto-corpo">Mariana confirmou o recibo #0037 · há 2 h</span>                    
                  </div>
                  <div class="col-12 border-top pt-2 mb-2">
                    <i class="ci ci-marcador-azul"></i><span class="texto-corpo">Recibo #0038 enviado para Mariana pelo WhatsApp. ontem</span>
                  </div>
                  <div class="col-12 border-top pt-2">
                    <i class="ci ci-marcador-amarelo"></i><span class="texto-corpo">Orçamento aprovado por Carlos Almeida. · 12 SET</span>
                  </div>
                </div>
               
              </div>
            </div>
            <div class="col-4">
              <div class="card border-0 shadow-sm p-3">
                <div class="col-12 mb-3">
                  <span class="texto-pirulito">NO MÊS DE SETEMBRO</span>
                </div>
                <div class="col-12">
                  <div class="text-nowrap">
                    <span class="align-middle texto-regular texto-negrito">R$</span>
                    <span class="align-middle" style="color:# 1E2933; font-size:2em; font-weight: 700;"> 2.840</span>
                  </div>
                  <span class="texto-pirulito">RECEBIDOS</span>                  
                </div>
                <div class="col-12">
                  <div class="row border-top pt-3 mt-3">
                    <div class="col-4">
                      <span class="texto-preto texto-negrito" style="font-size:1.06em">12</span><br>
                      <span class="texto-pirulito">TRABALHOS</span>
                    </div>
                    <div class="col-4">
                      <span class="texto-amarelo texto-negrito" style="font-size:1.06em">2</span><br>
                      <span class="texto-pirulito">AGUARDANDO</span>                      
                    </div>
                    <div class="col-4">
                      <span class="texto-verde texto-negrito" style="font-size:1.06em">10</span><br>
                      <span class="texto-pirulito">CONFIRMADOS</span>                      
                    </div>

                  </div>
                </div>

              </div>
            </div>
          </div>


        <!-- ÁREA DE CONTEÚDO (Insira o conteúdo específico de cada página aqui) -->

<!--
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <h5 class="card-title mb-3">Conteúdo Principal</h5>
            <p class="card-text">Substitua este trecho pelo formulário, tabela ou elementos específicos da sua nova página.</p>
          </div>
        </div>
-->        
        </div>        
        <!-- FIM DA ÁREA DE CONTEÚDO -->

      </main>

    </div>
  </div>
  <!-- Bootstrap 5 JS Bundle (com Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <?php include($Raiz . "include/php/rodape.php"); ?>
</body>

</html>
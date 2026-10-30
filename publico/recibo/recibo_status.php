<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /publico/recibo/recibo_status.php
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
include($Raiz . "include/php/header.php");
include($Raiz . "include/php/funcoes.php");
include($Raiz . "include/php/key.php");
include($Raiz . "conexao/db.php");
$chave_perfil = $_SESSION["CHAVE_PERFIL"];
$chave_recibo = "";
$situacao_recibo = "";
$msg = "";
if ($_SERVER['REQUEST_METHOD'] == "GET") {
  $chave_recibo = "";
  $chave_perfil = "";
  $situacao_recibo = "";

  $id1 = "";
  $id2 = "";
  if (isset($_GET["id1"])) {
    $id1 = $_GET["id1"];
    //echo $id1 . "<br>";
    $id1 = descriptografar($id1);
    //echo $id1 . "<br>";
  }
  if (isset($_GET["id2"])) {
    $id2 = $_GET["id2"];
    //echo $id2 . "<br>";
    $id2 = descriptografar($id2);
    //echo $id2 . "<br>";
    //?chave_perfil&chave_recibo&situacao_recibo
    //?id1=000001&id2=000001&id3=XXXXXXXXXXXXXXXXX
    $i = strpos($id1, "id1=");
    if ($i === false) {
    } else {
      $i += 4;
      $ii = strpos($id1, "&");
      if ($ii === false) {
      } else {
        $chave_perfil = substr($id1, $i, $ii - $i);
        //echo "CHAVE_PERFIL: " . $chave_perfil . "<br>";
        $id1 = substr($id1, $ii + 1);
        //echo $id1 . "<br>";
      }
    }

    $i = strpos($id1, "id2=");
    if ($i === false) {
    } else {
      $i += 4;
      $ii = strpos($id1, "&");
      if ($ii === false) {
      } else {
        $chave_recibo = substr($id1, $i, $ii - $i);
        //echo "CHAVE_RECIBO: " . $chave_recibo . "<br>";
        $id1 = substr($id1, $ii + 1);
        //echo $id1 . "<br>";
      }
    }
    $i = strpos($id1, "id3=");
    if ($i === false) {
    } else {
      $i += 4;
      $ii = strpos($id1, "&");
      if ($ii === false) {
      } else {
        $situacao_recibo = substr($id1, $i, $ii - $i);
        echo "SITUACAO_RECIBO: " . $situacao_recibo . "<br>";
        $id1 = substr($id1, $ii + 1);
        echo $id1 . "<br>";
      }
    }
    $situacao_recibo = $id2;
    //echo $id2 . "<br>";
    //echo "result<br>";
    if ($chave_recibo != "" && $chave_perfil != "" && $situacao_recibo != "") {
      abre_db();
      $abre_diario = abre_diario("trecibo", "chave_recibo", $chave_recibo, $campoexcluidos = array(""));
      $strsql = "
      update
      trecibo
      set
      situacao_recibo = :vsituacao_recibo 
      ,dtu_recibo = now() ";
      if ($situacao_recibo == "PAGO") {
        $strsql .= ",dtpagto_recibo = now() ";        
      }
      if ($situacao_recibo == "RECUSADO") {
        $strsql .= ",dtrecusado_recibo = now() ";        
      }
      $strsql .= "
      where    
      trecibo.chave_recibo = :vchave_recibo and
      trecibo.chave_perfil = :vchave_perfil and
      trecibo.caixa_recibo = 1
      ";
      $qrecibo = $pdo->prepare($strsql);
      $qrecibo->bindParam(":vchave_recibo", $chave_recibo);
      $qrecibo->bindParam(":vchave_perfil", $chave_perfil);
      $qrecibo->bindParam(":vsituacao_recibo", $situacao_recibo);
      $qrecibo->execute();
      fecha_diario("trecibo", "chave_recibo", $chave_recibo, $abre_diario, $campoexcluidos = array(""));
    }
  }
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  VerificaSessao();
  if (isset($_POST["chave_recibo"])) {
    $chave_recibo = $_POST["chave_recibo"];
  }
  if (isset($_POST["situacao_recibo"])) {
    $situacao_recibo = $_POST["situacao_recibo"];
  }
  if ($chave_recibo != "" && $situacao_recibo != "") {
    abre_db();
    $abre_diario = abre_diario("trecibo", "chave_recibo", $chave_recibo, $campoexcluidos = array(""));
    $strsql = "
    update
    trecibo
    set
    situacao_recibo = :vsituacao_recibo 
    ,dtu_recibo = now() ";
    if ($situacao_recibo == "PAGO") {
      $strsql .= ",dtpagto_recibo = now() ";
    }
    if ($situacao_recibo == "CANCELADO") {
      $strsql .= ",dtcancela_recibo = now() ";
    }
    $strsql .= "
    where    
    trecibo.chave_recibo = :vchave_recibo and
    trecibo.chave_perfil = :vchave_perfil and
    trecibo.caixa_recibo = 1
    ";
    $qrecibo = $pdo->prepare($strsql);
    $qrecibo->bindParam(":vchave_recibo", $chave_recibo);
    $qrecibo->bindParam(":vchave_perfil", $chave_perfil);
    $qrecibo->bindParam(":vsituacao_recibo", $situacao_recibo);
    $qrecibo->execute();
    fecha_diario("trecibo", "chave_recibo", $chave_recibo, $abre_diario, $campoexcluidos = array(""));
    header("Location: /publico/recibo/recibo_edita.php", false);
  }
  else {
    header("Location: /publico/recibo/recibo_edita.php", false);
  }
  die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Recibo Express - Status do Recibo</title>
  <?php include($Raiz . "include/html/header.html"); ?>
</head>
<body>
  <div class="container pt-2">
    <div class="container pt-2">
      <div class="row" style="padding-top:100px">
        <div class="col-12 text-center">
          <img class="img-fluid" src="/identidade/reciboexpress2.png">
        </div>
      </div>
      <?php if ($situacao_recibo == "APROVADO") { ?>
        <div class="container my-5">
          <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
              <div class="card border-0 shadow-sm rounded-4 text-center p-4" style="background-color: #ffffff;">
                <div class="card-body">
                  <!-- Ícone de Confirmação -->
                  <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle" style="width: 60px; height: 60px;">
                      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                        <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z" />
                      </svg>
                    </span>
                  </div>
                  <!-- Título -->
                  <h3 class="fw-bold mb-3" style="color: #163b5c;">Orçamento Aprovado com Sucesso!</h3>
                  <!-- Mensagem de Agradecimento -->
                  <p class="text-secondary mb-4 fs-6">
                    Agradecemos pela confiança em nosso trabalho. Estamos prontos para iniciar a prestação dos serviços estritamente de acordo com as suas necessidades e expectativas.
                  </p>
                  <!-- Detalhes do Acompanhamento (Opcional) -->
                  <div class="p-3 mb-4 rounded-3 text-start" style="background-color: #f8f9fa; border-left: 4px solid #163b5c;">
                    <small class="d-block text-muted"><strong>Próximo passo:</strong> Nossa equipe entrará em contato em breve para alinhar os detalhes do início do atendimento.</small>
                  </div>
                  <!-- Botão de Ação -->
                  <a href="https://reciboexpress.kraftwork.com.br" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm" style="background-color: #163b5c; border-color: #163b5c;">
                    Voltar ao Painel
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
      <?php if ($situacao_recibo == "RECUSADO") { ?>
        <!-- Seção de Orçamento Não Aprovado / Recusado -->
        <div class="container my-5">
          <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
              <div class="card border-0 shadow-sm rounded-4 text-center p-4" style="background-color: #ffffff;">
                <div class="card-body">
                  <!-- Ícone de Alerta / Recusa -->
                  <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center bg-danger text-white rounded-circle" style="width: 60px; height: 60px;">
                      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                      </svg>
                    </span>
                  </div>
                  <!-- Título -->
                  <h3 class="fw-bold mb-3" style="color: #163b5c;">Orçamento Não Aprovado</h3>
                  <!-- Mensagem de Agradecimento e Abertura -->
                  <p class="text-secondary mb-4 fs-6">
                    Agradecemos pelo tempo dedicado à análise de nossa proposta. Lamentamos que este orçamento não atenda às suas necessidades no momento, mas permanecemos à disposição para futuras demandas ou eventuais ajustes.
                  </p>
                  <!-- Detalhes para Reavaliação -->
                  <div class="p-3 mb-4 rounded-3 text-start" style="background-color: #f8f9fa; border-left: 4px solid #163b5c;">
                    <small class="d-block text-muted"><strong>Gostaria de reajustar o projeto?</strong> Caso queira revisar o escopo, prazos ou valores para adaptá-los ao seu orçamento, entre em contato conosco.</small>
                  </div>
                  <!-- Botões de Ação -->
                  <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                    <a href="mailto:suporte@reciboexpress.com.br" class="btn btn-outline-secondary px-4 py-2 rounded-pill shadow-sm">
                      Solicitar Ajuste
                    </a>
                    <a href="https://reciboexpress.kraftwork.com.br" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm" style="background-color: #163b5c; border-color: #163b5c;">
                      Voltar ao Painel
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
  <?php include($Raiz . "include/php/rodape.php"); ?>
</body>

</html>
<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /publico/servico/index.php
 ***** Conteúdo: 06-servico
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
VerificaSessao();
// Variáveis de controle
$ok = true;
$html = "";
$html_tit = "";
$html_msg = "";
$html_lnk = "";
$html_btn = "";
$email_usuario = "";
$chave_perfil = $_SESSION["CHAVE_PERFIL"];
// EOF Variáveis de controle
$aftergopage = "";
abre_db();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $acao = $_POST["acao"];
  $chave_servico = $_POST["chave_servico"];
  $descr_servico = $_POST["descr_servico"];
  $cobranca_servico = $_POST["cobranca_servico"];
  $valor_servico = $_POST["valor_servico"];
  $caixa_servico = "1";
  if ($ok && $acao != "e") {
    if ($chave_servico == "") {
      $strsql = "
      insert 
      into 
      tservico 
      (chave_perfil
      ,descr_servico
      ,cobranca_servico
      ,valor_servico
      ,caixa_servico
      ) 
      values 
      (:vchave_perfil
      ,:vdescr_servico
      ,:vcobranca_servico
      ,:vvalor_servico
      ,:vcaixa_servico
      )";
      $qservico = $pdo->prepare($strsql);
      $qservico->bindParam(":vchave_perfil", $chave_perfil);
      $qservico->bindParam(":vdescr_servico", $descr_servico);
      $qservico->bindParam(":vcobranca_servico", $cobranca_servico);
      $qservico->bindParam(":vvalor_servico", $valor_servico);
      $qservico->bindParam(":vcaixa_servico", $caixa_servico);
      $qservico->execute();
      $chave_servico = $pdo->lastInsertId();
      fecha_diario("tservico", "chave_servico", $chave_servico, $abre_diario = array(), $campoexcluidos = array(""));
      header("Location: /publico/servico");
      die();
    } else {
      $abre_diario = abre_diario("tservico", "chave_servico", $chave_servico, $campoexcluidos = array(""));
      $strsql = "
      update 
      tservico 
      set       
      descr_servico = :vdescr_servico
      ,cobranca_servico = :vcobranca_servico
      ,valor_servico = :vvalor_servico      
      where 
      chave_servico = :vchave_servico and 
      chave_perfil = :vchave_perfil and 
      caixa_servico = 1
      ";
      $qservico = $pdo->prepare($strsql);
      $qservico->bindParam(":vchave_servico", $chave_servico);
      $qservico->bindParam(":vchave_perfil", $chave_perfil);
      $qservico->bindParam(":vdescr_servico", $descr_servico);
      $qservico->bindParam(":vcobranca_servico", $cobranca_servico);
      $qservico->bindParam(":vvalor_servico", $valor_servico);
      $qservico->execute();
      fecha_diario("tservico", "chave_servico", $chave_servico, $abre_diario, $campoexcluidos = array(""));
      header("Location: /publico/servico");
      die();
    }
  }
  if ($ok && $acao == "e") {
    if ($chave_servico != "") {
      $abre_diario = abre_diario("tservico", "chave_servico", $chave_servico, $campoexcluidos = array(""));
      $strsql = "
      update 
      tservico 
      set       
      caixa_servico = 0
      where 
      chave_servico = :vchave_servico and 
      chave_perfil = :vchave_perfil and 
      caixa_servico = 1
      ";
      $qservico = $pdo->prepare($strsql);
      $qservico->bindParam(":vchave_servico", $chave_servico);
      $qservico->bindParam(":vchave_perfil", $chave_perfil);
      $qservico->execute();
      fecha_diario("tservico", "chave_servico", $chave_servico, $abre_diario, $campoexcluidos = array(""));
      header("Location: /publico/servico");
      die();
    }
  }
}
$acao = "";
$btn_confirmar = "<i class='ci ci-acao-adicionar'></i> Adicionar aos serviços";
$btn_confirmar_tipo = "btn btn-custom w-100";
$html = "";
$strsql = "
select
tservico.*
from
tservico 
where    
chave_perfil = :vchave_perfil and
caixa_servico = 1
order by chave_servico
";
$qservico = $pdo->prepare($strsql);
$qservico->bindParam(":vchave_perfil", $chave_perfil);
$qservico->execute();
while ($tservico = $qservico->fetch(PDO::FETCH_ASSOC)) {
  $chave_servico = $tservico["chave_servico"];
  $descr_servico = $tservico["descr_servico"];
  $cobranca_servico = strtolower($tservico["cobranca_servico"]);
  $valor_servico = "R$ " . number_format($tservico["valor_servico"], 2, ',', '.');
  if ($html != "") {
    $html .= "
    <div class='col-12'>
      <hr>
    </div>";
  }
  $html .= "
  <div class='col-8'>
    <span class='texto17'>{$descr_servico}</span><br>
    <span class='texto12-regular'>por {$cobranca_servico}</span>
  </div>
  <div class='col-4 text-end'>
    <span class='texto19 me-3'>{$valor_servico}</span>
    <a href='#' onclick='javascript:alteraSERVICO({$chave_servico})'><i class='ci ci-acao-editar me-3'></i></a><a href='#' onclick='javascript:excluiSERVICO({$chave_servico})'><i class='ci ci-acao-excluir'></i></a>
  </div>";
}
$html_cobranca_servico = "";
$html_cobranca_servico .= "<option value='' selected>Selecione</option>\n";
$html_cobranca_servico .= "<option value='DIARIA'>Diária</option>\n";
$html_cobranca_servico .= "<option value='HORA'>Hora</option>\n";
$html_cobranca_servico .= "<option value='TAREFA'>Tarefa</option>\n";
if ($_SERVER['REQUEST_METHOD'] == "GET") {
  $chave_servico = "";
  $descr_servico = "";
  $valor_servico = 0;
  /*  
    if (isset($_GET["id"])) {
      $chave_servico = $_GET["id"];
      if ($chave_servico != "") {
        abre_db();
        $strsql = "
        select
        tservico.*
        from
        tservico 
        where    
        chave_servico = :vchave_servico and
        caixa_servico = 1
        ";
        $qservico = $pdo->prepare($strsql);
        $qservico->bindParam(":vchave_servico", $chave_servico);
        $qservico->execute();
        if ($tservico = $qservico->fetch(PDO::FETCH_ASSOC)) {
          $descr_servico = $tservico["descr_servico"];
          $cobranca_servico = $tservico["cobranca_servico"];
          $valor_servico = $tservico["valor_servico"];
          $btn_confirmar = "Confirmar alteração";
          $html_cobranca_servico = "";
          $html_cobranca_servico .= "<option value='DIARIA'" . ($cobranca_servico == "DIARIA" ? " selected" : "") . ">Diária</option>\n";
          $html_cobranca_servico .= "<option value='HORA'" . ($cobranca_servico == "HORA" ? " selected" : "") . ">Hora</option>\n";
          $html_cobranca_servico .= "<option value='TAREFA'" . ($cobranca_servico == "TAREFA" ? " selected" : "") . ">Tarefa</option>\n";
          if (isset($_GET["acao"])) {
            $acao = $_GET["acao"];
            if ($acao == "e") {
              $btn_confirmar = "Confirmar exclusão";
              $btn_confirmar_tipo = "btn-danger ";
              $acao = "e";
            }
          }
        }
      }
    }
  */
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Recibo Express - Serviços</title>
  <?php include($Raiz . "include/html/header.html"); ?>
</head>
<script type="text/javascript">
  function verificaDESCR(ccampo) {
    var vok = true;
    var cdescr = ccampo.value;
    cdescr = cdescr.trim();
    if (cdescr == "") {
      const vdescr_servico_feedback = document.getElementById("descr_servico_feedback");
      vdescr_servico_feedback.textContent = "Conteúdo inválido! Informe a descrição do serviço.";
      document.getElementById('descr_servico').setAttribute('class', 'form-control is-invalid');
      document.getElementById('descr_servico_feedback').setAttribute('class', 'invalid-feedback');
      vok = false;
    }
    if (vok) {
      document.getElementById('descr_servico').setAttribute('class', 'form-control');
      document.getElementById('descr_servico_feedback').setAttribute('class', 'd-none');
    }
  }

  function verificaVALOR(ccampo) {
    var vok = true;
    var cvalor = ccampo.value;
    cvalor = cvalor.trim();
    if (cvalor == "") {
      const vvalor_servico_feedback = document.getElementById("valor_servico_feedback");
      vvalor_servico_feedback.textContent = "Conteúdo inválido! Informe o valor padrão do serviço.";
      document.getElementById('valor_servico').setAttribute('class', 'form-control is-invalid');
      document.getElementById('valor_servico_feedback').setAttribute('class', 'invalid-feedback');
      vok = false;
    }
    if (vok) {
      document.getElementById('valor_servico').setAttribute('class', 'form-control');
      document.getElementById('valor_servico_feedback').setAttribute('class', 'd-none');
    }
  }

  function novoSERVICO() {
    document.getElementById('acao').value = "";
    document.getElementById("chave_servico").value = "";
    document.getElementById("descr_servico").value = "";
    document.getElementById("valor_servico").value = 0;
    document.getElementById("cobranca_servico").value = "";
    document.getElementById('descr_servico').readOnly = false;
    document.getElementById('cobranca_servico').readOnly = false;
    document.getElementById('valor_servico').readOnly = false;
    document.getElementById("BTN_CONFIRMAR").innerHTML = "<i class='ci ci-acao-adicionar'></i> Adicionar aos serviços";
    document.getElementById('BTN_CONFIRMAR').className = 'btn btn-custom w-100';
    document.getElementById("FSETSERVICO").disabled = false;
  }

  async function alteraSERVICO(cservico) {
    if (cservico == "" || cservico == 0) {
      return;
    }
    try {
      const response = await fetch('servico_ajax.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          chave_servico: cservico,
        }),
      });
      const data = await response.json();
      console.log(data); // Loga os dados já processados do JSON
      if (data.retorno === "ok") {
        document.getElementById('acao').value = "";
        document.getElementById('chave_servico').value = data.chave_servico;
        document.getElementById('descr_servico').value = data.descr_servico;
        document.getElementById('cobranca_servico').value = data.cobranca_servico;
        document.getElementById('valor_servico').value = data.valor_servico;
        document.getElementById('descr_servico').readOnly = false;
        document.getElementById('cobranca_servico').readOnly = false;
        document.getElementById('valor_servico').readOnly = false;
        document.getElementById("BTN_CONFIRMAR").textContent = "Confirmar Alteração";
        document.getElementById('BTN_CONFIRMAR').className = 'btn btn-custom w-100';
        document.getElementById("FSETSERVICO").disabled = false;
      }
      else {
        document.getElementById("BTN_CONFIRMAR").disabled = true;
      }
    } catch (error) {
      console.error('Erro na requisição:', error);
    }
  }

  async function excluiSERVICO(cservico) {
    if (cservico == "" || cservico == 0) {
      return;
    }
    try {
      const response = await fetch('servico_ajax.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          chave_servico: cservico,
        }),
      });
      const data = await response.json();
      console.log(data); // Loga os dados já processados do JSON
      if (data.retorno === "ok") {
        document.getElementById('acao').value = "e";
        document.getElementById('chave_servico').value = data.chave_servico;
        document.getElementById('descr_servico').value = data.descr_servico;
        document.getElementById('cobranca_servico').value = data.cobranca_servico;
        document.getElementById('valor_servico').value = data.valor_servico;
        document.getElementById('descr_servico').readOnly = true;
        document.getElementById('cobranca_servico').readOnly = true;
        document.getElementById('valor_servico').readOnly = true;
        document.getElementById("BTN_CONFIRMAR").textContent = "Confirmar Exclusão";
        document.getElementById('BTN_CONFIRMAR').className = 'btn btn-danger w-100';
        document.getElementById("FSETSERVICO").disabled = false;
      }
      else {
        document.getElementById("BTN_CONFIRMAR").disabled = true;
      }
    } catch (error) {
      console.error('Erro na requisição:', error);
    }
  }

</script>

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
            <span class="texto11">CADASTROS . SERVIÇOS</span>
          </div>
          <div class="row">
            <div class="col-sm-10">
              <span style="color:#1E2933; font-weight:700; font-size: 1.75em;">Cadastro de Serviços</span><br>
              <span class="texto-corpo">Descrição, unidade de cobrança e valor padrão. Esses são os valores usados nas emissões de recibo de serviços.</span>
            </div>
            <div class="col-sm-2">
              <div class="d-flex flex-column h-100">
                <button class="btn btn-custom float-end mt-auto" id="BTN_NOVO" name="BTN_NOVO" onclick="javascript:novoSERVICO()">
                  <i class="ci ci-acao-adicionar"></i> Novo serviço
                </button>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="row mt-3">
              <div class="col-8">
                <div class="row">
                  <div class="col-12 mb-2">
                    <div class="card">
                      <div class="card-body">
                        <div class="row">
                          <?php echo $html ?>
                          <!--                          
                          <div class="col-8">
                            <span class="texto17">Faxina completa</span><br>
                            <span class="texto12-regular">por diária</span>
                          </div>
                          <div class="col-4 text-end">
                            <span class="texto19 me-3">R$ 180,00</span>
                            <a href="#">
                              <a href="index.php?id=1"><i class="ci ci-acao-editar me-3"></i></a>
                            </a>
                            <a href="#">
                              <i class="ci ci-acao-excluir"></i>
                            </a>
                          </div>
                          <div class="col-12">
                            <hr>
                          </div>
                          <div class="col-8">
                            <span class="texto17">Passadoria</span><br>
                            <span class="texto12-regular">por hora</span>
                          </div>
                          <div class="col-4 text-end">
                            <span class="texto19 me-3">R$ 40,00</span>
                            <a href="#">
                              <i class="ci ci-acao-editar me-3"></i>
                            </a>
                            <a href="#">
                              <i class="ci ci-acao-excluir"></i>
                            </a>
                          </div>
                          <div class="col-12">
                            <hr>
                          </div>
                          <div class="col-8">
                            <span class="texto17">Limpeza de vidros</span><br>
                            <span class="texto12-regular">por tarefa</span>
                          </div>
                          <div class="col-4 text-end">
                            <span class="texto19 me-3">R$ 60,00</span>
                            <a href="#">
                              <i class="ci ci-acao-editar me-3"></i>
                            </a>
                            <a href="#">
                              <i class="ci ci-acao-excluir"></i>
                            </a>
                          </div>
                          <div class="col-12">
                            <hr>
                          </div>
                          <div class="col-8">
                            <span class="texto17">Cozinha</span><br>
                            <span class="texto12-regular">por diária</span>
                          </div>
                          <div class="col-4 text-end">
                            <span class="texto19 me-3">R$ 220,00</span>
                            <a href="#">
                              <i class="ci ci-acao-editar me-3"></i>
                            </a>
                            <a href="#">
                              <i class="ci ci-acao-excluir"></i>
                            </a>
                          </div>
-->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="row">
                  <div class="col-12 mb-2">
                    <div class="card">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-12">
                            <span class="texto17">Adicionar serviço</span><br>
                            <span class="texto12-regular">Defina os serviços mais comuns e seus valores padrão.</span>
                          </div>
                          <div class="col-12">
                            <hr>
                          </div>
                          <form method="post" class="needs-validation" id="FSERVICO" name="FSERVICO" action="index.php" novalidate>
                            <fieldset id="FSETSERVICO" name="FSETSERVICO" disabled="disabled">
                              <div class="row mb-3">
                                <div class="col-12">
                                  <label for="descr_servico" class="form-label mb-1 texto-campo">Descrição</label>
                                  <input type="hidden" id="acao" name="acao" value="<?php echo $acao ?>">
                                  <input type="hidden" id="chave_servico" name="chave_servico" value="<?php echo $chave_servico ?>">
                                  <input type="text" class="form-control" id="descr_servico" name="descr_servico" value="<?php echo $descr_servico ?>" placeholder="Ex: faxina" onblur="verificaDESCR(this)" aria-describedby="descr_servico_feedback" required>
                                  <div id="descr_servico_feedback" class="invalid-feedback">
                                    Descrição inválida.
                                  </div>
                                </div>
                              </div>
                              <div class="row mb-3">
                                <div class="col-12">
                                  <label for="cobranca_servico" class="form-label mb-1 texto-campo">Unidade de cobrança</label>
                                  <select class="form-select" id="cobranca_servico" name="cobranca_servico" required>
                                    <?php echo $html_cobranca_servico ?>
                                  </select>
                                </div>
                              </div>
                              <div class="row mb-3">
                                <div class="col-12">
                                  <label for="valor_servico" class="form-label mb-1 texto-campo">Valor padrão</label>
                                  <input type="text" class="form-control" id="valor_servico" name="valor_servico" value="<?php echo $valor_servico ?>" placeholder="Ex: R$ 0,00" onblur="verificaVALOR(this)" aria-describedby="valor_servico_feedback" required>
                                  <div id="valor_servico_feedback" class="invalid-feedback">
                                    Valor inválido.
                                  </div>
                                </div>
                              </div>
                              <div class="col-12">
                                <hr>
                              </div>
                              <button type="submit" class="<?php echo $btn_confirmar_tipo ?>" id="BTN_CONFIRMAR" name="BTN_CONFIRMAR">
                                <?php echo $btn_confirmar ?>
                              </button>
                            </fieldset>
                          </form>
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

  <!-- Bootstrap 5 JS Bundle (com Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <?php include($Raiz . "include/php/rodape.php"); ?>
  <script type="text/javascript">
    // Exemplo padrão recomendado pela documentação do Bootstrap
    (function () {
      'use strict'
      var forms = document.querySelectorAll('.needs-validation')
      Array.prototype.slice.call(forms)
        .forEach(function (form) {
          form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
              event.preventDefault() // Impede o envio se estiver inválido
              event.stopPropagation() // Para a propagação do evento
            }
            form.classList.add('was-validated') // Aplica as classes visuais do Bootstrap
          }, false)
        })
    })()
  </script>
</body>

</html>
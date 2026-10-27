<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /publico/orcamento/index.php
 ***** Conteúdo: 07-orcamento
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
$chave_perfil = $_SESSION["CHAVE_PERFIL"];
$chave_orcamento = "";
$chave_cliente = "";
$nome_cliente = "";
$acao = "";
// EOF Variáveis de controle
$aftergopage = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST["chave_cliente"])) {
    $chave_cliente = $_POST["chave_cliente"];
  }
  if (isset($_POST["chave_orcamento"])) {
    $chave_orcamento = $_POST["chave_orcamento"];
  }
  if (isset($_POST["acao"])) {
    $acao = $_POST["acao"];
  }
  if ($acao == "NOVO") {
    if ($chave_orcamento == "" && $chave_cliente != "") {
      abre_db();
      $caixa_orcamento = "1";
      $strsql = "
      insert 
      into 
      torcamento 
      (chave_perfil
      ,chave_cliente
      ,caixa_orcamento
      ) 
      values 
      (:vchave_perfil
      ,:vchave_cliente    
      ,:vcaixa_orcamento
      )";
      $qorcamento = $pdo->prepare($strsql);
      $qorcamento->bindParam(":vchave_perfil", $chave_perfil);
      $qorcamento->bindParam(":vchave_cliente", $chave_cliente);
      $qorcamento->bindParam(":vcaixa_orcamento", $caixa_orcamento);
      $qorcamento->execute();
      $chave_orcamento = $pdo->lastInsertId();
      fecha_diario("torcamento", "chave_orcamento", $chave_orcamento, $abre_diario = array(), $campoexcluidos = array(""));
    }
  }
  if ($acao == "EDITA") {
    abre_db();
    $strsql = "
    select
    torcamento.chave_cliente    
    ,tcliente.nome_cliente
    from
    torcamento
    left join tcliente on torcamento.chave_cliente = tcliente.chave_cliente
    where    
    torcamento.chave_orcamento = :vchave_orcamento and
    torcamento.chave_perfil = :vchave_perfil and
    torcamento.caixa_orcamento = 1
    ";
    $qorcamento = $pdo->prepare($strsql);
    $qorcamento->bindParam(":vchave_orcamento", $chave_orcamento);
    $qorcamento->bindParam(":vchave_perfil", $chave_perfil);
    $qorcamento->execute();
    if ($torcamento = $qorcamento->fetch(PDO::FETCH_ASSOC)) {
      $chave_cliente = $torcamento["chave_cliente"];
    }
  }
}
$chave_orcamento = str_pad($chave_orcamento, 6, "0", STR_PAD_LEFT);
$qtd = 0;
$dtc = date('Y-m-d');
if ($chave_cliente != "") {
  abre_db();

  $strsql = "
  select
  count(1) as qtd 
  from
  torcamento  
  where    
  torcamento.chave_cliente = :vchave_cliente and
  torcamento.chave_perfil = :vchave_perfil and
  torcamento.caixa_orcamento = 1
  ";
  $qorcamento = $pdo->prepare($strsql);
  $qorcamento->bindParam(":vchave_cliente", $chave_cliente);
  $qorcamento->bindParam(":vchave_perfil", $chave_perfil);
  $qorcamento->execute();
  if ($torcamento = $qorcamento->fetch(PDO::FETCH_ASSOC)) {
    $qtd = $torcamento["qtd"];
  }

  $strsql = "
  select
  torcamento.dtc_orcamento
  from
  torcamento  
  where    
  torcamento.chave_cliente = :vchave_cliente and
  torcamento.chave_perfil = :vchave_perfil and
  torcamento.caixa_orcamento = 1
  order by torcamento.dtc_orcamento; torcamento.chave_orcamento
  ";
  $qorcamento = $pdo->prepare($strsql);
  $qorcamento->bindParam(":vchave_cliente", $chave_cliente);
  $qorcamento->bindParam(":vchave_perfil", $chave_perfil);
  $qorcamento->execute();
  if ($torcamento = $qorcamento->fetch(PDO::FETCH_ASSOC)) {
    $dtc = FormataData($torcamento["dtc_orcamento"], 'Y-m-d');
  }
  abre_db();
  $strsql = "
  select
  tcliente.nome_cliente 
  from
  tcliente
  where    
  tcliente.chave_cliente = :vchave_cliente and
  tcliente.chave_perfil = :vchave_perfil and
  tcliente.caixa_cliente = 1
  ";
  $qcliente = $pdo->prepare($strsql);
  $qcliente->bindParam(":vchave_cliente", $chave_cliente);
  $qcliente->bindParam(":vchave_perfil", $chave_perfil);
  $qcliente->execute();
  if ($tcliente = $qcliente->fetch(PDO::FETCH_ASSOC)) {
    $nome_cliente = $tcliente["nome_cliente"];
  }

}
$nome_cliente_compac = $nome_cliente;
if (strlen($nome_cliente) > 20) {
  $nome_cliente_compac = substr($nome_cliente_compac, 0, 20);
  $nome_cliente_compac .= "...";
}
if ($qtd == 1) {
  $resumo_qtd = $qtd . " trabalho . desde " . MesExtAno($dtc);
}
if ($qtd > 1) {
  $resumo_qtd = $qtd . " trabalhos . desde " . MesExtAno($dtc);
}
if ($qtd <= 0) {
  $resumo_qtd = "Nenhum trabalho ainda.";
}

$html_orcamentosrv = "";

abre_db();
$strsql = "
select
torcamentosrv.*
,tservico.descr_servico
from
torcamentosrv 
left join torcamento on torcamentosrv.chave_orcamento = torcamento.chave_orcamento
left join tservico on torcamentosrv.chave_servico = tservico.chave_servico
where 
torcamentosrv.chave_orcamento = :vchave_orcamento and
torcamento.chave_perfil = :vchave_perfil and
torcamentosrv.caixa_orcamentosrv = 1
";
$qorcamentosrv = $pdo->prepare($strsql);
$qorcamentosrv->bindParam(":vchave_orcamento", $chave_orcamento);
$qorcamentosrv->bindParam(":vchave_perfil", $chave_perfil);
$qorcamentosrv->execute();
while ($torcamentosrv = $qorcamentosrv->fetch(PDO::FETCH_ASSOC)) {
  $chave_orcamentosrv = $torcamentosrv["chave_orcamentosrv"];
  $descr_servico = $torcamentosrv["descr_servico"];
  $qtd_orcamentosrv = $torcamentosrv["qtd_orcamentosrv"];
  $vlu_orcamentosrv = $torcamentosrv["vlu_orcamentosrv"];
  //$cobranca_orcamento = strtolower($torcamento["cobranca_orcamento"]);
  //$valor_orcamento = "R$ " . number_format($torcamento["valor_orcamento"], 2, ',', '.');
  //
  //$html .= "
  //<tr>
  //  <td><span class='texto11'>{$chave_orcamento}</span></td>
  //  <td><span class='texto13'>{$nome_cliente}</span></td>
  //  <td><span class='texto13'>{$qtd_orcamentosrv}</span></td>
  //  <td><span class='texto13'>{$vlur_orcamentosrv}</span></td>
  //  <td class='text-end'><a href='#' onclick='javascript:alteraORCAMENTO({$chave_orcamento})'><i class='ci ci-acao-editar me-3'></i></a><a href='#' onclick='javascript:excluiORCAMENTO({$chave_orcamento})'><i class='ci ci-acao-excluir'></i></a></td>    
  //</tr>
  $html_orcamentosrv .= "
  <div class='col-8'>
    <span class='texto17'>{$descr_servico}</span><br>
    <span class='texto12-regular'>por {$descr_servico} - R$ {$vlu_orcamentosrv}</span>
  </div>
  <div class='col-4 d-flex justify-content-end'>
    <div class='row g-3 align-items-center'>
      <div class='col-auto'>
        <input type='text' class='form-control' id='valorserv1' name='valorserv1' value='1' style='max-width:50px'>
      </div>
      <div class='col-auto'>
        <span id='valorserv1' class='texto16'>
          R$ $vlu_orcamentosrv
        </span>
      </div>
    </div>
  </div>
  <div class='col-12'>
    <hr>
  </div>  
  ";
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Recibo Express - Orçamentos</title>
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
<style>
  :root {
    --step-active-color: #1a3b5c;
    /* Cor do estado ativo/concluído */
    --step-default-color: #bcc8d4;
    /* Cor dos passos restantes */
    --step-text-color: #93a3b1;
    /* Cor do texto pendente */
  }

  /* Estilo do círculo */
  .step-counter {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 2px solid var(--step-default-color);
    color: var(--step-default-color);
    font-size: 0.9rem;
    background-color: transparent;
    flex-shrink: 0;
  }

  .step-name {
    color: var(--step-text-color);
    font-size: 0.95rem;
    white-space: nowrap;
  }

  /* Linha de conexão entre passos */
  .stepper-line {
    height: 2px;
    background-color: var(--step-default-color);
  }

  /* Estado Concluído / Ativo */
  .stepper-item.completed .step-counter {
    background-color: var(--step-active-color);
    border-color: var(--step-active-color);
    color: #ffffff;
  }

  .stepper-item.completed .step-name {
    color: var(--step-active-color);
  }

  .stepper-line.completed {
    background-color: var(--step-active-color);
  }
</style>

<script type="text/javascript">

  function Modal_BrowseServicos() {
    var vurl = '';
    var vpagina = '1';
    var vbtn_redef = 'btn btn-sm btn-warning';
    //var vpesq = document.getElementById('nome_cliente').value.trim();
    var vpesq = "";
    var n = vpesq.indexOf(' ');
    var vurl = '';
    if (n > 0) {
      vpesq = vpesq.substring(0, n);
      vpesq = vpesq.trim();
    }
    if (vpesq != '') {
      vurl = vurl + 'PESQ=' + vpesq;
    }
    if (vurl != '') {
      vurl = vurl + '&';
    }
    vurl = vurl + 'PG=1';
    $.get('modal_servicos.php?' + vurl, function (data) {
      $('#MODAL_BROWSESERVICOS').find('.modal-body').html(data);
      $('#MODAL_BROWSESERVICOS').one('hidden.bs.modal', function () {
        $(document).removeData('bs.modal');
      })
    })
  };

  function Modal_BrowseServicos_Reshow(npag) {
    var vurl = '';
    var vpagina = '';
    var vbtn_redef = 'btn btn-sm btn-warning';
    //	var vchave_repr = document.getElementById('CHAVE_REPR').value;
    var vmeussrvpesq = document.getElementById('MEUSSRVPESQ').value;
    var vreset = '';
    if (String(npag) == 'undefined') {
      npag = '1';
    }
    if (String(npag) == 'RESET') {
      vreset = npag;
      npag = '1';
      vmeussrvpesq = '';
      document.getElementById('MEUSSRVPESQ').value = vmeussrvpesq;
    }
    vpagina = npag;
    //	if (vchave_repr != '' && vchave_repr != 'undefined') {
    //		vurl = 'ID=' + vchave_repr;
    //	}
    if (vpagina != '' && vpagina != 'undefined') {
      if (vurl != '') {
        vurl = vurl + '&';
      }
      vurl = vurl + 'PG=' + vpagina;
    }
    if (vmeussrvpesq != '' && vmeussrvpesq != 'undefined') {
      if (vurl != '') {
        vurl = vurl + '&';
      }
      vurl = vurl + 'PESQ=' + vmeussrvpesq;
    }
    if (vreset == 'RESET') {
      if (vurl != '') {
        vurl = vurl + '&';
      }
      vurl = vurl + 'RESET=SIM';
    }
    if (document.getElementById('MEUSSRVPESQ').value == '') {
      vbtn_redef = 'btn btn-sm btn-warning d-none';
    }
    document.getElementById('BTN_FILTRO_REDEF').setAttribute('class', vbtn_redef);
    $.get('modal_servicos.php?' + vurl, function (data) {
      $('#MODAL_BROWSESERVICOS').find('.modal-body').html(data);
    })
  };

  function Modal_BrowseServicos_Retorno() {
    if ($('#BOX_BROWSESERVICOS').val() != '') {
      $('#CHAVE_FOR').val($('#BOX_BROWSESERVICOS').val());
    }
    //  $('#main-result').val($('#modal-result').val());
    $('#MODAL_BROWSESERVICOS').modal('hide');
    if ($('#BOX_BROWSESERVICOS').val() == '') {
      browseservicos_novo();
      document.refresh;
    }
    else {
      Pesquisa_Cliente();
    }
  }

  function avaliatecla_pesquisaservico(e) {
    if (e.keyCode == 13) {
      Modal_BrowseServicos_Reshow('');
    }
  }

  function Pesquisa_Servico() {
    //const form = document.getElementById("FORCAMENTOSRV_EDITA");
    //form.submit();
    enviarFormulario();
  }

  async function enviarFormulario() {
    const formData = new FormData();
    const vchave_servico = document.getElementById("chave_servico").value;
    const vchave_orcamento = document.getElementById("chave_orcamento").value;    
    formData.append('chave_servico', vchave_servico);
    formData.append('chave_orcamento', vchave_orcamento);
    // formData.append('arquivo', inputElement.files[0]); // Para arquivos
    try {
      const resposta = await fetch('orcamentosrv_edita.php', {
        method: 'POST',
        body: formData // O browser ajusta os headers automaticamente
      });      
      const resultado = await resposta.json();
      console.log('Sucesso:', resultado);
    } catch (erro) {
      console.log('Erro:', erro);
    }
  }

</script>

<body>

  <!-- Modal Browse Serviços -->
  <div class="modal fade" id="MODAL_BROWSESERVICOS" tabindex="-1" role="dialog" aria-labelledby="MODAL_BROWSESERVICOS_LABEL">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Serviços</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Voltar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- EOF Modal Browse Serviços -->

  <div class="container">
    <div class="row">
      <?php include($Raiz . "include/php/menu_lateral.php"); ?>

      <!-- CONTEÚDO PRINCIPAL DA PÁGINA -->
      <main class="col-12 col-md-9 col-lg-10">
        <!-- Cabeçalho da Página -->
        <?php include($Raiz . "include/php/menu_superior.php"); ?>
        <div class="main-content">
          <div class="col-12 mt-3">
            <span class="texto11">ORÇAMENTOS . Nº <?php echo $chave_orcamento; ?></span>
          </div>
          <div class="row">
            <div class="col-sm-10">
              <span style="color:#1E2933; font-weight:700; font-size: 1.75em;">Orçamento para <?php echo $nome_cliente_compac ?></span><br>
              <span class="texto-corpo">Envie, aguarde a aprovação, realize o serviço e gere o recibo.</span>
            </div>
            <div class="col-sm-2">
              <div class="d-flex flex-column h-100">
                <button class="btn btn-custom float-end mt-auto">
                  Criar Orçamento
                </button>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="stepper-wrapper d-flex justify-content-between align-items-center my-4">
                <!-- Passo 1: Concluído -->
                <div class="stepper-item completed d-flex align-items-center">
                  <div class="step-counter d-flex align-items-center justify-content-center fw-bold">
                    <i class="bi bi-check2"></i>
                  </div>
                  <span class="step-name fw-bold ms-2">Criado</span>
                </div>

                <div class="stepper-line completed flex-grow-1 mx-3"></div>

                <!-- Passo 2: Pendente -->
                <div class="stepper-item d-flex align-items-center">
                  <div class="step-counter d-flex align-items-center justify-content-center fw-bold">2</div>
                  <span class="step-name ms-2">Enviado</span>
                </div>

                <div class="stepper-line flex-grow-1 mx-3"></div>

                <!-- Passo 3: Pendente -->
                <div class="stepper-item d-flex align-items-center">
                  <div class="step-counter d-flex align-items-center justify-content-center fw-bold">3</div>
                  <span class="step-name ms-2">Aprovado</span>
                </div>

                <div class="stepper-line flex-grow-1 mx-3"></div>

                <!-- Passo 4: Pendente -->
                <div class="stepper-item d-flex align-items-center">
                  <div class="step-counter d-flex align-items-center justify-content-center fw-bold">4</div>
                  <span class="step-name ms-2">Realizado</span>
                </div>

                <div class="stepper-line flex-grow-1 mx-3"></div>

                <!-- Passo 5: Pendente -->
                <div class="stepper-item d-flex align-items-center">
                  <div class="step-counter d-flex align-items-center justify-content-center fw-bold">5</div>
                  <span class="step-name ms-2">Recibo</span>
                </div>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-8">
              <div class="row">
                <div class="col-12 mb-2">
                  <div class="card">
                    <div class="card-body">
                      <form method="post" id="FORCAMENTOSRV_EDITA" name="FORCAMENTOSRV_EDITA" action="orcamentosrv_edita.php">
                        <input type="hidden" id="chave_orcamento" name="chave_orcamento" value="<?php echo $chave_orcamento ?>">
                        <input type="hidden" id="chave_servico" name="chave_servico" value="">
                        <div class="row">
                          <?php echo $html_orcamentosrv ?>
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
                          <div class="col-8">
                            <span class="texto17">Limpeza de vidros</span><br>
                            <span class="texto12-regular">por tarefa - R$ 60,00</span>
                          </div>
                          <div class="col-4 d-flex justify-content-end">
                            <div class="row g-3 align-items-center">
                              <div class="col-auto">
                                <input type="text" class="form-control" id="valorserv2" name="valorserv2" value="2" style="max-width:50px">
                              </div>
                              <div class="col-auto">
                                <span id="valorserv1" class="texto16">
                                  R$ 120,00
                                </span>
                              </div>
                            </div>
                          </div>
                          <div class="col-12">
                            <hr>
                          </div>
                          <div class="col-12 mb-3">
                            <button type="button" class="btn btn-outline-secondary" id="BTN_ADSERVICO" name="BTN_ADSERVICO" data-bs-toggle='modal' data-bs-target='#MODAL_BROWSESERVICOS' onclick="javascript:Modal_BrowseServicos();">
                              + Adicionar serviço
                            </button>
                          </div>
                          <div class="col-12 mt-3">
                            <div class="row">
                              <div class="col-6">
                                Subtotal
                              </div>
                              <div class="col-6 text-end">
                                R$ 300,00
                              </div>
                            </div>
                          </div>
                          <div class="col-12">
                            <hr>
                          </div>
                          <div class="col-12">
                            <div class="row">
                              <div class="col-6">
                                TOTAL
                              </div>
                              <div class="col-6 text-end">
                                <span class="texto28">R$ 300,00</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
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
                        <div class="col-12 mt-2 mb-2">
                          <span class="texto12">CLIENTE</span><br>
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-2 mb-2">
                          <div class="p-2 rounded shadow-sm text-center" style="background-color:#E7EEFE; min-width: 42px; min-height: 42px; max-width: 42px; max-height: 42px;">
                            <span class="texto16"><?php echo mb_strtoupper(substr($nome_cliente_compac, 0, 1)) ?></span>
                          </div>
                          <div>
                            <span class="texto15"><?php echo $nome_cliente_compac ?></span><br>
                            <!-- n trabalhos desde mar 2026 -->
                            <span class="texto12-regular"><?php echo $resumo_qtd; ?></span>
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
  <?php include($Raiz . "include/php/rodape.php"); ?>
</body>

</html>
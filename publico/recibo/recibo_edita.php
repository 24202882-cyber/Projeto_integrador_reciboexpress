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
VerificaSessao();
// Variáveis de controle
$ok = true;
$html = "";
$html_tit = "";
$html_msg = "";
$html_lnk = "";
$html_btn = "";
$chave_perfil = $_SESSION["CHAVE_PERFIL"];
$chave_recibo = "";
$dte_recibo = "";
$urlpdf_recibo = "";
$chave_cliente = "";
$nome_cliente = "";
$cpf_cliente = "";
$cep_cliente = "";
$situacao_recibo = "";
$valor_recibo = 0;
$tel_cliente = "";
$acao = "";
// EOF Variáveis de controle
$aftergopage = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST["chave_cliente"])) {
    $chave_cliente = $_POST["chave_cliente"];
  }
  if (isset($_POST["chave_recibo"])) {
    $chave_recibo = $_POST["chave_recibo"];
  }
  if (isset($_POST["acao"])) {
    $acao = $_POST["acao"];
  }
  if ($chave_recibo != "") {
    $_SESSION["CHAVE_RECIBO"] = $chave_recibo;
  }
  if ($acao == "NOVO") {
    if ($chave_recibo == "" && $chave_cliente != "") {
      abre_db();
      $caixa_recibo = "1";
      $strsql = "
      insert 
      into 
      trecibo 
      (chave_perfil
      ,chave_cliente
      ,caixa_recibo
      ) 
      values 
      (:vchave_perfil
      ,:vchave_cliente    
      ,:vcaixa_recibo
      )";
      $qrecibo = $pdo->prepare($strsql);
      $qrecibo->bindParam(":vchave_perfil", $chave_perfil);
      $qrecibo->bindParam(":vchave_cliente", $chave_cliente);
      $qrecibo->bindParam(":vcaixa_recibo", $caixa_recibo);
      $qrecibo->execute();
      $chave_recibo = $pdo->lastInsertId();
      fecha_diario("trecibo", "chave_recibo", $chave_recibo, $abre_diario = array(), $campoexcluidos = array(""));
      $_SESSION["CHAVE_RECIBO"] = $chave_recibo;
    }
  }
  if ($acao == "EDITA") {
    abre_db();
    $strsql = "
    select
    trecibo.chave_cliente
    ,trecibo.valor_recibo
    ,trecibo.situacao_recibo
    ,trecibo.dte_recibo
    ,trecibo.urlpdf_recibo
    ,tcliente.nome_cliente
    ,tcliente.tel_cliente
    from
    trecibo
    left join tcliente on trecibo.chave_cliente = tcliente.chave_cliente
    where    
    trecibo.chave_recibo = :vchave_recibo and
    trecibo.chave_perfil = :vchave_perfil and
    trecibo.caixa_recibo = 1
    ";
    $qrecibo = $pdo->prepare($strsql);
    $qrecibo->bindParam(":vchave_recibo", $chave_recibo);
    $qrecibo->bindParam(":vchave_perfil", $chave_perfil);
    $qrecibo->execute();
    if ($trecibo = $qrecibo->fetch(PDO::FETCH_ASSOC)) {
      $chave_cliente = $trecibo["chave_cliente"];
      $situacao_recibo = $trecibo["situacao_recibo"];
      $valor_recibo = $trecibo["valor_recibo"];
      $nome_cliente = $trecibo["nome_cliente"];
      $dte_recibo = $trecibo["dte_recibo"];
      $urlpdf_recibo = $trecibo["urlpdf_recibo"];
      $tel_cliente = $trecibo["tel_cliente"];
    }
  }
}
else {
  if (isset($_SESSION["CHAVE_RECIBO"])) {
    $chave_recibo = $_SESSION["CHAVE_RECIBO"];
    abre_db();
    $strsql = "
    select
    trecibo.chave_cliente
    ,trecibo.valor_recibo
    ,trecibo.situacao_recibo
    ,trecibo.dte_recibo
    ,trecibo.urlpdf_recibo
    ,tcliente.nome_cliente
    ,tcliente.tel_cliente
    from
    trecibo
    left join tcliente on trecibo.chave_cliente = tcliente.chave_cliente
    where    
    trecibo.chave_recibo = :vchave_recibo and
    trecibo.chave_perfil = :vchave_perfil and
    trecibo.caixa_recibo = 1
    ";
    $qrecibo = $pdo->prepare($strsql);
    $qrecibo->bindParam(":vchave_recibo", $chave_recibo);
    $qrecibo->bindParam(":vchave_perfil", $chave_perfil);
    $qrecibo->execute();
    if ($trecibo = $qrecibo->fetch(PDO::FETCH_ASSOC)) {
      $chave_cliente = $trecibo["chave_cliente"];
      $situacao_recibo = $trecibo["situacao_recibo"];
      $valor_recibo = $trecibo["valor_recibo"];
      $nome_cliente = $trecibo["nome_cliente"];
      $dte_recibo = $trecibo["dte_recibo"];
      $urlpdf_recibo = $trecibo["urlpdf_recibo"];
      $tel_cliente = $trecibo["tel_cliente"];
    }
  }
}
$chave_recibo_ext = str_pad($chave_recibo, 6, "0", STR_PAD_LEFT);
$qtd = 0;
$dtc = date('Y-m-d');
if ($chave_cliente != "") {
  abre_db();
  $strsql = "
  select
  count(1) as qtd 
  from
  trecibo  
  where    
  trecibo.chave_cliente = :vchave_cliente and
  trecibo.chave_perfil = :vchave_perfil and
  trecibo.caixa_recibo = 1
  ";
  $qrecibo = $pdo->prepare($strsql);
  $qrecibo->bindParam(":vchave_cliente", $chave_cliente);
  $qrecibo->bindParam(":vchave_perfil", $chave_perfil);
  $qrecibo->execute();
  if ($trecibo = $qrecibo->fetch(PDO::FETCH_ASSOC)) {
    $qtd = $trecibo["qtd"];
  }

  $strsql = "
  select
  trecibo.dtc_recibo
  from
  trecibo  
  where    
  trecibo.chave_cliente = :vchave_cliente and
  trecibo.chave_perfil = :vchave_perfil and
  trecibo.caixa_recibo = 1
  order by trecibo.dtc_recibo; trecibo.chave_recibo
  ";
  $qrecibo = $pdo->prepare($strsql);
  $qrecibo->bindParam(":vchave_cliente", $chave_cliente);
  $qrecibo->bindParam(":vchave_perfil", $chave_perfil);
  $qrecibo->execute();
  if ($trecibo = $qrecibo->fetch(PDO::FETCH_ASSOC)) {
    $dtc = FormataData($trecibo["dtc_recibo"], 'Y-m-d');
  }
  abre_db();
  $strsql = "
  select
  tcliente.nome_cliente 
  ,tcliente.cpf_cliente 
  ,tcliente.cep_cliente 
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
    $cpf_cliente = $tcliente["cpf_cliente"];
    $cep_cliente = $tcliente["cep_cliente"];
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
$html_recibosrv = "";
$html_recibosrv_compac = "";
$contador = 0;
$subtotal = 0;
$total = 0;
abre_db();
$strsql = "
select
trecibosrv.*
,tservico.descr_servico
from
trecibosrv 
left join trecibo on trecibosrv.chave_recibo = trecibo.chave_recibo
left join tservico on trecibosrv.chave_servico = tservico.chave_servico
where 
trecibosrv.chave_recibo = :vchave_recibo and
trecibo.chave_perfil = :vchave_perfil and
trecibosrv.caixa_recibosrv = 1
";
$qrecibosrv = $pdo->prepare($strsql);
$qrecibosrv->bindParam(":vchave_recibo", $chave_recibo);
$qrecibosrv->bindParam(":vchave_perfil", $chave_perfil);
$qrecibosrv->execute();
while ($trecibosrv = $qrecibosrv->fetch(PDO::FETCH_ASSOC)) {
  $chave_recibosrv = $trecibosrv["chave_recibosrv"];
  $chave_servico = $trecibosrv["chave_servico"];
  $descr_servico = $trecibosrv["descr_servico"];
  $qtd_recibosrv = $trecibosrv["qtd_recibosrv"];
  $vlu_recibosrv = $trecibosrv["vlu_recibosrv"];  
  $subtotal += (intval($qtd_recibosrv) * floatval($vlu_recibosrv));
  $total += (intval($qtd_recibosrv) * floatval($vlu_recibosrv));
  //$cobranca_recibo = strtolower($trecibo["cobranca_recibo"]);
  //$valor_recibo = "R$ " . number_format($trecibo["valor_recibo"], 2, ',', '.');
  //
  //$html .= "
  //<tr>
  //  <td><span class='texto11'>{$chave_recibo}</span></td>
  //  <td><span class='texto13'>{$nome_cliente}</span></td>
  //  <td><span class='texto13'>{$qtd_recibosrv}</span></td>
  //  <td><span class='texto13'>{$vlur_recibosrv}</span></td>
  //  <td class='text-end'><a href='#' onclick='javascript:alteraRECIBO({$chave_recibo})'><i class='ci ci-acao-editar me-3'></i></a><a href='#' onclick='javascript:excluiRECIBO({$chave_recibo})'><i class='ci ci-acao-excluir'></i></a></td>    
  //</tr>
  $contador += 1;
  $html_recibosrv .= "
  <div class='col-8'>
    <span class='texto17'>{$descr_servico}</span><br>
    <span class='texto12-regular'>por {$descr_servico} - " . Float2Moeda(floatval($trecibosrv["vlu_recibosrv"]), 2) . "</span>
  </div>
  <div class='col-4 d-flex justify-content-end'>
    <div class='row g-3 align-items-center'>
      <div class='col-auto'>
        <input type='hidden' id='servico{$contador}' name='servico{$contador}' value='{$chave_servico}'>
        <input type='hidden' id='chave{$contador}' name='chave{$contador}' value='{$chave_recibosrv}'>
        <input type='text' class='form-control' style='max-width:50px' id='qtd{$contador}' name='qtd{$contador}' value='{$qtd_recibosrv}' onblur='javascript:alteraSERVICO(this);'>
      </div>
      <div class='col-auto'>
        <span id='valorserv1' class='texto16'>
          " . Float2Moeda(floatval($trecibosrv["vlu_recibosrv"]), 2) . "
        </span>
      </div>
    </div>
  </div>
  <div class='col-12'>
    <hr>
  </div>  
  ";

  $html_recibosrv_compac .= "
  <div class='col-6'>
    <span class='texto13'>{$descr_servico}</span>
  </div>
  <div class='col-6 text-end'>
    <span class='texto13'>" . strtoupper(DiaMesExtAno($dtc)) . "</span>
  </div>
  ";
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
      Pesquisa_Servico();
    }
  }

  function avaliatecla_pesquisaservico(e) {
    if (e.keyCode == 13) {
      Modal_BrowseServicos_Reshow('');
    }
  }

  function Pesquisa_Servico() {
    const vchave_recibo = document.getElementById("chave_recibo").value;
    const vchave_servico = document.getElementById("chave_servico").value;
    if (vchave_recibo != "" && vchave_servico != "") {
      document.FRECIBOSRV_EDITA.submit();
    }
  }

  function alteraSERVICO(ccampo) {
    const numcampo = ccampo.id.slice(3);
    document.getElementById("chave_servico").value = document.getElementById("servico" + numcampo).value;
    document.getElementById("chave_recibosrv").value = document.getElementById("chave" + numcampo).value;
    document.getElementById("qtd_recibosrv").value = document.getElementById("qtd" + numcampo).value;
    document.getElementById("acao").value = "EDITA";
    document.FRECIBOSRV_EDITA.submit();
  }

  function gerarRECIBO() {
    document.FRECIBOSRV_EDITA.action="recibo_email.php";
    document.FRECIBOSRV_EDITA.submit();
  } 
  
  function reciboPAGTO() {
    const form = document.createElement('form');
    const vchave_recibo = document.getElementById("chave_recibo").value;
    form.method = 'POST';
    form.action = 'recibo_status.php';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'chave_recibo';
    input.value = vchave_recibo;
    form.appendChild(input);  

    const input2 = document.createElement('input');
    input2.type = 'hidden';
    input2.name = 'situacao_recibo';
    input2.value = 'PAGO';
    form.appendChild(input2);  
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
  }
  
function reciboWHATSAPP() {  
  const numeroTelefone = document.getElementById("tel_cliente").value;    
  let inputLink = 'https://reciboexpress.guiadomestico.com.br/publico/pdfs/' + document.getElementById("urlpdf_recibo").value;
  const mensagem = `Olá, Segue o link para você visualizar seu recibo:\n\n${inputLink}`;    
  const btn_whatsapp = document.getElementById("BTN_WHATSAPP");    
  const urlWhatsApp = `https://api.whatsapp.com/send?phone=${numeroTelefone}&text=${encodeURIComponent(mensagem)}`;
  window.open(urlWhatsApp, '_blank');
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
          <?php if ($situacao_recibo == 'CRIADO') { ?>
          <div class="col-12 mt-3">
            <span class="texto11">RECIBOS . EMISSÃO</span>
          </div>
          <div class="row">
            <div class="col-10">
              <span style="color:#1E2933; font-weight:700; font-size: 1.75em;">Emitir Recibo</span><br>
              <span class="texto-corpo">Confira à esquerda; o documento se atualiza à direita antes de gerar o PDF.</span>
            </div>
            <div class="col-sm-2">
              <div class="d-flex flex-column h-100">
                <button type='button' class='btn btn-custom float-end mt-auto' onclick='javascript:reciboPAGTO()'>
                  Confirmar Pagto
                </button>
              </div>
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
                          <label for="nome_cliente" class="form-label mb-1 texto-campo">CLIENTE</label>
                          <input type="text" class="form-control" id="nome_cliente" name="nome_cliente" placeholder="Nome completo" value="<?php echo $nome_cliente ?>">
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-6">
                          <label for="cpf_cliente" class="form-label mb-1 texto-campo">CPF DO PAGADOR</label>
                          <input type="text" class="form-control" id="cpf_cliente" name="cpf_cliente" placeholder="CPF" value="<?php echo $cpf_cliente ?>">
                        </div>
                        <div class="col-6">
                          <label for="cep_cliente" class="form-label mb-1 texto-campo">CEP</label>
                          <input type="text" class="form-control" id="cep_cliente" name="cep_cliente" placeholder="CEP" value="<?php echo $cep_cliente ?>">
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12 mb-2">
                  <div class="card rounded-3 border-1 p-4" style="background-color: #FFF; border-color: #CCC;">
                    <form method="post" id="FRECIBOSRV_EDITA" name="FRECIBOSRV_EDITA" action="recibosrv_edita.php">
                      <input type="hidden" id="chave_recibo" name="chave_recibo" value="<?php echo $chave_recibo ?>">
                      <input type="hidden" id="chave_recibosrv" name="chave_recibosrv" value="">
                      <input type="hidden" id="chave_servico" name="chave_servico" value="">
                      <input type="hidden" id="qtd_recibosrv" name="qtd_recibosrv" value="">
                      <input type="hidden" id="acao" name="acao" value="NOVO">
                      <input type="hidden" id="urlpdf_recibo" name="urlpdf_recibo" value="<?php echo 'https://reciboexpress.guiadomestico.com.br/publico/pdfs/' . $urlpdf_recibo ?>">
                      <input type="hidden" id="tel_cliente" name="tel_cliente" value="<?php echo $tel_cliente ?>">
                      <span class="texto12 mb-3">SERVIÇOS & DATA</span>
                      <div class="row">
                        <?php echo $html_recibosrv ?>
<!--                        
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
-->                        
                        <div class="col-12 mb-3">
                          <button type="button" class="btn btn-outline-custom" id="BTN_ADSERVICO" name="BTN_ADSERVICO" data-bs-toggle='modal' data-bs-target='#MODAL_BROWSESERVICOS' onclick="javascript:Modal_BrowseServicos();">
                            + Adicionar serviço
                          </button>
                        </div>
                        <div class="col-12 mt-3">
                          <div class="row">
                            <div class="col-6">
                              Subtotal
                            </div>
                            <div class="col-6 text-end">
                              <?php echo Float2Moeda(floatval($subtotal), 2); ?>
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
                              <span class="texto28"><?php echo Float2Moeda(floatval($total), 2); ?></span>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 mt-3 mb-3">
                          <i class="ci ci-menu-armazenamento"></i>
                          <span class="texto-regular"> O PDF será gerado e salvo na nuvem do seu perfil ao emitir.</span>
                        </div>
                        <div class="col-12 mt-2">
                          <button class="btn btn-custom" style="width: 100%" onclick="javascript:gerarRECIBO();">
                            <i class="ci ci-menu-recibos"></i> Gerar recibo e enviar para aprovação do cliente
                          </button>
                        </div>
                      </div>
                    </form>
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
                          <span class="texto18" style="color:#E9784A"><?php echo $chave_recibo_ext ?></span>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <span class="texto9">RECEBI DE</span>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <span class="texto21"><?php echo $nome_cliente_compac ?></span><br>
                          <span class="texto9">A IMPORTÂNCIA DE</span>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <span class="texto19" style="color: #697783; vertical-align: super;">R$</span><span class="texto40"><?php echo number_format(floatval($total), 2, ',', '.'); ?></span>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-6">
                          <span class="texto9">REFERENTE A</span><br>                          
                        </div>
                        <div class="col-6 text-end">
                          <span class="texto9">REALIZADA EM</span><br>                          
                        </div>
                        <?php echo $html_recibosrv_compac ?>
<!--
                        <div class="col-6">
                          <span class="texto13">Faxina residencial</span>
                        </div>
                        <div class="col-6 text-end">
                          <span class="texto13">17 SET 2026</span>
                        </div>
                        <div class="col-6">
                          <span class="texto13">Faxina residencial</span>
                        </div>
                        <div class="col-6 text-end">
                          <span class="texto13">17 SET 2026</span>
                        </div>
-->
                      </div>

                      <div class="row">
                        <div class="col-12">
                          <hr>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <span class="texto15"><?php echo NomeUserComprac(); ?></span><br>
                          <span class="texto11-regular"><?php echo CPFUserComprac(); ?></span>
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
          <?php
          }
          else { ?>
          <form method="post" id="FRECIBOSRV_EDITA" name="FRECIBOSRV_EDITA" action="recibo_edita.php">
            <input type="hidden" id="chave_recibo" name="chave_recibo" value="<?php echo $chave_recibo ?>">
            <input type="hidden" id="chave_recibosrv" name="chave_recibosrv" value="">
            <input type="hidden" id="chave_servico" name="chave_servico" value="">
            <input type="hidden" id="qtd_recibosrv" name="qtd_recibosrv" value="">
            <input type="hidden" id="acao" name="acao" value="NOVO">
            <input type="hidden" id="urlpdf_recibo" name="urlpdf_recibo" value="<?php echo 'https://reciboexpress.guiadomestico.com.br/publico/pdfs/' . $urlpdf_recibo ?>">
            <input type="hidden" id="tel_cliente" name="tel_cliente" value="<?php echo $tel_cliente ?>">
            <div class="col-12 mt-3 text-center">
              <img src="/identidade/logo-reciboexpress.svg" style="min-height: 48px; min-width: 48px;">            
            </div>
            <div class="col-12 mt-3 text-center">            
              <span class="texto11">RECIBO PRONTO . SALVO NA NÚVEM</span>
            </div>
            <div class="col-12 mt-3 text-center">            
              <span style='font-size: 48px; font-weight: 700; color: #000'>R$ <?php echo number_format(floatval($valor_recibo), 2, ',', '.') ?></span>
            </div>
            <div class="col-12 text-center">            
              <span class="texto17-regular"><?php echo $nome_cliente ?></span>
            </div>
            <div class="col-12 text-center">            
              <span class="texto13">Nº <?php echo $chave_recibo_ext . " . " . strtoupper(DiaMesExtAno($dte_recibo)) . " " . HoraMin($dte_recibo); ?></span>
            </div>
            <div class="col-12 mt-3 text-center">            
              <div class="d-flex justify-content-center align-items-center main-content gap-3 p-3">              
                <button type="button" class="btn btn-custom-lima" id="BTN_WHATSAPP" name="BTN_WHATSAPP" onclick="javascript:reciboWHATSAPP();">Enviar pelo WhatsApp</button>
                <button type="button" class="btn btn-light" id="BTN_BAIXAR_PDF" name="BTN_BAIXAR_PDF"><i class="ci ci-acao-download"></i> Baixar PDF</button>
                <button type="button" class="btn btn-light" id="BTN_COPIAR_LINK" name="BTN_COPIAR_LINK"><i class="ci ci-acao-link"></i> Copiar Link</button>
              </div>
              <div class="d-flex justify-content-center align-items-center main-content gap-3 p-3">                            
                <button type="button" class="btn btn-sm btn-light" id="BTN_VISAOGERAL" name="BTN_VISAOGERAL"><i class="ci ci-acao-visao-geral"></i> Ir para Visão Geral</button>
              </div>
            </div>
          </form>
  <script>
    const btn_copiar_link = document.getElementById('BTN_COPIAR_LINK');
    const urlpdf_recibo = document.getElementById('urlpdf_recibo');
    btn_copiar_link.addEventListener('click', async () => {
      try {
        // Copia o valor do input para a área de transferência
        await navigator.clipboard.writeText(urlpdf_recibo.value);
        // Feedback visual temporário para o usuário
        btn_copiar_link.innerHTML = 'Copiado!';
        setTimeout(() => {
          btn_copiar_link.innerHTML = "<span class='text-nowrap'>Copiar Link</span>";
        }, 2000);
      }
      catch (err) {
        // Fallback para navegadores antigos se o Clipboard API falhar
        urlpdf_recibo.select();
        document.execCommand('copy');
        alert('Link copiado com sucesso!');
      }
    });
  </script>  

          <?php } ?>
      </main>
    </div>
  </div>
  <!-- FIM DA ÁREA DE CONTEÚDO -->
  <?php include($Raiz . "include/php/rodape.php"); ?>
</body>

</html>
<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /publico/orcamento/orcamento_edita.php
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
include($Raiz . "include/php/header.php");
include($Raiz . "include/php/funcoes.php");
include($Raiz . "include/php/key.php");
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
$email_cliente = "";
$tel_cliente = "";
$situacao_orcamento = "CRIADO";
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
  if ($chave_orcamento != "") {
    $_SESSION["CHAVE_ORCAMENTO"] = $chave_orcamento;
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
      $_SESSION["CHAVE_ORCAMENTO"] = $chave_orcamento;
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
} else {
  if (isset($_SESSION["CHAVE_ORCAMENTO"])) {
    $chave_orcamento = $_SESSION["CHAVE_ORCAMENTO"];
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
$chave_orcamento_ext = str_pad($chave_orcamento, 6, "0", STR_PAD_LEFT);
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
  ,tcliente.email_cliente
  ,tcliente.tel_cliente
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
    $email_cliente = $tcliente["email_cliente"];
    $tel_cliente = $tcliente["tel_cliente"];
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

$strsql = "
select
torcamento.situacao_orcamento
from
torcamento  
where    
torcamento.chave_orcamento = :vchave_orcamento and
torcamento.chave_perfil = :vchave_perfil and
torcamento.caixa_orcamento = 1
order by torcamento.chave_orcamento
";
$qorcamento = $pdo->prepare($strsql);
$qorcamento->bindParam(":vchave_orcamento", $chave_orcamento);
$qorcamento->bindParam(":vchave_perfil", $chave_perfil);
$qorcamento->execute();
if ($torcamento = $qorcamento->fetch(PDO::FETCH_ASSOC)) {
  $situacao_orcamento = $torcamento["situacao_orcamento"];
}

$passo = "1";
$passo1 = "1";
$passo2 = "2";
$passo3 = "3";
$passo4 = "4";
$passo5 = "5";
$html_botao_passo = "";

// passo 1
if ($situacao_orcamento == "" || $situacao_orcamento == "CRIADO") {
  $passo1 = "<i class='bi bi-check2'></i>";
  $passo2 = "2";
  $passo3 = "3";
  $passo4 = "4";
  $passo5 = "5";
  $passo = "1";
}
// passo 2
if ($situacao_orcamento == "ENVIADO") {
  $passo1 = "<i class='bi bi-check2'></i>";
  $passo2 = "<i class='bi bi-check2'></i>";
  $passo3 = "3";
  $passo4 = "4";
  $passo5 = "5";
  $passo = "2";
}
// passo 3
if ($situacao_orcamento == "APROVADO") {
  $passo1 = "<i class='bi bi-check2'></i>";
  $passo2 = "<i class='bi bi-check2'></i>";
  $passo3 = "<i class='bi bi-check2'></i>";
  $passo4 = "4";
  $passo5 = "5";
  $passo = "3";
}
// passo 4
if ($situacao_orcamento == "REALIZADO") {
  $passo1 = "<i class='bi bi-check2'></i>";
  $passo2 = "<i class='bi bi-check2'></i>";
  $passo3 = "<i class='bi bi-check2'></i>";
  $passo4 = "<i class='bi bi-check2'></i>";
  $passo5 = "5";
  $passo = "4";
}

// passo 4
if ($situacao_orcamento == "RECIBO") {
  $passo1 = "<i class='bi bi-check2'></i>";
  $passo2 = "<i class='bi bi-check2'></i>";
  $passo3 = "<i class='bi bi-check2'></i>";
  $passo4 = "<i class='bi bi-check2'></i>";
  $passo5 = "<i class='bi bi-check2'></i>";
  $passo = "5";
}

$urlaprova_orcamento = "";
if ($passo == "1") {
  $urlaprova_orcamento = "id1={$chave_perfil}&id2={$chave_orcamento}&id3={$situacao_orcamento}";
  $urlaprova_orcamento = criptografar($urlaprova_orcamento);
  $html_botao_passo = "
  <button type='button' class='btn btn-custom float-end mt-auto' onclick='javascript:orcamentoENVIADO()'>
    Confirmar Envio
  </button>
  ";
}

if ($passo == "2") {
  $html_botao_passo = "
  <button type='button' class='btn btn-custom float-end mt-auto' onclick='javascript:orcamentoAPROVADO()'>
    Confirmar Aprovado
  </button>  ";
}

if ($passo == "3") {
  $html_botao_passo = "
  <button type='button' class='btn btn-custom float-end mt-auto' onclick='javascript:orcamentoREALIZADO()'>
    Confirmar Realizado
  </button>  ";
}

if ($passo == "4") {
  $html_botao_passo = "
  <button type='button' class='btn btn-custom float-end mt-auto' onclick='javascript:orcamentoRECIBO()'>
    Confirmar Recibo
  </button>  ";
}


$html_orcamentosrv = "";
$contador = 0;
$subtotal = 0;
$total = 0;
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
  $chave_servico = $torcamentosrv["chave_servico"];
  $descr_servico = $torcamentosrv["descr_servico"];
  $qtd_orcamentosrv = $torcamentosrv["qtd_orcamentosrv"];
  $vlu_orcamentosrv = $torcamentosrv["vlu_orcamentosrv"];
  $subtotal += (intval($qtd_orcamentosrv) * floatval($vlu_orcamentosrv));
  $total += (intval($qtd_orcamentosrv) * floatval($vlu_orcamentosrv));
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
  $contador += 1;
  $html_orcamentosrv .= "
  <div class='col-8'>
    <span class='texto17'>{$descr_servico}</span><br>
    <span class='texto12-regular'>por {$descr_servico} - " . Float2Moeda(floatval($torcamentosrv["vlu_orcamentosrv"]), 2) . "</span>
  </div>
  <div class='col-4 d-flex justify-content-end'>
    <div class='row g-3 align-items-center'>
      <div class='col-auto'>
        <input type='hidden' id='servico{$contador}' name='servico{$contador}' value='{$chave_servico}'>
        <input type='hidden' id='chave{$contador}' name='chave{$contador}' value='{$chave_orcamentosrv}'>
        <input type='text' class='form-control' style='max-width:50px' id='qtd{$contador}' name='qtd{$contador}' value='{$qtd_orcamentosrv}' onblur='javascript:alteraSERVICO(this);'>
      </div>
      <div class='col-auto'>        
        <span id='valorserv1' class='texto16 me-3'>
          " . Float2Moeda(floatval($torcamentosrv["vlu_orcamentosrv"]), 2) . "
        </span>
        <a href='#' onclick='javascript:excluiSERVICO({$chave_orcamentosrv})'><i class='ci ci-acao-excluir'></i></a>
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
      Pesquisa_Servico();
    }
  }

  function avaliatecla_pesquisaservico(e) {
    if (e.keyCode == 13) {
      Modal_BrowseServicos_Reshow('');
    }
  }

  function Pesquisa_Servico() {
    const vchave_orcamento = document.getElementById("chave_orcamento").value;
    const vchave_servico = document.getElementById("chave_servico").value;
    if (vchave_orcamento != "" && vchave_servico != "") {
      document.FORCAMENTOSRV_EDITA.submit();
    }
  }

  function alteraSERVICO(ccampo) {
    const numcampo = ccampo.id.slice(3);
    document.getElementById("chave_servico").value = document.getElementById("servico" + numcampo).value;
    document.getElementById("chave_orcamentosrv").value = document.getElementById("chave" + numcampo).value;
    document.getElementById("qtd_orcamentosrv").value = document.getElementById("qtd" + numcampo).value;
    document.getElementById("acao").value = "EDITA";
    document.FORCAMENTOSRV_EDITA.submit();
  }

  function excluiSERVICO(ccampo) {
    document.getElementById("chave_orcamentosrv").value = ccampo;
    document.getElementById("acao").value = "EXCLUI";
    document.FORCAMENTOSRV_EDITA.submit();
  }

  function enviarEMAIL() {
    document.FRECIBOSRV_EDITA.action = "recibo_email.php";
    document.FRECIBOSRV_EDITA.submit();
  }

  function orcamentoENVIADO() {
    const form = document.createElement('form');
    const vchave_orcamento = document.getElementById("chave_orcamento").value;
    form.method = 'POST';
    form.action = 'orcamento_status.php';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'chave_orcamento';
    input.value = vchave_orcamento;
    form.appendChild(input);  

    const input2 = document.createElement('input');
    input2.type = 'hidden';
    input2.name = 'situacao_orcamento';
    input2.value = 'ENVIADO';
    form.appendChild(input2);  
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
  }

  function orcamentoAPROVADO() {
    const form = document.createElement('form');
    const vchave_orcamento = document.getElementById("chave_orcamento").value;
    form.method = 'POST';
    form.action = 'orcamento_status.php';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'chave_orcamento';
    input.value = vchave_orcamento;
    form.appendChild(input);  

    const input2 = document.createElement('input');
    input2.type = 'hidden';
    input2.name = 'situacao_orcamento';
    input2.value = 'APROVADO';
    form.appendChild(input2);  
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
  }  

  function orcamentoREALIZADO() {
    const form = document.createElement('form');
    const vchave_orcamento = document.getElementById("chave_orcamento").value;
    form.method = 'POST';
    form.action = 'orcamento_status.php';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'chave_orcamento';
    input.value = vchave_orcamento;
    form.appendChild(input);  

    const input2 = document.createElement('input');
    input2.type = 'hidden';
    input2.name = 'situacao_orcamento';
    input2.value = 'REALIZADO';
    form.appendChild(input2);  
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
  }

  function orcamentoRECIBO() {
    const form = document.createElement('form');
    const vchave_orcamento = document.getElementById("chave_orcamento").value;
    form.method = 'POST';
    form.action = 'orcamento_status.php';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'chave_orcamento';
    input.value = vchave_orcamento;
    form.appendChild(input);  

    const input2 = document.createElement('input');
    input2.type = 'hidden';
    input2.name = 'situacao_orcamento';
    input2.value = 'RECIBO';
    form.appendChild(input2);  
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
  }

  function orcamentoENVIAR() {
    const form = document.createElement('form');
    const vchave_orcamento = document.getElementById("chave_orcamento").value;
    form.method = 'POST';
    form.action = 'orcamento_email.php';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'chave_orcamento';
    input.value = vchave_orcamento;
    form.appendChild(input);  
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
  }  

  function orcamentoWHATSAPP() {
    const inputLink = document.getElementById("link_aprovacao").value;    
    const numeroTelefone = document.getElementById("tel_cliente").value;    
    const mensagem = `Olá, Segue o link para você visualizar e aprovar o seu orçamento:\n\n${inputLink}`;    
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
          <div class="col-12 mt-3">
            <span class="texto11">ORÇAMENTOS . Nº <?php echo $chave_orcamento_ext; ?></span>
          </div>
          <div class="row">
            <div class="col-sm-10">
              <span style="color:#1E2933; font-weight:700; font-size: 1.75em;">Orçamento para <?php echo $nome_cliente_compac ?></span><br>
              <span class="texto-corpo">Envie, aguarde a aprovação, realize o serviço e gere o recibo.</span>
            </div>
            <div class="col-sm-2">
              <div class="d-flex flex-column h-100">
                <?php echo $html_botao_passo; ?> 
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="stepper-wrapper d-flex justify-content-between align-items-center my-4">
                <!-- Passo 1: Concluído -->
                <div class="stepper-item completed d-flex align-items-center">
                  <div class="step-counter d-flex align-items-center justify-content-center fw-bold">
                    <?php echo $passo1; ?>
                  </div>
                  <span class="step-name fw-bold ms-2">Criado</span>
                </div>

                <div class="stepper-line completed flex-grow-1 mx-3"></div>

                <!-- Passo 2: Pendente -->
                <div class="stepper-item <?php echo ($passo2 == "2" ? "" : "completed ") ?> d-flex align-items-center">
                  <div class="step-counter d-flex align-items-center justify-content-center fw-bold">
                    <?php echo $passo2; ?>
                  </div>
                  <span class="step-name ms-2">Enviado</span>
                </div>

                <div class="stepper-line flex-grow-1 mx-3"></div>

                <!-- Passo 3: Pendente -->
                <div class="stepper-item <?php echo ($passo3 == "3" ? "" : "completed ") ?> d-flex align-items-center">
                  <div class="step-counter d-flex align-items-center justify-content-center fw-bold">
                    <?php echo $passo3; ?>
                  </div>
                  <span class="step-name ms-2">Aprovado</span>
                </div>

                <div class="stepper-line flex-grow-1 mx-3"></div>

                <!-- Passo 4: Pendente -->
                <div class="stepper-item <?php echo ($passo4 == "4" ? "" : "completed ") ?> d-flex align-items-center">
                  <div class="step-counter d-flex align-items-center justify-content-center fw-bold">
                    <?php echo $passo4; ?>
                  </div>
                  <span class="step-name ms-2">Realizado</span>
                </div>

                <div class="stepper-line flex-grow-1 mx-3"></div>

                <!-- Passo 5: Pendente -->
                <div class="stepper-item <?php echo ($passo5 == "5" ? "" : "completed ") ?> d-flex align-items-center">
                  <div class="step-counter d-flex align-items-center justify-content-center fw-bold">
                    <?php echo $passo5; ?>
                  </div>
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
                        <input type="hidden" id="chave_orcamentosrv" name="chave_orcamentosrv" value="">
                        <input type="hidden" id="chave_servico" name="chave_servico" value="">
                        <input type="hidden" id="qtd_orcamentosrv" name="qtd_orcamentosrv" value="">
                        <input type="hidden" id="acao" name="acao" value="NOVO">
                        <input type="hidden" id="tel_cliente" name="tel_cliente" value="<?php echo $tel_cliente; ?>">                        
                        <div class="row">
                          <?php echo $html_orcamentosrv ?>
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
                          </div>-->
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
                                TOTAL
                              </div>
                              <div class="col-6 text-end">
                                <span class="texto28"><?php echo Float2Moeda(floatval($total), 2); ?></span>
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
              <?php if ($passo == "3") { ?> 
              <div class="row">
                <div class="col-12 mb-2">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-12 mt-2 mb-2">
                          <div class="mb-3">
                            <span class="texto12">PRÓXIMA ETAPA</span>
                          </div>
                          <div class="mb-3">
                            <span class="texto12-regular">O orçamento foi </span><span class="texto12" style="color:#000">aprovado.</span><span class="texto12-regular"> Marque o serviço como realizado para gerar o recibo.</span>
                          </div>
                          <div class="mt-2 pt-2">
                            <button type="button" class="btn btn-custom w-100" onclick="javascript:orcamentoREALIZADO()">Marcar realizado e emitir</button>
                          </div>                         
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php } ?> 
            </div>
            <?php if ($passo == "1") { ?> 
            <div class="row mt-3">
              <div class="col-12">
                <div class="row">
                  <div class="col-12 mb-2">
                    <div class="card">
                      <div class="card-body">
                        <div class="col">
                          <span class="texto12">ENVIAR ORÇAMENTO PARA O CLIENTE</span><br>
                          <span class="texto12-regular">Disponibilize a proposta diretamente pelos canais de contato da Mariana.</span>
                        </div>
                        <div class="col">
                          <div class="d-flex justify-content-center align-items-center border-bottom main-content gap-3 p-3" style="--bs-border-color:#DDE4E9">
                            <div class="col-3 gap-3 rounded-3 p-3" style="background-color: #F8FAFC">
                              <div class="d-flex justify-content-start align-items-center gap-3 mb-3">
                                <div class="rounded-2 p-2" style="min-height:32px; min-width: 32px; background-color: #e5f7ed;">
                                  <i class="ci ci-whatsapp fs-4" style="background-color: #25D366"></i>
                                </div>
                                <div>
                                  <span class="texto12" style="color: #0F172A'">WhatsApp</span><br>
                                  <span class="texto11-regular"><?php echo $tel_cliente ?></span>
                                </div>
                              </div>
                              <div>
                                <button type="button" id="BTN_WHATSAPP" name="BTN_WHATSAPP" class="btn btn-custom-lima w-100 rounded-pill" onclick="javascript:orcamentoWHATSAPP();">Enviar pelo WhatsApp</button>
                              </div>
                            </div>
                            <div class="col-3 gap-3 rounded-3 p-3" style="background-color: #F8FAFC">
                              <div class="d-flex justify-content-start align-items-center gap-3 mb-3">
                                <div class="rounded-2 p-2" style="min-height:32px; min-width: 32px; background-color: #e8f0fc;">
                                  <i class="ci ci-envelope fs-4" style="background-color: #2563EB"></i>
                                </div>
                                <div>
                                  <span class="texto12" style="color: #0F172A'">E-mail</span><br>
                                  <span class="texto11-regular"><?php echo $email_cliente ?></span>
                                </div>
                              </div>
                              <div>
                                <button type="button" class="btn btn-primary w-100 rounded-pill" onclick="javascript:orcamentoENVIAR();">Enviar por E-mail</button>
                              </div>
                            </div>
                          </div>

                        </div>
                        <hr>
                        <div class="col">
                          <span class="texto12">LINK PÚBLICO PARA APROVAÇÃO DO CLIENTE</span>
                        </div>
                        <div clas="row">
                          <div class="col d-flex justify-content-between align-items-center gap-3">                            
                            <input type="text" class="form-control" id="link_aprovacao" name="link_aprovacao" value="https://reciboexpress.kraftwork.com.br/publico/orcamento/orcamento_status.php?id1=<?php echo $urlaprova_orcamento ?>">
                            <button type="button" class="btn btn-light" id="BTN_COPIAR_LINK" name="BTN_COPIAR_LINK"><span class="text-nowrap">Copiar Link</span></button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php } ?> 
          </div>
        </div>
      </main>
    </div>
  </div>
  <!-- FIM DA ÁREA DE CONTEÚDO -->
<script>
  const btn_coipar_link = document.getElementById('BTN_COPIAR_LINK');
  const link_aprovacao = document.getElementById('link_aprovacao');
  btn_coipar_link.addEventListener('click', async () => {
    try {
      // Copia o valor do input para a área de transferência
      await navigator.clipboard.writeText(link_aprovacao.value);
      // Feedback visual temporário para o usuário
      btn_coipar_link.innerHTML = 'Copiado!';
      setTimeout(() => {
        btn_coipar_link.innerHTML = "<span class='text-nowrap'>Copiar Link</span>";
      }, 2000);
    }
    catch (err) {
      // Fallback para navegadores antigos se o Clipboard API falhar
      link_aprovacao.select();
      document.execCommand('copy');
      alert('Link copiado com sucesso!');
    }
  });
  <?php include($Raiz . "include/php/rodape.php"); ?>
</body>

</html>
<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /publico/historico/index.php
 ***** Conteúdo: 10-historico
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
include($Raiz . "conexao/db.php");
VerificaSessao();
$ok = true;
$chave_perfil = $_SESSION["CHAVE_PERFIL"];
$html = "";
$strsql = "";
$filtro_situacao_orcamento = "";
$filtro_chave_cliente = "";
$filtro_base_cadastro = "ORCAMENTO"; // ORCAMENTOS OU PEDIDOS
$data_inicial = "";
$data_final = "";
$strsql = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST["situacao_orcamento"])) {
    if (isset($_POST["situacao_orcamento"])) {
      $filtro_situacao_orcamento = $_POST["situacao_orcamento"];
    }
    if (isset($_POST["base_cadastro"])) {
      $filtro_base_cadastro = $_POST["base_cadastro"];
    }
    if (isset($_POST["chave_cliente"])) {
      $filtro_chave_cliente = $_POST["chave_cliente"];
    }
    if ($filtro_situacao_orcamento == "TUDO") {
      $filtro_situacao_orcamento = "";
    }
    if ($filtro_situacao_orcamento != "") {
      $strsql .= "torcamento.situacao_orcamento = :vsituacao_orcamento and ";
    }
    if ($filtro_chave_cliente != "") {
      $strsql .= "torcamento.chave_cliente = :vchave_cliente and ";
    }
    if ($filtro_base_cadastro == "PEDIDO") {
      $strsql .= "torcamento.chave_recibo > 0 and ";
    }    
  }    
}
$btn_tudo_css = "btn-outline-secondary";
$btn_aprovado_css = "btn-outline-secondary";
$btn_enviado_css = "btn-outline-secondary";
$btn_aguardando_css = "btn-outline-secondary";
$btn_periodo_css = "btn-outline-secondary";
$btn_cliente_css = "btn-outline-secondary";
$btn_orcamento_css = "btn-outline-secondary";
$btn_pedido_css = "btn-outline-secondary";
$btn_tudo_valor = "0";
$btn_aprovado_valor = "0";
$btn_enviado_valor = "0";
$btn_aguardando_valor = "0";
$btn_periodo_valor = "0";
$btn_cliente_valor = "0";
$btn_orcamento_valor = "0";
$btn_pedido_valor = "0";
if ($filtro_situacao_orcamento == "TUDO" || $filtro_situacao_orcamento == "") {
  $btn_tudo_css = "btn-custom";
  $btn_tudo_valor = "1";
}
if ($filtro_situacao_orcamento == "APROVADO") {
  $btn_aprovado_css = "btn-custom";
  $btn_aprovado_valor = "1";
}
if ($filtro_situacao_orcamento == "ENVIADO") {
  $btn_enviado_css = "btn-custom";
  $btn_enviado_valor = "1";
}
if ($filtro_situacao_orcamento == "AGUARDANDO") {
  $btn_aguardando_css = "btn-custom";
  $btn_aguardando_valor = "1";
}
if ($filtro_chave_cliente != "") {
  $btn_cliente_css = "btn-custom";
  $btn_cliente_valor = "1";
}
if ($filtro_base_cadastro == "PEDIDO") {
  $btn_pedido_css = "btn-custom";
  $btn_pedido_valor = "1";
  $btn_orcamento_css = "btn-outline-secondary";
  $btn_orcamento_valor = "0";
}
else {
  $btn_pedido_css = "btn-outline-secondary";
  $btn_pedido_valor = "0";
  $btn_orcamento_css = "btn-custom";
  $btn_orcamento_valor = "1";
}
abre_db();
$strsql = "
select
torcamento.*
,tcliente.nome_cliente
,(select tservico.descr_servico from torcamentosrv left join tservico on torcamentosrv.chave_servico = tservico.chave_servico where torcamento.chave_orcamento = torcamentosrv.chave_orcamento and torcamentosrv.caixa_orcamentosrv = 1 order by torcamentosrv.chave_orcamentosrv limit 1) as descr_servico
from
torcamento 
left join tcliente on torcamento.chave_cliente = tcliente.chave_cliente
where
torcamento.chave_perfil = :vchave_perfil and 
{$strsql}
torcamento.caixa_orcamento = 1 
order by torcamento.chave_orcamento desc
";
$qorcamento = $pdo->prepare($strsql);
$qorcamento->bindParam(":vchave_perfil", $chave_perfil);
if ($filtro_chave_cliente != "") {
  $qorcamento->bindParam(":vchave_cliente", $filtro_chave_cliente);
}
if ($filtro_situacao_orcamento != "") {
  $qorcamento->bindParam(":vsituacao_orcamento", $filtro_situacao_orcamento);
}
$qorcamento->execute();
while ($torcamento = $qorcamento->fetch(PDO::FETCH_ASSOC)) {
  $chave_orcamento = $torcamento["chave_orcamento"];
  $chave_orcamento_ext = str_pad($chave_orcamento, 6, "0", STR_PAD_LEFT);
  $nome_cliente = $torcamento["nome_cliente"];
  $dtc_orcamento = $torcamento["dtc_orcamento"];
  $dtu_orcamento = $torcamento["dtu_orcamento"];
  $dtu_orcamento = strtoupper(DiaMesExt($dtu_orcamento));
  $valor_orcamento = $torcamento["valor_orcamento"];
  $valor_orcamento = Float2Moeda(floatval($valor_orcamento), 2);
  $descr_servico = $torcamento["descr_servico"];
  $situacao_orcamento = $torcamento["situacao_orcamento"];
  $urlpdf_orcamento = $torcamento["urlpdf_orcamento"];
  if (strlen($nome_cliente) > 15) {
    $nome_cliente = substr($nome_cliente, 0, 15) . '...';
  }
  $html_coluna_situacao = "<span class='texto12'></span>";
  if ($situacao_orcamento == "CRIADO") {
    $html_coluna_situacao = "<span class='texto12' style='color:#EA580C'><i class='ci ci-marcador-amarelo'></i> {$situacao_orcamento}</span>";
  }
  if ($situacao_orcamento == "ENVIADO") {
    $html_coluna_situacao = "<span class='texto12' style='color:#2563EB'><i class='ci ci-marcador-azul'></i> {$situacao_orcamento}</span>";
  }
  if ($situacao_orcamento == "APROVADO") {
    $html_coluna_situacao = "<span class='texto12' style='color:#16A34A'><i class='ci ci-marcador-verde'></i> {$situacao_orcamento}</span>";
  }
  if ($situacao_orcamento == "REALIZADO") {
    $html_coluna_situacao = "<span class='texto12' style='color:#16A34A'><i class='ci ci-marcador-verde'></i> {$situacao_orcamento}</span>";
  }
  if ($situacao_orcamento == "RECIBO") {
    $html_coluna_situacao = "<span class='texto12' style='color:#2563EB'><i class='ci ci-marcador-azul'></i> {$situacao_orcamento}</span>";
  }
  if ($situacao_orcamento == "RECUSADO") {
    $html_coluna_situacao = "<span class='texto12' style='color:#d81818'><i class='ci ci-marcador-vermelho'></i> {$situacao_orcamento}</span>";
  }
  $pdf = "";  
  if ($urlpdf_orcamento != "") {
    $pdf = "    
    <a class='link-padrao' href='https://reciboexpress.guiadomestico.com.br/publico/pdfs/{$urlpdf_orcamento}'>
    <span class='texto12-regular pe-3' style='color:#111827'>Ver <i class='ci ci-acao-download' style='background-color:#111827''></i></span>
    <span class='texto11'> PDF</span></a>";
  }
  $html .= "
  <tr>
    <td data-id_dbg='{$chave_orcamento}' class='SELECIONA_LINHA'><span class='texto11 pe-3'>{$chave_orcamento_ext}</span><span class='texto13'> {$nome_cliente}</span></td>
    <td><span class='texto13'>{$dtu_orcamento}</span></td>
    <td><span class='texto13-regular' stule='color:#111827'>{$descr_servico}</span></td>
    <td><span class='texto13'>{$valor_orcamento}</span></td>
    <td><span class='texto12' style='color:#EA580C'>{$html_coluna_situacao}</td>
    <td class='text-end'>
      {$pdf}
    </td>
  </tr>
  ";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Recibo Express - Histórico</title>
  <?php include($Raiz . "include/html/header.html"); ?>
  <link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet">
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

  /* Container externo arredondado */
  .toggle-switch-container {
    background-color: #f8f9fa !important;
    border-color: #6c757d !important;
    line-height: 1;
  }

  /* Estilo padrão do botão inativo */
  .toggle-btn {
    color: #6c757d;
    border: none;
    line-height: 1.25;
    transition: all 0.2s ease-in-out;
  }

  /* Estilo do botão SELECIONADO (ativo) */
  .btn-check:checked+.toggle-btn {
    background-color: #17324d;
    /* Azul escuro */
    color: #ffffff;
    /* Texto branco */
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
  }

  /* Hover na opção inativa */
  .toggle-btn:hover:not(.btn-check:checked + .toggle-btn) {
    color: #17324d;
  }
</style>
<script type="text/javascript">
  function historicoFILTRO(ccampo) {
    let vsituacao_orcamento = document.FHISTORICO.situacao_orcamento.value;
    let vbase_cadastro = document.FHISTORICO.base_cadastro.value;
    let vchave_cliente = document.FHISTORICO.chave_cliente.value;
    let vdata_inicial = document.FHISTORICO.data_inicial.value;
    let vdata_final = document.FHISTORICO.data_final.value;
    const btn_pressionado = ccampo.id;
    switch (btn_pressionado) {
      case "BTN_TUDO":
        if (ccampo.value == "1") {
          ccampo.value = "0";
          vsituacao_orcamento = "";
        }
        else {
          ccampo.value = "1";
          vsituacao_orcamento = "TUDO";
        }        
        break;
      case "BTN_ENVIADO":
        if (ccampo.value == "1") {
          ccampo.value = "0";
          vsituacao_orcamento = "";
        }
        else {
          ccampo.value = "1";
          vsituacao_orcamento = "ENVIADO";
        }        
        break;
      case "BTN_APROVADO":
        if (ccampo.value == "1") {
          ccampo.value = "0";
          vsituacao_orcamento = "";
        }
        else {
          ccampo.value = "1";
          vsituacao_orcamento = "APROVADO";
        }
        break;
      case "BTN_AGUARDANDO":
        if (ccampo.value == "1") {
          ccampo.value = "0";
          vsituacao_orcamento = "";
        }
        else {
          ccampo.value = "1";
          vsituacao_orcamento = "AGUARDANDO";
        }
        break;
      case "BTN_CLIENTE":
        if (ccampo.value == "1") {
          ccampo.value = "0";
        }
        else {
          ccampo.value = "1";
        }
        break;
      case "BTN_PEDIDO":
        if (ccampo.value == "1") {
          ccampo.value = "0";
          vbase_cadastro = "";
        }
        else {
          ccampo.value = "1";
          vbase_cadastro = "PEDIDO";
        }
        break;
      case "BTN_ORCAMENTO":
        if (ccampo.value == "1") {
          ccampo.value = "0";
          vbase_cadastro = "";
        }
        else {
          ccampo.value = "1";
          vbase_cadastro = "";
        }
        break;
      case "BTN_PERIODO":
        if (ccampo.value == "1") {
          ccampo.value = "0";
        }
        else {
          ccampo.value = "1";          
        }
        break;

      default:
        //vsituacao_orcamento = "";
    }
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'index.php';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'situacao_orcamento';
    input.value = vsituacao_orcamento;
    form.appendChild(input); 

    const input_base_cadastro = document.createElement('input');
    input_base_cadastro.type = 'hidden';
    input_base_cadastro.name = 'base_cadastro';
    input_base_cadastro.value = vbase_cadastro;
    form.appendChild(input_base_cadastro);

    const input_cliente = document.createElement('input');
    input_cliente.type = 'hidden';
    input_cliente.name = 'chave_cliente';
    input_cliente.value = vchave_cliente;
    form.appendChild(input_cliente);

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
  }

  function Modal_BrowseClientes() {
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
    $.get('/publico/orcamento/modal_clientes.php?' + vurl, function (data) {
      $('#MODAL_BROWSECLIENTES').find('.modal-body').html(data);
      $('#MODAL_BROWSECLIENTES').one('hidden.bs.modal', function () {
        $(document).removeData('bs.modal');
      })
    })
  };

  function Modal_BrowseClientes_Reshow(npag) {
    var vurl = '';
    var vpagina = '';
    var vbtn_redef = 'btn btn-sm btn-warning';
    //	var vchave_repr = document.getElementById('CHAVE_REPR').value;
    var vmeusclipesq = document.getElementById('MEUSCLIPESQ').value;
    var vreset = '';
    if (String(npag) == 'undefined') {
      npag = '1';
    }
    if (String(npag) == 'RESET') {
      vreset = npag;
      npag = '1';
      vmeusclipesq = '';
      document.getElementById('MEUSCLIPESQ').value = vmeusclipesq;
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
    if (vmeusclipesq != '' && vmeusclipesq != 'undefined') {
      if (vurl != '') {
        vurl = vurl + '&';
      }
      vurl = vurl + 'PESQ=' + vmeusclipesq;
    }
    if (vreset == 'RESET') {
      if (vurl != '') {
        vurl = vurl + '&';
      }
      vurl = vurl + 'RESET=SIM';
    }
    if (document.getElementById('MEUSCLIPESQ').value == '') {
      vbtn_redef = 'btn btn-sm btn-warning d-none';
    }
    document.getElementById('BTN_FILTRO_REDEF').setAttribute('class', vbtn_redef);
    $.get('/publico/orcamento/modal_clientes.php?' + vurl, function (data) {
      $('#MODAL_BROWSECLIENTES').find('.modal-body').html(data);
    })
  };

  function Modal_BrowseClientes_Retorno() {
    if ($('#BOX_BROWSECLIENTES').val() != '') {
      $('#chave_cliente').val($('#BOX_BROWSECLIENTES').val());
    }
    //  $('#main-result').val($('#modal-result').val());
    $('#MODAL_BROWSECLIENTES').modal('hide');
    if ($('#BOX_BROWSECLIENTES').val() == '') {
      browseclientes_novo();
      document.refresh;
    }
    else {
      Pesquisa_Cliente();
    }
  }

  function avaliatecla_pesquisacliente(e) {
    if (e.keyCode == 13) {
      Modal_BrowseClientes_Reshow('');
    }
  }

  function Pesquisa_Cliente() {
    const botao = document.getElementById("BTN_CLIENTE");
    botao.value = "0";
    historicoFILTRO(botao);
  }

</script>

<body>

  <!-- Modal Browse Clientes -->
  <div class="modal fade" id="MODAL_BROWSECLIENTES" tabindex="-1" role="dialog" aria-labelledby="MODAL_BROWSECLIENTES_LABEL">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Clientes</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-primary" id="BTN_MODAL_CLIENTE_VAZIO" name="BTN_MODAL_CLIENTE_VAZIO">Todos Clientes</button>
          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Voltar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- EOF Modal Browse Clientes -->

  <div class="container">
    <div class="row">
      <?php include($Raiz . "include/php/menu_lateral.php"); ?>

      <!-- CONTEÚDO PRINCIPAL DA PÁGINA -->
      <main class="col-12 col-md-9 col-lg-10">
        <!-- Cabeçalho da Página -->
        <?php include($Raiz . "include/php/menu_superior.php"); ?>
        <div class="main-content">
          <div class="col-12 mt-3">
            <span class="texto11">HISTÓRICO</span>
          </div>
          <div class="row">
            <div class="col-12">
              <span style="color:#1E2933; font-weight:700; font-size: 1.75em;">Histórico de Orçamentos</span><br>
              <span class="texto-corpo">Filtre por status, período e cliente.</span>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-12">
              <button class="btn btn-sm <?php echo $btn_tudo_css; ?> rounded-4" id="BTN_TUDO" name="BTN_TUDO" value="<?php echo $btn_tudo_valor; ?>" onclick="javascript:historicoFILTRO(this)">
                Tudo
              </button>
              <button class="btn btn-sm <?php echo $btn_aprovado_css; ?> rounded-4" id="BTN_APROVADO" name="BTN_APROVADO" value="<?php echo $btn_aprovado_valor; ?>" onclick="javascript:historicoFILTRO(this)">
                Aprovado
              </button>
              <button class="btn btn-sm <?php echo $btn_enviado_css; ?> rounded-4" id="BTN_ENVIADO" name="BTN_ENVIADO" value="<?php echo $btn_enviado_valor; ?>" onclick="javascript:historicoFILTRO(this)">
                Enviado
              </button>
              <button class="btn btn-sm <?php echo $btn_aguardando_css; ?> rounded-4" id="BTN_AGUARDANDO" name="BTN_AGUARDANDO" value="<?php echo $btn_aguardando_valor; ?>" onclick="javascript:historicoFILTRO(this)">
                Aguardando
              </button>
              <button class="btn btn-sm <?php echo $btn_periodo_css; ?> rounded-4" id="BTN_PERIODO" name="BTN_PERIODO" value="<?php echo $btn_periodo_valor; ?>">
                <i class="ci ci-acao-calendario" style="background-color: #9AA6AE"></i> Período: <strong class="text-negrito" id="textoPeriodo">...</strong>
              </button>
              <button class="btn btn-sm <?php echo $btn_cliente_css; ?> rounded-4" id="BTN_CLIENTE" name="BTN_CLIENTE" value="<?php echo $btn_cliente_valor; ?>" data-bs-toggle='modal' data-bs-target='#MODAL_BROWSECLIENTES' onclick="javascript:Modal_BrowseClientes();">
                <i class="ci ci-acao-cliente" style="background-color: #9AA6AE"></i> Cliente<span class="texto-negrito"> Todos</span>
              </button>
              <button class="btn btn-sm <?php echo $btn_orcamento_css; ?> rounded-4" id="BTN_ORCAMENTO" name="BTN_ORCAMENTO" value="<?php echo $btn_orcamento_valor; ?>" onclick="javascript:historicoFILTRO(this)">
                Orçamentos
              </button>
              <button class="btn btn-sm <?php echo $btn_pedido_css; ?> rounded-4" id="BTN_PEDIDO" name="BTN_PEDIDO" value="<?php echo $btn_pedido_valor; ?>" onclick="javascript:historicoFILTRO(this)">
                Pedidos
              </button>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <form action="/publico/orcamento/orcamento_edita.php" id="FHISTORICO" name="FHISTORICO" method="POST">
                    <input type="hidden" id="chave_orcamento" name="chave_orcamento" value="">
                    <input type="hidden" id="acao" name="acao" value="EDITAR">
                    <input type="hidden" id="situacao_orcamento" name="situacao_orcamento" value="<?php echo $filtro_situacao_orcamento; ?>">
                    <input type="hidden" id="base_cadastro" name="base_cadastro" value="<?php echo $filtro_base_cadastro; ?>">
                    <input type="hidden" id="chave_cliente" name="chave_cliente" value="<?php echo $filtro_chave_cliente; ?>">
                    <input type="hidden" id="data_inicial" name="data_inicial" value="<?php echo $data_inicial; ?>">
                    <input type="hidden" id="data_final" name="data_final" value="<?php echo $data_final; ?>">
                    <table class='table table-hover'>
                      <thead>
                        <tr>
                          <th scope="col"><span class="texto11">ORÇAMENTO</span></th>
                          <th scope="col"><span class="texto11">DATA</span></th>
                          <th scope="col"><span class="texto11">SERVIÇO</span></th>
                          <th scope="col"><span class="texto11">VALOR</span></th>
                          <th scope="col"><span class="texto11">STATUS</span></th>
                          <th class="text-end" scope="col"><span class="texto11">AÇÕES / PDF</span></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php echo $html ?>
  <!--                      
                        <tr>
                          <td><span class="texto11 pe-3">#0038</span><span class="texto13"> Mariana Lopes</span></td>
                          <td><span class="texto13">17 SET</span></td>
                          <td><span class="texto13-regular" stule="color:#111827">Faxina residencial detalhada</span></td>
                          <td><span class="texto13">R$ 180,00</span></td>
                          <td><span class="texto12" style="color:#EA580C"><i class="ci ci-marcador-amarelo"></i> AGUARDANDO</span></td>
                          <td class="text-end"><span class="texto12-regular pe-3" style="color:#111827">Ver <i class="ci ci-acao-download" style="background-color:#111827""></i></span><span class=" texto11"> PDF</span></td>
                        </tr>
                        <tr>
                          <td><span class="texto11 pe-3">#0035</span><span class="texto13"> Carlos Almeida</span></td>
                          <td><span class="texto13">14 SET</span></td>
                          <td><span class="texto13-regular" stule="color:#111827">Manutenção elétrica geral</span></td>
                          <td><span class="texto13">R$ 320,00</span></td>
                          <td><span class="texto12" style="color:#2563EB"><i class="ci ci-marcador-azul"></i>ENVIADO</span></td>
                          <td class="text-end"><span class="texto12-regular pe-3" style="color:#111827">Enviar <i class="ci ci-acao-download" style="background-color:#111827""></i></span><span class=" texto11"> PDF</span></td>
                        </tr>
                        <tr>
                          <td><span class="texto11 pe-3">#0037</span><span class="texto13"> Mariana Lopes</span></td>
                          <td><span class="texto13">09 SET</span></td>
                          <td><span class="texto13-regular" stule="color:#111827">Faxina residencial quinzenal</span></td>
                          <td><span class="texto13">R$ 180,00</span></td>
                          <td><span class="texto12" style="color:#16A34A"><i class="ci ci-marcador-verde"></i>APROVADO</span></td>
                          <td class="text-end"><span class="texto12-regular pe-3" style="color:#111827">Ver <i class="ci ci-acao-download" style="background-color:#111827""></i></span><span class=" texto11"> PDF</span></td>
                        </tr>
                        <tr>
                          <td><span class="texto11 pe-3">#0036</span><span class="texto13"> Roberto Dias</span></td>
                          <td><span class="texto13">05 SET</span></td>
                          <td><span class="texto13-regular" stule="color:#111827">Faxina residencial quinzenal</span></td>
                          <td><span class="texto13">R$ 850,00</span></td>
                          <td><span class="texto12" style="color:#EA580C"><i class="ci ci-marcador-vermelho"></i>RECUSADO</span></td>
                          <td class="text-end"><span class="texto12-regular pe-3" style="color:#111827">Ver <i class="ci ci-acao-download" style="background-color:#111827""></i></span><span class=" texto11"> PDF</span></td>
                        </tr>
                        <tr>
                          <td><span class="texto11 pe-3">#0034</span><span class="texto13"> Beatriz Nunes</span></td>
                          <td><span class="texto13">02 SET</span></td>
                          <td><span class="texto13-regular" stule="color:#111827">Cozinha · diária para eventos</span></td>
                          <td><span class="texto13">R$ 220,00</span></td>
                          <td><span class="texto12" style="color:#16A34A"><i class="ci ci-marcador-verde"></i>APROVADO</span></td>
                          <td class="text-end"><span class="texto12-regular pe-3" style="color:#111827">Ver <i class="ci ci-acao-download" style="background-color:#111827""></i></span><span class=" texto11"> PDF</span></td>
                        </tr>
  -->                      
                      </tbody>
                    </table>
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
  <?php include($Raiz . "include/php/rodape.php"); ?>
  <script type="text/javascript">
    $(document).ready(function () {
      $(".SELECIONA_LINHA").click(function () {
        document.getElementById("chave_orcamento").value = $(this).data("id_dbg");
        document.getElementById("acao").value = "EDITA";
        document.getElementById("FHISTORICO").submit();
      });
/*      $("#BTN_INCLUIR").click(function () {
        document.getElementById("chave_cliente").value = "0";
        document.getElementById("FCLIENTE").submit();
      });*/
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/plugins/ranges.js"></script>
  <script>
    const picker = new Litepicker({
      element: document.getElementById('BTN_PERIODO'),
      singleMode: false, // Permite selecionar intervalo (Data Inicial -> Data Final)
      numberOfMonths: 2,  // Exibe 2 meses lado a lado
      numberOfColumns: 2,
      format: 'DD/MM/YYYY',
      lang: 'pt-BR', // Idioma em português
      setup: (picker) => {
        // Evento disparado quando o usuário escolhe a data inicial e final
        picker.on('selected', (date1, date2) => {
          const inicio = date1.format('DD/MM/YYYY');
          const fim = date2.format('DD/MM/YYYY');
          // Atualiza o texto dentro do botão
          document.getElementById('textoPeriodo').innerText = `${inicio} - ${fim}`;
          //console.log("Período selecionado:", inicio, "até", fim);
          // Exemplo: se precisar recarregar/filtrar os orçamentos:
          // Pesquisa_Por_Periodo(inicio, fim);
          document.getElementById("data_inicial").value = inicio;          
          document.getElementById("data_final").value = fim;
          const botao = document.getElementById("BTN_PERIODO");
          historicoFILTRO(botao);
        });
      }
    });
  </script>
</body>

</html>
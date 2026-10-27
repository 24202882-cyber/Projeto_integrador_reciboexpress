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
// Variáveis de controle
VerificaSessao();
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
if ($_SERVER['REQUEST_METHOD'] == "POST") {

}

abre_db();
$strsql = "
select
torcamento.*
,tcliente.nome_cliente
from
torcamento 
left join tcliente on torcamento.chave_cliente = tcliente.chave_cliente
where    
torcamento.chave_perfil = :vchave_perfil and
torcamento.caixa_orcamento = 1
";
$qorcamento = $pdo->prepare($strsql);
$qorcamento->bindParam(":vchave_perfil", $chave_perfil);
$qorcamento->execute();
while ($torcamento = $qorcamento->fetch(PDO::FETCH_ASSOC)) {
  $chave_orcamento = $torcamento["chave_orcamento"];
  $nome_cliente = $torcamento["nome_cliente"];
  $dtc_orcamento = $torcamento["dtc_orcamento"];
  $valor_orcamento = $torcamento["valor_orcamento"];
  //$cobranca_orcamento = strtolower($torcamento["cobranca_orcamento"]);
  //$valor_orcamento = "R$ " . number_format($torcamento["valor_orcamento"], 2, ',', '.');
  $html .= "
  <tr>
    <td><span class='texto11'>{$chave_orcamento}</span></td>
    <td><span class='texto13'>{$nome_cliente}</span></td>
    <td><span class='texto13'>{$dtc_orcamento}</span></td>
    <td><span class='texto13'>{$valor_orcamento}</span></td>
    <td class='text-end'><a href='#' onclick='javascript:alteraORCAMENTO({$chave_orcamento})'><i class='ci ci-acao-editar me-3'></i></a><a href='#' onclick='javascript:excluiORCAMENTO({$chave_orcamento})'><i class='ci ci-acao-excluir'></i></a></td>    
  </tr>
  ";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Recibo Express - Histórico</title>
  <?php include($Raiz . "include/html/header.html"); ?>
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

function novoORCAMENTO() {
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = 'orcamento_edita.php';
  const input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'chave_orcamento';
  input.value = 'NOVO';
  form.appendChild(input);

  const input2 = document.createElement('input');
  input2.type = 'hidden';
  input2.name = 'acao';
  input2.value = 'NOVO';
  form.appendChild(input2);

  document.body.appendChild(form);
  form.submit();
  document.body.removeChild(form);
}

function alteraORCAMENTO(cchave) {    
  document.getElementById('chave_orcamento').value = cchave;
  document.getElementById('acao').value = "EDITA";
  document.FORCAMENTO_EDITA.submit();
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
  $.get('modal_clientes.php?' + vurl, function (data) {
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
  $.get('modal_clientes.php?' + vurl, function (data) {
    $('#MODAL_BROWSECLIENTES').find('.modal-body').html(data);
  })
};

function Modal_BrowseClientes_Retorno() {
  if ($('#BOX_BROWSECLIENTES').val() != '') {
    $('#CHAVE_FOR').val($('#BOX_BROWSECLIENTES').val());
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
  const form = document.getElementById("FORCAMENTO_EDITA");
  form.submit();
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
            <span class="texto11">ORÇAMENTOS</span>
          </div>
          <div class="row">
            <div class="col-sm-10">
              <span style="color:#1E2933; font-weight:700; font-size: 1.75em;">Orçamentos Cadastrados</span><br>
              <span class="texto-corpo">Envie, aguarde a aprovação, realize o serviço e gere o recibo.</span>
            </div>
            <div class="col-sm-2">
              <div class="d-flex flex-column h-100">
                <button class="btn btn-custom float-end mt-auto" id="BTN_CRIAR" name="BTN_CRIAR" data-bs-toggle='modal' data-bs-target='#MODAL_BROWSECLIENTES' onclick="javascript:Modal_BrowseClientes();">
                  Criar Orçamento
                </button>
              </div>
            </div>
          </div>
          <form method="post" id="FORCAMENTO_EDITA" name="FORCAMENTO_EDITA" action="orcamento_edita.php">
            <input type="hidden" id="chave_orcamento" name="chave_orcamento" value="">
            <input type="hidden" id="chave_cliente" name="chave_cliente" value="">
            <input type="hidden" id="acao" name="acao" value="NOVO">
            <div class="row mt-3">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col"><span class="texto11">ORÇAMENTO</span></th>
                          <th scope="col"><span class="texto11">CLIENTE</span></th>
                          <th scope="col"><span class="texto11">CADASTRO</span></th>
                          <th scope="col"><span class="texto11">VALOR</span></th>
                          <th class="text-end" scope="col"><span class="texto11">AÇÕES</span></th>
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
                        <td><span class="texto12" style="color:#EA580C"><i class="ci ci-marcador-amarelo"></i>RECUSADO</span></td>
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

                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>
  <!-- FIM DA ÁREA DE CONTEÚDO -->
  <?php include($Raiz . "include/php/rodape.php"); ?>
</body>

</html>
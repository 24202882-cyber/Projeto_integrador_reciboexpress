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
torcamento.caixa_orcamento = 1 
order by torcamento.chave_orcamento desc
";
$qorcamento = $pdo->prepare($strsql);
$qorcamento->bindParam(":vchave_perfil", $chave_perfil);
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
    <a class='link-padrao' href='https://reciboexpress.kraftwork.com.br/publico/pdfs/{$urlpdf_orcamento}'>
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
              <button class="btn btn-sm btn-custom rounded-4" onclick="javascript:alert('teste')">
                Tudo
              </button>
              <button class="btn btn-sm btn-outline-secondary rounded-4" onclick="javascript:alert('teste')">
                Aprovado
              </button>
              <button class="btn btn-sm btn-outline-secondary rounded-4" onclick="javascript:alert('teste')">
                Enviado
              </button>
              <button class="btn btn-sm btn-outline-secondary rounded-4" onclick="javascript:alert('teste')">
                Aguardando
              </button>
              <button class="btn btn-sm btn-outline-secondary rounded-4" onclick="javascript:alert('teste')">
                <i class="ci ci-acao-calendario" style="background-color: #9AA6AE"></i> Período<span class="texto-negrito"> Setembro 2026</span>
              </button>
              <button class="btn btn-sm btn-outline-secondary rounded-4" onclick="javascript:alert('teste')">
                <i class="ci ci-acao-cliente" style="background-color: #9AA6AE"></i> Cliente<span class="texto-negrito"> Todos</span>
              </button>
              <button class="btn btn-sm btn-outline-secondary rounded-4" onclick="javascript:alert('teste')">
                Orçamentos
              </button>
              <button class="btn btn-sm btn-outline-secondary rounded-4" onclick="javascript:alert('teste')">
                Pedidos
              </button>

            </div>
          </div>

          <div class="row mt-3">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <form action="/publico/orcamento/orcamento_edita.php" id="FORCAMENTO_EDITA" name="FORCAMENTO_EDITA" method="POST">
                    <input type="hidden" id="chave_orcamento" name="chave_orcamento" value="">
                    <input type="hidden" id="acao" name="acao" value="EDITAR">
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
        document.getElementById("FORCAMENTO_EDITA").submit();
      });
/*      $("#BTN_INCLUIR").click(function () {
        document.getElementById("chave_cliente").value = "0";
        document.getElementById("FCLIENTE").submit();
      });*/
    });
  </script>
</body>

</html>
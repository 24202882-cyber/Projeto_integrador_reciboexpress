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
include($Raiz . "include/php/header.php");
include($Raiz . "include/php/funcoes.php");
include($Raiz . "conexao/db.php");
VerificaSessao();
$ok = true;
$chave_perfil = $_SESSION["CHAVE_PERFIL"];

$qtd_enviado = 0;
abre_db();
$strsql = "
select
count(1) as qtd_enviado
from
torcamento
where    
torcamento.chave_perfil = :vchave_perfil and
torcamento.situacao_orcamento = 'ENVIADO' and 
torcamento.caixa_orcamento = 1
";
$qorcamento = $pdo->prepare($strsql);
$qorcamento->bindParam(":vchave_perfil", $chave_perfil);
$qorcamento->execute();
if ($torcamento = $qorcamento->fetch(PDO::FETCH_ASSOC)) {
  $qtd_enviado = $torcamento["qtd_enviado"];
}

abre_db();
$html_enviado = "";
$strsql = "
select
torcamento.chave_orcamento
,torcamento.valor_orcamento
,tcliente.nome_cliente
from
torcamento
left join tcliente on torcamento.chave_cliente = tcliente.chave_cliente 
where    
torcamento.chave_perfil = :vchave_perfil and
torcamento.situacao_orcamento = 'ENVIADO' and 
torcamento.caixa_orcamento = 1
";
$qorcamento = $pdo->prepare($strsql);
$qorcamento->bindParam(":vchave_perfil", $chave_perfil);
$qorcamento->execute();
while ($torcamento = $qorcamento->fetch(PDO::FETCH_ASSOC)) {  
  $nome_cliente = $torcamento["nome_cliente"];  
  $chave_orcamento = $torcamento["chave_orcamento"];
  $chave_orcamento_ext = str_pad($chave_orcamento, 6, "0", STR_PAD_LEFT);  
  $valor_orcamento = $torcamento["valor_orcamento"];  
  $valor_orcamento = Float2Moeda(floatval($valor_orcamento), 2);
  if (strlen($nome_cliente) > 15) {
    $nome_cliente = substr($nome_cliente, 0, 15) . '...';
  }
  $html_enviado .= "    
  <div class='row'>
    <div class='col-8'>
      <span class='texto135-negrito'>{$nome_cliente}</span><span class='texto135'> #{$chave_orcamento_ext}</span>
    </div>
    <div class='col-4 text-end'>
      <span class='texto135-negrito'>{$valor_orcamento}</span>
    </div>
  </div>";
}

abre_db();
$html_hoje = "";
$strsql = "
select
torcamento.chave_orcamento
,torcamento.dtc_orcamento
,torcamento.valor_orcamento
,tcliente.nome_cliente
,(select tservico.descr_servico from torcamentosrv left join tservico on torcamentosrv.chave_servico = tservico.chave_servico where torcamento.chave_orcamento = torcamentosrv.chave_orcamento and torcamentosrv.caixa_orcamentosrv = 1 order by torcamentosrv.chave_orcamentosrv limit 1) as descr_servico
from
torcamento
left join tcliente on torcamento.chave_cliente = tcliente.chave_cliente 
where    
torcamento.chave_perfil = :vchave_perfil and
torcamento.dtu_orcamento >= CURDATE() - INTERVAL 3 DAY and 
torcamento.caixa_orcamento = 1
order by dtu_orcamento desc
";
$qorcamento = $pdo->prepare($strsql);
$qorcamento->bindParam(":vchave_perfil", $chave_perfil);
$qorcamento->execute();
while ($torcamento = $qorcamento->fetch(PDO::FETCH_ASSOC)) {  
  $chave_orcamento = $torcamento["chave_orcamento"];
  $chave_orcamento_ext = str_pad($chave_orcamento, 6, "0", STR_PAD_LEFT);  
  $nome_cliente = $torcamento["nome_cliente"];  
  $dtc_orcamento = $torcamento["dtc_orcamento"];  
  $hora = date('H:i', strtotime($dtc_orcamento));
  $periodo = strtoupper(obterPeriodoDoDia($dtc_orcamento));  
  $valor_orcamento = $torcamento["valor_orcamento"];  
  $valor_orcamento = Float2Moeda(floatval($valor_orcamento), 2);
  $descr_servico = $torcamento["descr_servico"];  
  if (strlen($nome_cliente) > 15) {
    $nome_cliente = substr($nome_cliente, 0, 15) . '...';
  }
  $html_hoje .= "  
  <div class='row'>
    <div class='col-3'>
      <span style='color:#163B5C; font-weight: 700; font-size: 0.93em'>{$hora}</span><br>
      <span class='texto-menor4'>{$periodo}</span>
    </div>
    <div class='col-6'>
      <span class='texto-linha-tab'>{$descr_servico}</span><br>
      <span class='texto-corpo'>{$nome_cliente}</span>
    </div>
    <div class='col-3 text-end'>
      <span class='texto-linha-tab'>{$valor_orcamento }</span>
      <i class='ci ci-marcador-amarelo'></i><span style='font-size:0.66em; color:#B4531F; font-weight:700'>A FAZER</span>
    </div>
  </div>";
}

abre_db();
$html_ultimas = "";
$strsql = "
select
torcamento.chave_orcamento
,torcamento.dtc_orcamento
,torcamento.valor_orcamento
,torcamento.situacao_orcamento
,torcamento.dtc_orcamento
,torcamento.dte_orcamento
,torcamento.dta_orcamento
,torcamento.dtrecibo_orcamento
,torcamento.dtu_orcamento
,tcliente.nome_cliente
from
torcamento
left join tcliente on torcamento.chave_cliente = tcliente.chave_cliente 
where    
torcamento.chave_perfil = :vchave_perfil and
torcamento.dtu_orcamento >= CURDATE() - INTERVAL 3 DAY and 
torcamento.caixa_orcamento = 1
order by dtu_orcamento desc
limit 5
";
$qorcamento = $pdo->prepare($strsql);
$qorcamento->bindParam(":vchave_perfil", $chave_perfil);
$qorcamento->execute();
while ($torcamento = $qorcamento->fetch(PDO::FETCH_ASSOC)) {  
  $chave_orcamento = $torcamento["chave_orcamento"];
  $chave_orcamento_ext = str_pad($chave_orcamento, 6, "0", STR_PAD_LEFT);
  $nome_cliente = $torcamento["nome_cliente"];  
  $dtc_orcamento = $torcamento["dtc_orcamento"];  
  $hora = date('H:i', strtotime($dtc_orcamento));
  $periodo = strtoupper(obterPeriodoDoDia($dtc_orcamento));  
  $valor_orcamento = $torcamento["valor_orcamento"];  
  $valor_orcamento = Float2Moeda(floatval($valor_orcamento), 2);  
  $situacao_orcamento = $torcamento["situacao_orcamento"];
  $dte_orcamento = $torcamento["dte_orcamento"];  
  $dta_orcamento = $torcamento["dta_orcamento"];  
  if (strlen($nome_cliente) > 15) {
    $nome_cliente = substr($nome_cliente, 0, 15) . '...';
  }
  if ($situacao_orcamento == "ENVIADO") {
    $html_ultimas .= "<div class='col-12 mb-2'><i class='ci ci-marcador-azul'></i><span class='texto-corpo'>Orçamento #{$chave_orcamento_ext} enviado para {$nome_cliente} em {$dte_orcamento}</span></div>";
  }
  if ($situacao_orcamento == "APROVADO") {
    $html_ultimas .= "<div class='col-12 mb-2'><i class='ci ci-marcador-amarelo'></i><span class='texto-corpo'>Orçamento #{$chave_orcamento_ext} aprovado por {$nome_cliente}</span></div>";
  }
  if ($situacao_orcamento == "REALIZADO") {
    $html_ultimas .= "<div class='col-12 mb-2'><i class='ci ci-marcador-verde'></i><span class='texto-corpo'>{$nome_cliente} confirmou o Orçamento #{$chave_orcamento_ext} em {$dta_orcamento}</span></div>";
  }
  if ($situacao_orcamento == "RECIBO") {
    $html_ultimas .= "<div class='col-12 mb-2'><i class='ci ci-marcador-verde'></i><span class='texto-corpo'>Emitido para Recibo para  Orçamento #{$chave_orcamento_ext} de {$nome_cliente} em {$dta_orcamento}</span></div>";
  }
}

abre_db();
$strsql = "
select
trecibo.*
,tcliente.nome_cliente
from
trecibo
left join tcliente on trecibo.chave_cliente = tcliente.chave_cliente 
where    
trecibo.chave_perfil = :vchave_perfil and
trecibo.dtu_recibo >= CURDATE() - INTERVAL 3 DAY and 
trecibo.caixa_recibo = 1
order by dtu_recibo desc
limit 5
";
$qrecibo = $pdo->prepare($strsql);
$qrecibo->bindParam(":vchave_perfil", $chave_perfil);
$qrecibo->execute();
while ($trecibo = $qrecibo->fetch(PDO::FETCH_ASSOC)) {  
  $chave_recibo = $trecibo["chave_recibo"];
  $chave_recibo_ext = str_pad($chave_recibo, 6, "0", STR_PAD_LEFT);
  $nome_cliente = $trecibo["nome_cliente"];  
  $dtc_recibo = $trecibo["dtc_recibo"];  
  $hora = date('H:i', strtotime($dtc_recibo));
  $periodo = strtoupper(obterPeriodoDoDia($dtc_recibo));  
  $valor_recibo = $trecibo["valor_recibo"];  
  $valor_recibo = Float2Moeda(floatval($valor_recibo), 2);  
  $situacao_recibo = $trecibo["situacao_recibo"];
  $dte_recibo = $trecibo["dte_recibo"];  
  if (strlen($nome_cliente) > 15) {
    $nome_cliente = substr($nome_cliente, 0, 15) . '...';
  }
  if ($situacao_recibo == "CONFIRMADO") {
    $html_ultimas .= "<div class='col-12 mb-2'><i class='ci ci-marcador-verde'></i><span class='texto-corpo'>{$nome_cliente} confirmou o Recibo #{$chave_recibo_ext} em {$dta_recibo}</span></div>";
  }
}

$no_mes_de = "NO MÊS DE " . mb_strtoupper(getNomeMesArray(date('Y/m/d')));
$total_pago = 0;
$strsql = "
select
sum(trecibo.valor_recibo) as total_pago
from
trecibo
where    
trecibo.chave_perfil = :vchave_perfil and
trecibo.situacao_recibo = 'PAGO' and 
/*CURDATE() IN (trecibo.dtc_recibo, trecibo.dte_recibo, trecibo.dta_recibo, trecibo.dtrecusado_recibo) and */
trecibo.caixa_recibo = 1
";
$qrecibo = $pdo->prepare($strsql);
$qrecibo->bindParam(":vchave_perfil", $chave_perfil);
$qrecibo->execute();
if ($trecibo = $qrecibo->fetch(PDO::FETCH_ASSOC)) {  
  $total_pago = $trecibo["total_pago"];    
}

abre_db();
$qtd_trabalhos = 0;
$strsql = "
select
count(1) as qtd_trabalho
from
torcamento
where    
torcamento.chave_perfil = :vchave_perfil and
torcamento.situacao_orcamento in ('REALIZADO','ORCAMENTO') and 
/*CURDATE() IN (torcamento.dtc_orcamento, torcamento.dte_orcamento, torcamento.dta_orcamento, torcamento.dtrecusado_orcamento) and */
torcamento.caixa_orcamento = 1
";
$qorcamento = $pdo->prepare($strsql);
$qorcamento->bindParam(":vchave_perfil", $chave_perfil);
$qorcamento->execute();
if ($torcamento = $qorcamento->fetch(PDO::FETCH_ASSOC)) {  
  $qtd_trabalhos = $torcamento["qtd_trabalho"];    
}

/*CURDATE() IN (torcamento.dtc_orcamento, torcamento.dte_orcamento, torcamento.dta_orcamento, torcamento.dtrecusado_orcamento) and */

$qtd_aprovado = 0;
$strsql = "
select
count(1) as qtd_aprovado
from
torcamento
where    
torcamento.chave_perfil = :vchave_perfil and
torcamento.situacao_orcamento = 'APROVADO' and 
torcamento.caixa_orcamento = 1
";
$qorcamento = $pdo->prepare($strsql);
$qorcamento->bindParam(":vchave_perfil", $chave_perfil);
$qorcamento->execute();
if ($torcamento = $qorcamento->fetch(PDO::FETCH_ASSOC)) {  
  $qtd_aprovado = $torcamento["qtd_aprovado"];    
}

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
                  <span class="texto-pirulito float-end"><a href="/publico/historico" class="link-padrao">VER AGENDA</a></span>              
                </div>
              </div>
              <div class="card border-0 shadow-sm p-3">
                <?php echo $html_hoje; ?>
                <!--
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
                </div>-->
              </div>
            </div>
            <div class="col-4">
              <div class="card border-1 shadow-sm p-3 mb-3" style="border-color:#F1D3C4; background-color:#FBEDE6">
                <div class="row">
                  <div class="col-12 mb-2">
                    <div class="d-flex align-items-center gap-2">                      
                      <div class="bg-white p-2 rounded shadow-sm">
                        <i class="ci ci-status-relogio"></i>
                      </div>                      
                      <span class="texto15"><?php echo $qtd_enviado ?> aguardando confirmação</span>
                    </div>
                  </div>
                </div>
                <?php echo $html_enviado; ?>
                <!--
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
                -->
              </div>              
              <div class="card border-0 shadow-sm p-3">
                <div class="col-12 mb-3">
                  <span class="texto-pirulito"><?php echo $no_mes_de ?></span>
                </div>
                <div class="col-12">
                  <div class="text-nowrap">
                    <span class="align-middle texto-regular texto-negrito">R$</span>
                    <span class="align-middle" style="color:# 1E2933; font-size:2em; font-weight: 700;"><?php echo Float2Moeda(floatval($total_pago), 0); ?></span>
                  </div>
                  <span class="texto-pirulito">RECEBIDOS</span>                  
                </div>
                <div class="col-12">
                  <div class="row border-top pt-3 mt-3">
                    <div class="col-4">
                      <span class="texto-preto texto-negrito" style="font-size:1.06em"><?php echo $qtd_trabalhos ?></span><br>
                      <span class="texto-pirulito text-nowrap">TRABALHOS</span>
                    </div>
                    <div class="col-4">
                      <span class="texto-amarelo texto-negrito" style="font-size:1.06em"><?php echo $qtd_enviado ?></span><br>
                      <span class="texto-pirulito text-nowrap">AGUARDANDO</span>                      
                    </div>
                    <div class="col-4">
                      <span class="texto-verde texto-negrito" style="font-size:1.06em"><?php echo $qtd_aprovado ?></span><br>
                      <span class="texto-pirulito text-nowrap">CONFIRMADOS</span>                      
                    </div>
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
                  <?php echo $html_ultimas; ?>
<!--
                  <div class="col-12 mb-2">
                    <i class="ci ci-marcador-verde"></i><span class="texto-corpo">Mariana confirmou o recibo #0037 · há 2 h</span>                    
                  </div>
                  <div class="col-12 border-top pt-2 mb-2">
                    <i class="ci ci-marcador-azul"></i><span class="texto-corpo">Recibo #0038 enviado para Mariana pelo WhatsApp. ontem</span>
                  </div>
                  <div class="col-12 border-top pt-2">
                    <i class="ci ci-marcador-amarelo"></i><span class="texto-corpo">Orçamento aprovado por Carlos Almeida. · 12 SET</span>
                  </div>
-->                  
                </div>
              </div>
            </div>
            <div class="col-4">
            </div>
          </div>
        <!-- ÁREA DE CONTEÚDO (Insira o conteúdo específico de cada página aqui) -->
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
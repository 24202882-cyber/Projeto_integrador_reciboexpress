<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /publico/orcamento/modal_servicos.php
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
$qtdlimite = 20;
$qtdregpagina = 0;
$qtdreg = 0;
$qtdregtotal = 0;
$apagina = array();
$pagina = 1;
$qtdpagina = 0;
$paginainicial = 1;
$paginafinal = 1;
$html = "";
$procurar = "";
$procurar_exceto = "";
$ordem = "";
$ordempos = "";
$pesq = "";
$pg = "";
$reset = "";
if (isset($_GET["PESQ"])) {
  $pesq = $_GET["PESQ"];
}
if (isset($_GET["PG"])) {
  $pagina = $_GET["PG"];
}
else {
  if (isset($_SESSION["CURRENTPAGE_SERVICO"])) {
    if ($_SESSION["CURRENTPAGE_SERVICO"] != "") {
      $pagina = $_SESSION["CURRENTPAGE_SERVICO"];
    }
  }
}
if (isset($_GET["ORDEM"])) {
  $ordem = $_GET["ORDEM"];
}
if (isset($_GET["RESET"])) {
  $reset = $_GET["RESET"];
}
if ($reset == "SIM") {
  $pesq = "";
  $_SESSION["SEARCHSTRING_SERVICO"] = $pesq;
  $pagina = "1";
}
if ($pesq != "") {
  $_SESSION["SEARCHSTRING_SERVICO"] = $pesq;
}
$chave_perfil = $_SESSION["CHAVE_PERFIL"];

//********************
//********************
// RESET
//********************
//********************
if ($reset == "SIM") {
  $_SESSION["FIRSTPAGE_SERVICO"] = "0";
  $_SESSION["LASTPAGE_SERVICO"] = "0";
  $_SESSION["CURRENTPAGE_SERVICO"] = "1";
  $_SESSION["SEARCHSTRING_SERVICO"] = "";
  $_SESSION["SEARCHEXCETO_SERVICO"] = "";
  $_SESSION["ORDEM_SERVICO"] = "DESCR_SERVICO";
  $_SESSION["ORDEMPOS_SERVICO"] = "";
  $_SESSION["CAIXA_SERVICO"] = 1;
}
//********************
//********************
// EOF RESET
//********************
//********************

// Controle de paginação
if (!isset($_SESSION["FIRSTPAGE_SERVICO"])) {
  $_SESSION["FIRSTPAGE_SERVICO"] = "0";
  $_SESSION["LASTPAGE_SERVICO"] = "0";
  $_SESSION["CURRENTPAGE_SERVICO"] = "1";
  $_SESSION["SEARCHSTRING_SERVICO"] = "";
  $_SESSION["SEARCHEXCETO_SERVICO"] = "";
  $_SESSION["ORDEM_SERVICO"] = "DESCR_SERVICO";
  $_SESSION["ORDEMPOS_SERVICO"] = "";
}
if (isset($_POST["PROCURAR"])) {
  if ($_POST["PROCURAR"] != "") {
    $procurar = $_POST["PROCURAR"];
    $pagina = 1;
    $_SESSION["SEARCHSTRING_SERVICO"] = $procurar;
  }
}
$procurar = $_SESSION["SEARCHSTRING_SERVICO"];
if (isset($_POST['PROCURAR_EXCETO'])) {
  if ($_POST['PROCURAR_EXCETO'] != "") {
    $procurar_exceto = $_POST['PROCURAR_EXCETO'];
    $pagina = 1;
    $_SESSION["SEARCHEXCETO_SERVICO"] = $procurar_exceto;
  }
}
$procurar_exceto = $_SESSION["SEARCHEXCETO_SERVICO"];
if (isset($_GET['ORDEM'])) {
  $ordem = strtolower($_GET['ORDEM']);
  if ($ordem != $_SESSION["ORDEM_SERVICO"]) {
    $_SESSION["ORDEM_SERVICO"] = $ordem;
    $_SESSION["ORDEMPOS_SERVICO"] = "";
  } else {
    if ($_SESSION["ORDEMPOS_SERVICO"] == "") {
      $_SESSION["ORDEMPOS_SERVICO"] = "desc";
    } else {
      $_SESSION["ORDEMPOS_SERVICO"] = "";
    }
  }
}
if (isset($_GET['ORDEMPOS'])) {
  $ordempos = strtolower($_GET['ORDEMPOS']);
  if ($ordempos != $_SESSION["ORDEMPOS_SERVICO"]) {
    $_SESSION["ORDEMPOS_SERVICO"] = $ordempos;
  }
}
$ordem = $_SESSION["ORDEM_SERVICO"];
$ordempos = $_SESSION["ORDEMPOS_SERVICO"];

//********************
//********************
// CAIXA
//********************
//********************
$caixa_servico = 1;
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if ($_POST["CAIXA_SERVICO"] != "") {
    if (strval($_POST["CAIXA_SERVICO"]) != strval($_SESSION["CAIXA_SERVICO"])) {
      $pagina = 1;
    }
    $caixa_servico = intval($_POST["CAIXA_SERVICO"]);
    $_SESSION["CAIXA_SERVICO"] = $caixa_servico;
  }
}
if (isset($_SESSION["CAIXA_SERVICO"])) {
  $caixa_servico = intval($_SESSION["CAIXA_SERVICO"]);
} else {
  $_SESSION["CAIXA_SERVICO"] = $caixa_servico;
}
//********************
//********************
// EOF CAIXA
//********************
//********************

// EOF Controle de paginação

abre_db();

// Captura de paginação
$strsql = "
select 
sum(1) as qtdreg
from 
tservico
where 
tservico.chave_perfil = {$chave_perfil} and 
tservico.caixa_servico = {$caixa_servico}
";
$qservico = $pdo->prepare($strsql);
$qservico->execute();
if ($tservico = $qservico->fetch(PDO::FETCH_ASSOC)) {
  $qtdregtotal = $tservico["qtdreg"];
}
$strsql = "
select 
sum(1) as qtdreg_filtragem 
from 
tservico
where 
tservico.chave_perfil = {$chave_perfil} and 
";
if ($procurar != "") {
  $strsql = $strsql . (($procurar_exceto != "1") ? " " : " not ") .
    "(
    tservico.descr_servico like '%{$procurar}%'
    ) and ";
}
$strsql .= "
tservico.caixa_servico = " . $caixa_servico;
$qservico = $pdo->prepare($strsql);
$qservico->execute();
if ($tservico = $qservico->fetch(PDO::FETCH_ASSOC)) {
  $qtdreg = $tservico['qtdreg_filtragem'];
  $qtdpaginaresto = round($qtdreg / $qtdlimite, 2);
  $qtdpagina = floor($qtdreg / $qtdlimite);
  if ($qtdpaginaresto - $qtdpagina > 0) {
    $qtdpagina = floor($qtdpagina) + 1;
  }
  if ($qtdreg > 0) {
    for ($i = 0; $i <= $qtdpagina - 1; $i++) {
      array_push($apagina, $i + 1);
    }
  }
  $paginado = false;
  if (empty($pagina)) {
    $pagina = '1';
    $_SESSION["FIRSTPAGE_SERVICO"] = "0";
    $_SESSION["LASTPAGE_SERVICO"] = "0";
  }
  if ($pagina == 'FIRST') {
    $pagina = '1';
  }
  if ($pagina == 'LAST') {
    $pagina = $qtdpagina;
  }
  $pagina = intval($pagina);
  $paginainicial = $_SESSION["FIRSTPAGE_SERVICO"];
  $paginafinal = $_SESSION["LASTPAGE_SERVICO"];
  // Início padrão, sem página informada
  if (intval($paginainicial) <= 0 || $pagina == 1) {
    $paginainicial = 1;
    $paginafinal = ($paginainicial + 5) - 1;
    $_SESSION["FIRSTPAGE_SERVICO"] = $paginainicial;
    $_SESSION["LASTPAGE_SERVICO"] = $paginafinal;
    $paginado = true;
  }
  // EOF Início padrão, sem página informada
  if (!$paginado) {
    if ($pagina > $paginainicial && $pagina < $paginafinal) {
      $paginado = true;
    }
  }
  if (!$paginado) {
    if ($pagina == $paginainicial) {
      $paginainicial = $paginainicial - 1;
      $paginafinal = $paginafinal - 1;
      if ($paginainicial < 1) {
        $paginainicial = 1;
        $paginafinal = ($paginainicial + 5) - 1;
      }
      $_SESSION["FIRSTPAGE_SERVICO"] = $paginainicial;
      $_SESSION["LASTPAGE_SERVICO"] = $paginafinal;
      $paginado = true;
    }
  }
  if (!$paginado) {
    if ($pagina == $paginafinal) {
      $paginainicial = $paginainicial + 1;
      $paginafinal = $paginafinal + 1;
      $_SESSION["FIRSTPAGE_SERVICO"] = $paginainicial;
      $_SESSION["LASTPAGE_SERVICO"] = $paginafinal;
      $paginado = true;
    }
  }
  if (!$paginado) {
    if ($pagina == $qtdpagina) {
      $paginafinal = $qtdpagina;
      $paginainicial = ($paginafinal - 5) + 1;
      $_SESSION["FIRSTPAGE_SERVICO"] = $paginainicial;
      $_SESSION["LASTPAGE_SERVICO"] = $paginafinal;
      $paginado = true;
    }
  }
  $_SESSION["CURRENTPAGE_SERVICO"] = $pagina;
}
// EOF Captura de paginação

//********************
//********************
//**** Browse
//********************
//********************
$strsql = "
select
tservico.chave_servico
,tservico.descr_servico
,tservico.cobranca_servico
,tservico.valor_servico
from
tservico
where
tservico.chave_perfil = {$chave_perfil} and 
";
if ($procurar != "") {
  $strsql = $strsql . (($procurar_exceto != "1") ? " " : " not ") .
    "(
    tservico.descr_servico like '%{$procurar}%'    
    ) and ";
}
$strsql .= "
tservico.caixa_servico = {$caixa_servico}
order by {$ordem} " . ($ordempos == "desc" ? " " . $ordempos : $ordempos) . "
limit {$qtdlimite}
";
if (intval($pagina) > 1) {
  $offset = ((intval($pagina) - 1) * 20);
  $strsql = $strsql . ' offset ' . $offset;
}
$qservico = $pdo->prepare($strsql);
$qservico->execute();
$contetq = 0;
$etq = '';
$qtdregpagina = 0;
while ($tservico = $qservico->fetch(PDO::FETCH_ASSOC)) {
  $chave_servico = $tservico["chave_servico"];
  $descr_servico = $tservico["descr_servico"];
  $cobranca_servico = $tservico["cobranca_servico"];
  $valor_servico = $tservico["valor_servico"];
  //$dtc_servico = formatadata($tservico["dtc_servico"], "d/m/Y H:i");
  $etq .= '              <tr>' . "\n";
  $etq .= '                <td data-id_dbg="' . $chave_servico . '" class="SELECIONA_LINHA">' . str_pad($chave_servico, 6, "0", STR_PAD_LEFT) . '</td>' . "\n";
  $etq .= '                <td data-id_dbg="' . $chave_servico . '" class="SELECIONA_LINHA">' . $descr_servico . '</td>' . "\n";
  $etq .= '                <td data-id_dbg="' . $chave_servico . '" class="SELECIONA_LINHA">' . $cobranca_servico . '</td>' . "\n";
  $etq .= '                <td class="text-end" data-id_dbg="' . $chave_servico . '" class="SELECIONA_LINHA">' . $valor_servico . '</td>' . "\n";
  $etq .= '              </tr>' . "\n";
  $contetq = $contetq + 1;
  $qtdregpagina = $qtdregpagina + 1;
  if ($contetq == 4) {
    $contetq = 0;
  }
}

// Filtro
$html .= '      <div class="row g-3 align-items-center">' . "\n";
$html .= '        <div class="col">' . "\n";
$html .= '        <div class="input-group input-group-sm mb-3">' . "\n";
$html .= '          <span class="input-group-text input-sm" id="basic-addon1">Filtrar</span>' . "\n";
$html .= '          <input type="text" class="form-control" id="MEUSSRVPESQ" name="MEUSSRVPESQ" placeholder="Digite a informação do servico que deseja procurar" onkeyup="javascript:avaliatecla_pesquisaservico(event);" aria-label="MEUSSRVPESQ" aria-describedby="basic-addon1">' . "\n";
$cl_filtro_redef = " d-none";
if (trim($_SESSION["SEARCHSTRING_SERVICO"]) != "") {
  $cl_filtro_redef = "";
}
$html .= '          <button type="button" class="btn btn-sm btn-warning' . $cl_filtro_redef . '" id="BTN_FILTRO_REDEF" name="BTN_FILTRO_REDEF" onclick="javascript:Modal_BrowseServicos_Reshow(\'RESET\');">Redefinir</button>' . "\n";
$html .= '          <button type="button" class="btn btn-sm btn-primary" id="BTN_FILTRO_OK" name="BTN_FILTRO_OK" onclick="javascript:Modal_BrowseServicos_Reshow(\'\');">Ok</button>' . "\n";
$html .= '        </div>' . "\n";
$html .= '        </div>' . "\n";
$html .= '      </div>' . "\n";
// EOF Filtro
// Corpo
if ($etq != "") {
  $html .= '          <table class="table table-hover table-sm mb-0">' . "\n";
  $html .= '            <thead class="thead-light">' . "\n";
  $html .= '              <tr>' . "\n";
  $html .= '                <th><a href="' . $Raiz . 'sistema/servico/servico.php?ORDEM=CHAVE_SERVICO">Chave</a></td>' . "\n";
  $html .= '                <th><a href="' . $Raiz . 'sistema/servico/servico.php?ORDEM=DESCR_SERVICO">Descrição</a></td>' . "\n";
  $html .= '                <th><a href="' . $Raiz . 'sistema/servico/servico.php?ORDEM=COBRANCA_SERVICO">Tipo Cobrança</a></td>' . "\n";
  $html .= '                <th class="text-end"><a href="' . $Raiz . 'sistema/servico/servico.php?ORDEM=VALOR_SERVICO">Valor</a></td>' . "\n";
  $html .= '              </tr>' . "\n";
  $html .= '            </thead>' . "\n";
  $html .= '            <tbody>' . "\n";
  $html .= $etq . "\n";
  $html .= '            </tbody>' . "\n";
  $html .= '          </table>' . "\n";
}
// EOF Corpo
// Botões de paginação inferior
if ($qtdpagina > 0 && ($qtdreg > $qtdlimite)) {
  $html .= '<br>' . "\n";
  $html .= '            <div class="col mb-2">' . "\n";
  $html .= '              <nav aria-label="Páginas de navegação">' . "\n";
  $html .= '                <ul class="pagination pagination-sm mb-0">' . "\n";
  $html .= '                  <li class="page-item"><a class="page-link" onclick=\'javascript:Modal_BrowseServicos_Reshow("FIRST")\'">&laquo;</a></li>' . "\n";
  if ($qtdpagina > 0) {
    $cont_apagina = count($apagina);
    for ($i = 0; $i <= $cont_apagina - 1; $i++) {
      if (($i + 1) >= $paginainicial && ($i + 1) <= $paginafinal) {
        if (($i + 1) == intval($pagina)) {
          $html .= '                  <li class="page-item active"><a class="page-link" href="#" onclick="Modal_BrowseServicos_Reshow(' . ($i + 1) . ')">' . $apagina[$i] . '</a></li>' . "\n";
        } else {
          $html .= '                  <li class="page-item"><a class="page-link" href="#" onclick="Modal_BrowseServicos_Reshow(' . ($i + 1) . ')">' . $apagina[$i] . '</a></li>' . "\n";
        }
      }
    }
  }
  $html .= '                  <li class="page-item"><a class="page-link" onclick=\'javascript:Modal_BrowseServicos_Reshow("LAST")\'">&raquo;</a></li>' . "\n";
  $html .= '                </ul>' . "\n";
  $html .= '              </nav>' . "\n";
  $html .= '            </div>' . "\n";
}
// EOF Botões de paginação inferior
echo $html;
?>
<script type="text/javascript">
  $(document).ready(function () {
    $(".SELECIONA_LINHA").click(function () {
      var vchave_servico = $(this).data("id_dbg");
      $("#MODAL_BROWSESERVICOS").modal("hide");
      $("#chave_servico").val(vchave_servico);
      Pesquisa_Servico();
    });
  });
</script>
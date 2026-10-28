<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /publico/orcamento/modal_clientes.php
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
  if (isset($_SESSION["CURRENTPAGE_CLIENTE"])) {
    if ($_SESSION["CURRENTPAGE_CLIENTE"] != "") {
      $pagina = $_SESSION["CURRENTPAGE_CLIENTE"];
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
  $_SESSION["SEARCHSTRING_CLIENTE"] = $pesq;
  $pagina = "1";
}
if ($pesq != "") {
  $_SESSION["SEARCHSTRING_CLIENTE"] = $pesq;
}
$chave_perfil = $_SESSION["CHAVE_PERFIL"];

//********************
//********************
// RESET
//********************
//********************
if ($reset == "SIM") {
  $_SESSION["FIRSTPAGE_CLIENTE"] = "0";
  $_SESSION["LASTPAGE_CLIENTE"] = "0";
  $_SESSION["CURRENTPAGE_CLIENTE"] = "1";
  $_SESSION["SEARCHSTRING_CLIENTE"] = "";
  $_SESSION["SEARCHEXCETO_CLIENTE"] = "";
  $_SESSION["ORDEM_CLIENTE"] = "NOME_CLIENTE";
  $_SESSION["ORDEMPOS_CLIENTE"] = "";
  $_SESSION["CAIXA_CLIENTE"] = 1;
}
//********************
//********************
// EOF RESET
//********************
//********************

// Controle de paginação
if (!isset($_SESSION["FIRSTPAGE_CLIENTE"])) {
  $_SESSION["FIRSTPAGE_CLIENTE"] = "0";
  $_SESSION["LASTPAGE_CLIENTE"] = "0";
  $_SESSION["CURRENTPAGE_CLIENTE"] = "1";
  $_SESSION["SEARCHSTRING_CLIENTE"] = "";
  $_SESSION["SEARCHEXCETO_CLIENTE"] = "";
  $_SESSION["ORDEM_CLIENTE"] = "NOME_CLIENTE";
  $_SESSION["ORDEMPOS_CLIENTE"] = "";
}
if (isset($_POST["PROCURAR"])) {
  if ($_POST["PROCURAR"] != "") {
    $procurar = $_POST["PROCURAR"];
    $pagina = 1;
    $_SESSION["SEARCHSTRING_CLIENTE"] = $procurar;
  }
}
$procurar = $_SESSION["SEARCHSTRING_CLIENTE"];
if (isset($_POST['PROCURAR_EXCETO'])) {
  if ($_POST['PROCURAR_EXCETO'] != "") {
    $procurar_exceto = $_POST['PROCURAR_EXCETO'];
    $pagina = 1;
    $_SESSION["SEARCHEXCETO_CLIENTE"] = $procurar_exceto;
  }
}
$procurar_exceto = $_SESSION["SEARCHEXCETO_CLIENTE"];
if (isset($_GET['ORDEM'])) {
  $ordem = strtolower($_GET['ORDEM']);
  if ($ordem != $_SESSION["ORDEM_CLIENTE"]) {
    $_SESSION["ORDEM_CLIENTE"] = $ordem;
    $_SESSION["ORDEMPOS_CLIENTE"] = "";
  } else {
    if ($_SESSION["ORDEMPOS_CLIENTE"] == "") {
      $_SESSION["ORDEMPOS_CLIENTE"] = "desc";
    } else {
      $_SESSION["ORDEMPOS_CLIENTE"] = "";
    }
  }
}
if (isset($_GET['ORDEMPOS'])) {
  $ordempos = strtolower($_GET['ORDEMPOS']);
  if ($ordempos != $_SESSION["ORDEMPOS_CLIENTE"]) {
    $_SESSION["ORDEMPOS_CLIENTE"] = $ordempos;
  }
}
$ordem = $_SESSION["ORDEM_CLIENTE"];
$ordempos = $_SESSION["ORDEMPOS_CLIENTE"];

//********************
//********************
// CAIXA
//********************
//********************
$caixa_cliente = 1;
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if ($_POST["CAIXA_CLIENTE"] != "") {
    if (strval($_POST["CAIXA_CLIENTE"]) != strval($_SESSION["CAIXA_CLIENTE"])) {
      $pagina = 1;
    }
    $caixa_cliente = intval($_POST["CAIXA_CLIENTE"]);
    $_SESSION["CAIXA_CLIENTE"] = $caixa_cliente;
  }
}
if (isset($_SESSION["CAIXA_CLIENTE"])) {
  $caixa_cliente = intval($_SESSION["CAIXA_CLIENTE"]);
} else {
  $_SESSION["CAIXA_CLIENTE"] = $caixa_cliente;
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
tcliente
where 
tcliente.chave_perfil = {$chave_perfil} and 
tcliente.caixa_cliente = {$caixa_cliente}
";
$qcliente = $pdo->prepare($strsql);
$qcliente->execute();
if ($tcliente = $qcliente->fetch(PDO::FETCH_ASSOC)) {
  $qtdregtotal = $tcliente["qtdreg"];
}
$strsql = "
select 
sum(1) as qtdreg_filtragem 
from 
tcliente
where 
tcliente.chave_perfil = {$chave_perfil} and 
";
if ($procurar != "") {
  $strsql = $strsql . (($procurar_exceto != "1") ? " " : " not ") .
    "(
    tcliente.nome_cliente like '%{$procurar}%' or
    tcliente.email_cliente like '%{$procurar}%'
    ) and ";
}
$strsql .= "
tcliente.caixa_cliente = " . $caixa_cliente;
$qcliente = $pdo->prepare($strsql);
$qcliente->execute();
if ($tcliente = $qcliente->fetch(PDO::FETCH_ASSOC)) {
  $qtdreg = $tcliente['qtdreg_filtragem'];
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
    $_SESSION["FIRSTPAGE_CLIENTE"] = "0";
    $_SESSION["LASTPAGE_CLIENTE"] = "0";
  }
  if ($pagina == 'FIRST') {
    $pagina = '1';
  }
  if ($pagina == 'LAST') {
    $pagina = $qtdpagina;
  }
  $pagina = intval($pagina);
  $paginainicial = $_SESSION["FIRSTPAGE_CLIENTE"];
  $paginafinal = $_SESSION["LASTPAGE_CLIENTE"];
  // Início padrão, sem página informada
  if (intval($paginainicial) <= 0 || $pagina == 1) {
    $paginainicial = 1;
    $paginafinal = ($paginainicial + 5) - 1;
    $_SESSION["FIRSTPAGE_CLIENTE"] = $paginainicial;
    $_SESSION["LASTPAGE_CLIENTE"] = $paginafinal;
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
      $_SESSION["FIRSTPAGE_CLIENTE"] = $paginainicial;
      $_SESSION["LASTPAGE_CLIENTE"] = $paginafinal;
      $paginado = true;
    }
  }
  if (!$paginado) {
    if ($pagina == $paginafinal) {
      $paginainicial = $paginainicial + 1;
      $paginafinal = $paginafinal + 1;
      $_SESSION["FIRSTPAGE_CLIENTE"] = $paginainicial;
      $_SESSION["LASTPAGE_CLIENTE"] = $paginafinal;
      $paginado = true;
    }
  }
  if (!$paginado) {
    if ($pagina == $qtdpagina) {
      $paginafinal = $qtdpagina;
      $paginainicial = ($paginafinal - 5) + 1;
      $_SESSION["FIRSTPAGE_CLIENTE"] = $paginainicial;
      $_SESSION["LASTPAGE_CLIENTE"] = $paginafinal;
      $paginado = true;
    }
  }
  $_SESSION["CURRENTPAGE_CLIENTE"] = $pagina;
}
// EOF Captura de paginação

//********************
//********************
//**** Browse
//********************
//********************
$strsql = "
select
tcliente.chave_cliente
,tcliente.nome_cliente
,tcliente.email_cliente
,tcliente.dtc_cliente
from
tcliente
where
tcliente.chave_perfil = {$chave_perfil} and 
";
if ($procurar != "") {
  $strsql = $strsql . (($procurar_exceto != "1") ? " " : " not ") .
    "(
    tcliente.nome_cliente like '%{$procurar}%' or
    tcliente.email_cliente like '%{$procurar}%'
    ) and ";
}
$strsql .= "
tcliente.caixa_cliente = {$caixa_cliente}
order by {$ordem} " . ($ordempos == "desc" ? " " . $ordempos : $ordempos) . "
limit {$qtdlimite}
";
if (intval($pagina) > 1) {
  $offset = ((intval($pagina) - 1) * 20);
  $strsql = $strsql . ' offset ' . $offset;
}
$qcliente = $pdo->prepare($strsql);
$qcliente->execute();
$contetq = 0;
$etq = '';
$qtdregpagina = 0;
while ($tcliente = $qcliente->fetch(PDO::FETCH_ASSOC)) {
  $chave_cliente = $tcliente["chave_cliente"];
  $nome_cliente = $tcliente["nome_cliente"];
  $email_cliente = $tcliente["email_cliente"];
  $dtc_cliente = formatadata($tcliente["dtc_cliente"], "d/m/Y H:i");
  $etq .= '              <tr>' . "\n";
  $etq .= '                <td data-id_dbg="' . $chave_cliente . '" class="SELECIONA_LINHA">' . str_pad($chave_cliente, 6, "0", STR_PAD_LEFT) . '</td>' . "\n";
  $etq .= '                <td data-id_dbg="' . $chave_cliente . '" class="SELECIONA_LINHA">' . $nome_cliente . '</td>' . "\n";
  $etq .= '                <td data-id_dbg="' . $chave_cliente . '" class="SELECIONA_LINHA">' . $email_cliente . '</td>' . "\n";
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
$html .= '          <input type="text" class="form-control" id="MEUSCLIPESQ" name="MEUSCLIPESQ" placeholder="Digite a informação do cliente que deseja procurar" onkeyup="javascript:avaliatecla_pesquisacliente(event);" aria-label="MEUSCLIPESQ" aria-describedby="basic-addon1">' . "\n";
$cl_filtro_redef = " d-none";
if (trim($_SESSION["SEARCHSTRING_CLIENTE"]) != "") {
  $cl_filtro_redef = "";
}
$html .= '          <button type="button" class="btn btn-sm btn-warning' . $cl_filtro_redef . '" id="BTN_FILTRO_REDEF" name="BTN_FILTRO_REDEF" onclick="javascript:Modal_BrowseClientes_Reshow(\'RESET\');">Redefinir</button>' . "\n";
$html .= '          <button type="button" class="btn btn-sm btn-primary" id="BTN_FILTRO_OK" name="BTN_FILTRO_OK" onclick="javascript:Modal_BrowseClientes_Reshow(\'\');">Ok</button>' . "\n";
$html .= '        </div>' . "\n";
$html .= '        </div>' . "\n";
$html .= '      </div>' . "\n";
// EOF Filtro
// Corpo
if ($etq != "") {
  $html .= '          <table class="table table-hover table-sm mb-0">' . "\n";
  $html .= '            <thead class="thead-light">' . "\n";
  $html .= '              <tr>' . "\n";
  $html .= '                <th><a href="' . $Raiz . 'sistema/cliente/cliente.php?ORDEM=CHAVE_CLIENTE">Chave</a></td>' . "\n";
  $html .= '                <th><a href="' . $Raiz . 'sistema/cliente/cliente.php?ORDEM=NOME_CLIENTE">Razão Social</a></td>' . "\n";
  $html .= '                <th><a href="' . $Raiz . 'sistema/cliente/cliente.php?ORDEM=FANTASIA_CLIENTE">Nome Fantasia</a></td>' . "\n";
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
  $html .= '                  <li class="page-item"><a class="page-link" onclick=\'javascript:Modal_BrowseClientes_Reshow("FIRST")\'">&laquo;</a></li>' . "\n";
  if ($qtdpagina > 0) {
    $cont_apagina = count($apagina);
    for ($i = 0; $i <= $cont_apagina - 1; $i++) {
      if (($i + 1) >= $paginainicial && ($i + 1) <= $paginafinal) {
        if (($i + 1) == intval($pagina)) {
          $html .= '                  <li class="page-item active"><a class="page-link" href="#" onclick="Modal_BrowseClientes_Reshow(' . ($i + 1) . ')">' . $apagina[$i] . '</a></li>' . "\n";
        } else {
          $html .= '                  <li class="page-item"><a class="page-link" href="#" onclick="Modal_BrowseClientes_Reshow(' . ($i + 1) . ')">' . $apagina[$i] . '</a></li>' . "\n";
        }
      }
    }
  }
  $html .= '                  <li class="page-item"><a class="page-link" onclick=\'javascript:Modal_BrowseClientes_Reshow("LAST")\'">&raquo;</a></li>' . "\n";
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
      var vchave_cliente = $(this).data("id_dbg");
      $("#MODAL_BROWSECLIENTES").modal("hide");
      $("#chave_cliente").val(vchave_cliente);
      Pesquisa_Cliente();
    });
  });
</script>
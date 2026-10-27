<?php
/*

*/
session_start();
$Raiz = "../../";
include($Raiz . "include/php/header.php"); // Configurações HEADER
include($Raiz . "include/php/funcoes.php"); // Funcoes de Usuário PHP
include($Raiz . "conexao/db.php");
VerificaSessao();
VerificaAdmin($Raiz);

//********************
//********************
// VARIÁVEIS DE INICIALIZAÇÃO
//********************
//********************
$pagina_titulo = "DBU SQL Server";
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
$link_main = $Raiz . "admin/autoexec/autoexec.php";
//********************
//********************
// EOF - VARIÁVEIS DE INICIALIZAÇÃO
//********************
//********************

//********************
//********************
// RESET
//********************
//********************
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($_POST["RESET_AUTOEXEC"] == "SIM") {
        $_SESSION["FIRSTPAGE_AUTOEXEC"] = "0";
        $_SESSION["LASTPAGE_AUTOEXEC"] = "0";
        $_SESSION["CURRENTPAGE_AUTOEXEC"] = "1";
        $_SESSION["SEARCHSTRING_AUTOEXEC"] = "";
        $_SESSION["SEARCHEXCETO_AUTOEXEC"] = "";
        $_SESSION["ORDEM_AUTOEXEC"] = "DESCR_AUTOEXEC";
        $_SESSION["ORDEMPOS_AUTOEXEC"] = "";
        $_SESSION["CAIXA_AUTOEXEC"] = 'CADASTRADO';
        header("Location:" . $Raiz . "sistema/autoexec/autoexec.php");
        die();
    }
}
//********************
//********************
// EOF RESET
//********************
//********************

//********************
//********************
// CONTROLE DE PAGINAÇÃO
//********************
//********************
if (!isset($_SESSION["FIRSTPAGE_AUTOEXEC"])) {
    $_SESSION["FIRSTPAGE_AUTOEXEC"] = "0";
    $_SESSION["LASTPAGE_AUTOEXEC"] = "0";
    $_SESSION["CURRENTPAGE_AUTOEXEC"] = "1";
    $_SESSION["SEARCHSTRING_AUTOEXEC"] = "";
    $_SESSION["SEARCHEXCETO_AUTOEXEC"] = "";
    $_SESSION["ORDEM_AUTOEXEC"] = "DESCR_AUTOEXEC";
    $_SESSION["ORDEMPOS_AUTOEXEC"] = "";
}
if (isset($_GET['PG'])) {
    $pagina = $_GET['PG'];
} else {
    if ($_SESSION["CURRENTPAGE_AUTOEXEC"] != "") {
        $pagina = $_SESSION["CURRENTPAGE_AUTOEXEC"];
    }
}
if (isset($_POST["PROCURAR"])) {
    if ($_POST["PROCURAR"] != "") {
        $procurar = $_POST["PROCURAR"];
        $pagina = 1;
        $_SESSION["SEARCHSTRING_AUTOEXEC"] = $procurar;
    }
}
$procurar = $_SESSION["SEARCHSTRING_AUTOEXEC"];
if (isset($_POST['PROCURAR_EXCETO'])) {
    if ($_POST['PROCURAR_EXCETO'] != "") {
        $procurar_exceto = $_POST['PROCURAR_EXCETO'];
        $pagina = 1;
        $_SESSION["SEARCHEXCETO_AUTOEXEC"] = $procurar_exceto;
    }
}
$procurar_exceto = $_SESSION["SEARCHEXCETO_AUTOEXEC"];
if (isset($_GET['ORDEM'])) {
    $ordem = strtolower($_GET['ORDEM']);
    if ($ordem != $_SESSION["ORDEM_AUTOEXEC"]) {
        $_SESSION["ORDEM_AUTOEXEC"] = $ordem;
        $_SESSION["ORDEMPOS_AUTOEXEC"] = "";
    } else {
        if ($_SESSION["ORDEMPOS_AUTOEXEC"] == "") {
            $_SESSION["ORDEMPOS_AUTOEXEC"] = "desc";
        } else {
            $_SESSION["ORDEMPOS_AUTOEXEC"] = "";
        }
    }
}
if (isset($_GET['ORDEMPOS'])) {
    $ordempos = strtolower($_GET['ORDEMPOS']);
    if ($ordempos != $_SESSION["ORDEMPOS_AUTOEXEC"]) {
        $_SESSION["ORDEMPOS_AUTOEXEC"] = $ordempos;
    }
}
$ordem = $_SESSION["ORDEM_AUTOEXEC"];
$ordempos = $_SESSION["ORDEMPOS_AUTOEXEC"];
//********************
//********************
// EOF - CONTROLE DE PAGINAÇÃO
//********************
//********************

//********************
//********************
// CAIXA
//********************
//********************
$caixa_autoexec = 'CADASTRADO';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($_POST["CAIXA_AUTOEXEC"] != "") {
        if (strval($_POST["CAIXA_AUTOEXEC"]) != strval($_SESSION["CAIXA_AUTOEXEC"])) {
            $pagina = 1;
        }
        $caixa_autoexec = intval($_POST["CAIXA_AUTOEXEC"]);
        $_SESSION["CAIXA_AUTOEXEC"] = $caixa_autoexec;
    }
}
if (isset($_SESSION["CAIXA_AUTOEXEC"])) {
    $caixa_autoexec = $_SESSION["CAIXA_AUTOEXEC"];
} else {
    $_SESSION["CAIXA_AUTOEXEC"] = $caixa_autoexec;
}
//********************
//********************
// EOF CAIXA
//********************
//********************
abre_empresa();

// Captura de paginação
$strsql = "
select 
sum(1) as qtdreg
from 
tautoexec
where 
tautoexec.caixa_autoexec = :VCAIXA_AUTOEXEC
";
$qautoexec = $pdo_empresa->prepare($strsql);
$qautoexec->bindParam(":VCAIXA_AUTOEXEC", $caixa_autoexec);
$qautoexec->execute();
if ($tautoexec = $qautoexec->fetch(PDO::FETCH_ASSOC)) {
    $qtdregtotal = $tautoexec["qtdreg"];
}
$strsql = "
select 
sum(1) as qtdreg_filtragem 
from 
tautoexec
where ";
if ($procurar != "") {
    $strsql = $strsql . (($procurar_exceto != "1") ? " " : " not ") .
        "(
    tautoexec.descr_autoexec like '%{$procurar}%' 
    ) and ";
}
$strsql .= "tautoexec.caixa_autoexec = :VCAIXA_AUTOEXEC";
$qautoexec = $pdo_empresa->prepare($strsql);
$qautoexec->bindParam(":VCAIXA_AUTOEXEC", $caixa_autoexec);
$qautoexec->execute();
if ($tautoexec = $qautoexec->fetch(PDO::FETCH_ASSOC)) {
    $qtdreg = $tautoexec['qtdreg_filtragem'];
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
        $_SESSION["FIRSTPAGE_AUTOEXEC"] = "0";
        $_SESSION["LASTPAGE_AUTOEXEC"] = "0";
    }
    if ($pagina == 'FIRST') {
        $pagina = '1';
    }
    if ($pagina == 'LAST') {
        $pagina = $qtdpagina;
    }
    $pagina = intval($pagina);
    $paginainicial = $_SESSION["FIRSTPAGE_AUTOEXEC"];
    $paginafinal = $_SESSION["LASTPAGE_AUTOEXEC"];
    // Início padrão, sem página informada
    if (intval($paginainicial) <= 0 || $pagina == 1) {
        $paginainicial = 1;
        $paginafinal = ($paginainicial + 5) - 1;
        $_SESSION["FIRSTPAGE_AUTOEXEC"] = $paginainicial;
        $_SESSION["LASTPAGE_AUTOEXEC"] = $paginafinal;
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
            $_SESSION["FIRSTPAGE_AUTOEXEC"] = $paginainicial;
            $_SESSION["LASTPAGE_AUTOEXEC"] = $paginafinal;
            $paginado = true;
        }
    }
    if (!$paginado) {
        if ($pagina == $paginafinal) {
            $paginainicial = $paginainicial + 1;
            $paginafinal = $paginafinal + 1;
            $_SESSION["FIRSTPAGE_AUTOEXEC"] = $paginainicial;
            $_SESSION["LASTPAGE_AUTOEXEC"] = $paginafinal;
            $paginado = true;
        }
    }
    if (!$paginado) {
        if ($pagina == $qtdpagina) {
            $paginafinal = $qtdpagina;
            $paginainicial = ($paginafinal - 5) + 1;
            $_SESSION["FIRSTPAGE_AUTOEXEC"] = $paginainicial;
            $_SESSION["LASTPAGE_AUTOEXEC"] = $paginafinal;
            $paginado = true;
        }
    }
    $_SESSION["CURRENTPAGE_AUTOEXEC"] = $pagina;
}
// EOF Captura de paginação

//********************
//********************
//**** Browse
//********************
//********************
$strsql = "
select
tautoexec.*
from
tautoexec
where ";
if ($procurar != "") {
    $strsql = $strsql . (($procurar_exceto != "1") ? " " : " not ") .
        "(
    tautoexec.descr_autoexec like '%{$procurar}%'
    ) and ";
}
$strsql .= "
tautoexec.caixa_autoexec = :VCAIXA_AUTOEXEC 
order by {$ordem} " . ($ordempos == "desc" ? " " . $ordempos : $ordempos);
if (intval($pagina) > 1) {
    $offset = ((intval($pagina) - 1) * 20);
    $strsql = $strsql . ' offset ' . $offset;
}
$qautoexec = $pdo_empresa->prepare($strsql);
$qautoexec->bindParam(":VCAIXA_AUTOEXEC", $caixa_autoexec);
$qautoexec->execute();
$contetq = 0;
$etq = '';
$qtdregpagina = 0;
while ($tautoexec = $qautoexec->fetch(PDO::FETCH_ASSOC)) {
    $chave_autoexec = $tautoexec["CHAVE_AUTOEXEC"];
    $cripto_autoexec = Cripto(str_pad($chave_autoexec, 6, "0", STR_PAD_LEFT));
    $descr_autoexec = strval($tautoexec["DESCR_AUTOEXEC"]);
    $etq .= '              <tr>' . "\n";
    $etq .= '                <td width="40" data-id_dbg="' . $cripto_autoexec . '" class="SELECIONA_LINHA text-nowrap">' . str_pad($chave_autoexec, 6, "0", STR_PAD_LEFT) . '</td>' . "\n";
    $etq .= '                <td><a href="autoexec_SQL_Server_edita.php?id=' . $cripto_autoexec . '&onback=' . Cripto('autoexec.php') . '"><img src="/include/icons16/Edition-33.png" class="navButton" title="Editar"></a></td>' . "\n";
    $etq .= '                <td data-id_dbg="' . $cripto_autoexec . '" class="SELECIONA_LINHA">' . $descr_autoexec . '</td>' . "\n";
    $etq .= '                <td width="20" class="text-center"><a href="#" data-bs-toggle="modal" data-bs-target="#MODAL_DIARIO_EXC" id="SHOW_DIARIO_EXC" data-id="' . $chave_autoexec . '" data-campo-id="chave_autoexec" data-tabela-id="tautoexec" data-url-id="' . $Raiz . '" data-caixa-id="caixa_autoexec"><img src="/include/icons16/Edition-41.png" class="navButton" title="Excluir"></a></td>' . "\n";
    $etq .= '                <td width="20" class="text-center"><a href="#" data-bs-toggle="modal" data-bs-target="#MODAL_DIARIO"     id="SHOW_DIARIO"     data-id="' . $chave_autoexec . '" data-campo-id="chave_autoexec" data-tabela-id="tautoexec" data-url-id="' . $Raiz . '" ><img src="/include/icons16/Programing-51.png" class="navButton" title="Diário do registro"></a></td>' . "\n";
    $etq .= '              </tr>' . "\n";
    $contetq = $contetq + 1;
    $qtdregpagina = $qtdregpagina + 1;
    if ($contetq == 4) {
        $contetq = 0;
    }
}
if ($procurar != "") {
    $btn_redefinir = '<button type="button" class="btn btn-sm btn-warning ml-1" id="BTN_REDEFINIR" name="BTN_REDEFINIR" onclick="javascript:redefinir_autoexec();">Redefinir</button>' . "\n";
} else {
    $btn_redefinir = "";
}
// Pesquisa
$html_reg = "";
if ($qtdreg <= 0) {
    $html_reg .= '      <span>Nenhum registro</span>';
} else {
    $html_reg .= '      <span>Registros: ' . $qtdregpagina . '/' . $qtdreg . '</span>';
}
$html_pesq = "";
$html_pesq .= '      <div class="row">' . "\n"; // Pesquisa
$html_pesq .= '        <div class="col-6">' . "\n"; // Pesquisa
$html_pesq .= '          <a class="btn btn-sm btn-outline-light navButtonForm" id="BTN_INCLUIR_2" name="BTN_INCLUIR_2"><img src="/include/icons16/Files-3.png" class="navButton" title="Incluir"></a>' . "\n";
$html_pesq .= '        </div>' . "\n"; // Pesquisa
$html_pesq .= '        <div class="col-6 justify-content-end d-inline-flex gap-1">' . "\n"; // Pesquisa
$html_pesq .= '          <form action="autoexec.php" id="FAUTOEXEC_FILTRO" name="FAUTOEXEC_FILTRO" method="POST">' . "\n"; // Pesquisa
$html_pesq .= '            <input type="hidden" id="RESET_AUTOEXEC" name="RESET_AUTOEXEC" value="">' . "\n";
$html_pesq .= '            <input type="hidden" id="CAIXA_AUTOEXEC" name="CAIXA_AUTOEXEC" value="">' . "\n";
$html_pesq .= '            <div class="input-group">' . "\n";
$html_pesq .= '              <input type="text" class="form-control form-control-sm' . ($procurar != "" ? ' form-danger' : '') . '" onkeyup="javascript:procurar_avaliatecla(event);" id="PROCURAR" name="PROCURAR" value="' . $procurar . '" placeholder="Procurar">' . "\n";
$html_pesq .= '              <span class="input-group-text">' . "\n";
$html_pesq .= '                <div class="form-check" style="padding:-6px; margin:-6px;">' . "\n";
$html_pesq .= '                  <input class="form-check-input" type="checkbox" id="PROCURAR_EXCETO" name="PROCURAR_EXCETO" value="1" ' . (($procurar_exceto == "1") ? ' checked' : '') . '>' . "\n";
$html_pesq .= '                  <label class="form-check-label small" style="padding-top:2px;" for="PROCURAR_EXCETO">' . "\n";
$html_pesq .= '                    Exceto' . "\n";
$html_pesq .= '                  </label>' . "\n";
$html_pesq .= '                </div>' . "\n";
$html_pesq .= '              </span>' . "\n";
$html_pesq .= '              <button type="button" class="btn btn-sm btn-primary" id="BTN_PROCURAR" name="BTN_PROCURAR" onclick="javascript:procurar_autoexec();">Ok</button>' . "\n";
$html_pesq .= '              ' . $btn_redefinir;
$html_pesq .= '            </div>' . "\n";
$html_pesq .= '          </form>' . "\n";
$html_pesq .= '          <button type="button" class="btn btn-sm btn-secondary" id="BTN_LIXEIRA" name="BTN_LIXEIRA" onclick="javascript:caixa_autoexec(\'' . $caixa_autoexec . '\')">' . (($caixa_autoexec == 1) ? 'Lixeira' : 'Cadastrados') . '</button>' . "\n";
$html_pesq .= '        </div>' . "\n";
$html_pesq .= '      </div>' . "\n";
// EOF Pesquisa

// Corpo
$html_titulo = "";
$html_titulo .= '<div class="row mb-1">' . "\n";
$html_titulo .= '  <div class="col">' . "\n";
$html_titulo .= '    <h4>' . $pagina_titulo . '</h4>' . "\n";
$html_titulo .= '  </div>' . "\n";
$html_titulo .= '</div>' . "\n";

$html .= '      <div class="card shadow">' . "\n";
$html .= '        <div class="card-header m-0">' . "\n";
$html .= '          ' . $html_pesq . "\n";
$html .= '        </div>' . "\n";
$html .= '        <div class="card-body" style="padding:0; margin:0">' . "\n";
$html .= '          <form action="autoexec_SQL_Server_edita.php" id="FPRO" name="FPRO" method="POST">' . "\n";
$html .= '            <input type="hidden" id="chave_autoexec" name="chave_autoexec" value="">' . "\n";
$html .= '            <input type="hidden" id="acao_autoexec" name="acao_autoexec" value="EDITAR">' . "\n";
if ($etq != "") {
    $html .= '	        <table class="table table-hover table-sm mb-0">' . "\n";
    $html .= '              <thead class="thead-light">' . "\n";
    $html .= '                <tr>' . "\n";
    $html .= '                  <th width="40"><a href="' . $Raiz . 'sistema/autoexec/autoexec.php?ORDEM=CHAVE_AUTOEXEC">Chave</a></th>' . "\n";
    $html .= '                  <th width="20"><img src="/include/icons16/Edition-33.png" class="navButtonOff" title="Editar"></th>' . "\n";
    $html .= '                  <th><a href="' . $Raiz . 'sistema/autoexec/autoexec.php?ORDEM=DESCR_PRODUTO">Descrição</a></th>' . "\n";
    $html .= '                  <th width="20"><a href="' . $Raiz . 'sistema/autoexec/autoexec.php?ORDEM=CAIXA_AUTOEXEC">&nbsp;</a></th>' . "\n";
    $html .= '                  <th width="20"></th>' . "\n";
    $html .= '                </tr>' . "\n";
    $html .= '              </thead>' . "\n";
    $html .= '              <tbody>' . "\n";
    $html .= '                ' . $etq . "\n";
    $html .= '              </tbody>' . "\n";
    $html .= '            </table>' . "\n";
}
$html .= '          </form>' . "\n";
$html .= '        </div>' . "\n";
//********************
//********************
//**** Botões de paginação inferior
//********************
//********************
$html .= '          <div class="card-footer text-muted">' . "\n";
$html .= '            <div class="row mb-0">' . "\n";
$html .= '              <div class="col-12">' . "\n";
//if ($qtdpagina > 0 && ($qtdreg > $qtdlimite)) {
if ($qtdreg > 0) {
    $html .= '                <nav aria-label="Páginas de navegação">' . "\n";
    $html .= '                  <ul class="pagination pagination-sm mb-0 float-start">' . "\n";
    $html .= '                    <li class="page-item"><a class="page-link" href="autoexec.php?PG=FIRST' . (($_SESSION['SEARCHSTRING_AUTOEXEC'] != "") ? '&PROCURAR=' . $_SESSION['SEARCHSTRING_AUTOEXEC'] : "") . '">&laquo;</a></li>' . "\n";
    if ($qtdpagina > 0) {
        $cont_apagina = count($apagina);
        for ($i = 0; $i <= $cont_apagina - 1; $i++) {
            if (($i + 1) >= $paginainicial && ($i + 1) <= $paginafinal) {
                if (($i + 1) == intval($pagina)) {
                    $html .= '                    <li class="page-item active"><a class="page-link" href="autoexec.php?PG=' . $apagina[$i] . (($_SESSION['SEARCHSTRING_AUTOEXEC'] != "") ? '&PROCURAR=' . $_SESSION['SEARCHSTRING_AUTOEXEC'] : "") . '">' . $apagina[$i] . '</a></li>' . "\n";
                } else {
                    $html .= '                    <li class="page-item"><a class="page-link" href="autoexec.php?PG=' . $apagina[$i] . (($_SESSION['SEARCHSTRING_AUTOEXEC'] != "") ? '&PROCURAR=' . $_SESSION['SEARCHSTRING_AUTOEXEC'] : "") . '">' . $apagina[$i] . '</a></li>' . "\n";
                }
            }
        }
    }
    $html .= '                    <li class="page-item"><a class="page-link" href="autoexec.php?PG=LAST' . (($_SESSION['SEARCHSTRING_AUTOEXEC'] != "") ? '&PROCURAR=' . $_SESSION['SEARCHSTRING_AUTOEXEC'] : "") . '" aria-label="Último"><span aria-hidden="true">&raquo;</span></a></li>' . "\n";
    $html .= '                  </ul>' . "\n";
    $html .= '                </nav>' . "\n";
} else {
    if ($qtdregtotal > 0 and ($qtdregtotal != $qtdreg)) {
        $html .= '                <a href="javascript:redefinir_autoexec();"><span class="float-start">Há ' . ($qtdregtotal - $qtdreg) . ' registro(s) que não satisfaz a pesquisa.</span></a>' . "\n";
    }
}
$html .= '                <nav aria-label="Contador de registros">' . "\n";
$html .= '                  <ul class="pagination pagination-sm mb-0 justify-content-end">' . "\n";
$html .= '                    <li class="page-item disabled"><a class="page-link">' . $html_reg . '</a></li>' . "\n";
$html .= '                  </ul>' . "\n";
$html .= '                </nav>' . "\n";
$html .= '              </div>' . "\n";
$html .= '            </div>' . "\n";
$html .= '            <div class="row mb-0">' . "\n";
$html .= '              <div class="col text-center">' . "\n";
$html .= '                <button type="button" class="btn btn-sm btn-primary" id="BTN_INCLUIR" name="BTN_INCLUIR">Incluir</button>' . "\n";
$html .= '              </div>' . "\n";
$html .= '            </div>' . "\n";
$html .= '          </div>' . "\n";
//********************
//********************
//**** EOF Botões de paginação inferior
//********************
//********************
$html .= '        </div>' . "\n";
// EOF Corpo
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title><?php Titulo($pagina_titulo, true) ?></title>
    <?php include($Raiz . "include/html/header.html"); ?>
</head>
<script type="text/javascript">
    function caixa_autoexec(ccaixa) {
        var vcaixa = 1;
        if (ccaixa == 1) {
            vcaixa = 0;
        }
        document.getElementById("CAIXA_AUTOEXEC").value = vcaixa;
        document.getElementById("FAUTOEXEC_FILTRO").submit();
    }

    function procurar_autoexec() {
        if (document.getElementById("PROCURAR").value != "") {
            document.getElementById("RESET_AUTOEXEC").value = "NAO";
            document.getElementById("FAUTOEXEC_FILTRO").submit();
        }
        else {
            document.getElementById("RESET_AUTOEXEC").value = "SIM";
            document.getElementById("FAUTOEXEC_FILTRO").submit();
        }
    }

    function redefinir_autoexec() {
        document.getElementById("RESET_AUTOEXEC").value = "SIM";
        document.getElementById("FAUTOEXEC_FILTRO").submit();
    }

    function procurar_avaliatecla(e) {
        if (e.key == "Enter") {
            procurar_autoexec();
        }
    }

    $(document).on("keyup", function (tecla) {
        if (tecla.key == "F2" || tecla.key == "Insert") {
            if ($('#MODAL_AUTOEXEC').hasClass('show')) {
                document.getElementById("BTN_AUTOEXEC_SUBMIT").click();
            }
            else {
                document.getElementById("BTN_AUTOEXEC_INCLUIR").click();
            }
        }
        if (tecla.key == "Escape") {
            window.location.href = "autoexec.php";
        }
    });

</script>

<body>
    <?php include($Raiz . "include/php/menu.php"); ?>
    <?php
    Modal_Diario($Raiz);
    Modal_Exclusao($Raiz, $link_main);
    ?>
    <div class="container-fluid">
        <?php echo $html_titulo ?>
        <div class="row">
            <div class="col">
                <?php echo $html; ?>
            </div>
        </div>
        <div class="row mt-4 Texto-Rodape">
            <div class="col" id="LEGENDA">
                <h5>Legendas</h5>
                <img src="/include/icons16/Files-3.png" class="navButton" style="padding-right: 10px;" /><span>Incluir
                    registro</span><br />
                <img src="/include/icons16/Edition-33.png" class="navButton" style="padding-right: 10px;" /><span>Editar
                    registro</span><br />
                <img src="/include/icons16/Edition-41.png" class="navButton"
                    style="padding-right: 10px;" /><span>Excluir registro</span><br />
                <img src="/include/icons16/Design-16.png" class="navButton" style="padding-right: 10px;" /><span>Cores
                    do Revestimento</span><br />
                <img src="/include/icons16/Design-10.png" class="navButton"
                    style="padding-right: 10px;" /><span>Gravações do Revestimento</span><br />
                <img src="/include/icons16/Programing-51.png" class="navButton"
                    style="padding-right: 10px;" /><span>Diário do registro</span><br />
            </div>
        </div>
    </div>
    <?php include($Raiz . "include/php/rodape.php"); ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".SELECIONA_LINHA").click(function () {
                window.location.href = 'autoexec_SQL_Server_edita.php?id=' + $(this).data("id_dbg");
            });
            $("#BTN_INCLUIR").click(function () {
                window.location.href = 'autoexec_SQL_Server_edita.php?id=NOVO';
            });
            $("#BTN_INCLUIR_2").click(function () {
                window.location.href = 'autoexec_SQL_Server_edita.php?id=NOVO';
            });
        });
    </script>
</body>

</html>
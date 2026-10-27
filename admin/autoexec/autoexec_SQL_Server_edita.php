<?php
/*
*/
session_start();
$Raiz = "../../";
include($Raiz . "include/php/header.php");
include($Raiz . "include/php/funcoes.php");
include($Raiz . "conexao/db.php");
$Home = $Raiz . "sistema/autoexec/autoexec_SQL_Server.php";
$ok = true;
$acao = "EDITAR";
$chave_autoexec = 0;
$link_main = $Raiz . "sistema/autoexec/autoexec_SQL_Server.php";
VerificaSessao();
VerificaAdmin($Raiz);
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (!isset($_POST["chave_autoexec"])) {
		$ok = false;
	} else {
		$chave_autoexec = $_POST["chave_autoexec"];
	}
} else {
	if ($_SERVER['REQUEST_METHOD'] == "GET") {
		if (!isset($_GET["id"])) {
			$ok = false;
		} else {
			$chave_autoexec = $_GET["id"];
			if ($chave_autoexec == 'NOVO') {
				$chave_autoexec = '0';
			} else {
				$chave_autoexec = Uncripto($chave_autoexec);
			}
		}
		if ($ok) {
			try {
				$chave_autoexec = intval($chave_autoexec);
			} catch (Exception $e) {
				$ok = false;
			}
		}
	}
}
if (!$ok) {
	header("Location: " . $Home);
	die();
}
$pagina_titulo = "DBU SQL Server";
abre_empresa();
//********************
//********************
//**** Grava registro caso POST chamado contenha diretiva GRAVAR
//********************
//********************
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$acao = $_POST["acao_autoexec"];
}
if ($acao == "GRAVAR") {
	// Avalia informação para inclusão	
	$chave_autoexec = $_POST["chave_autoexec"];
	$descr_autoexec = $_POST["descr_autoexec"];
	$tabela_autoexec = $_POST["tabela_autoexec"];
	$codigo_autoexec = $_POST["codigo_autoexec"];
	$caixa_autoexec = 'CADASTRADO';
	if ($chave_autoexec == "0") {
		// Inclusão do registro	   
		$strsql = "
		insert 
		into 
		tautoexec 
		(descr_autoexec
		,tabela_autoexec
		,codigo_autoexec
		,caixa_autoexec
		) 
		values 
		(:vdescr_autoexec
		,:vtabela_autoexec
		,:vcodigo_autoexec
		,:vcaixa_autoexec
		)";
		$qautoexec = $pdo_empresa->prepare($strsql);
		$qautoexec->bindParam(":vdescr_autoexec", $descr_autoexec);
		$qautoexec->bindParam(":vtabela_autoexec", $tabela_autoexec);
		$qautoexec->bindParam(":vcodigo_autoexec", $codigo_autoexec);
		$qautoexec->bindParam(":vcaixa_autoexec", $caixa_autoexec);
		$qautoexec->execute();
		$chave_autoexec = $pdo_empresa->lastInsertId();
		//elenir		fecha_diario("tautoexec", "chave_autoexec", $chave_autoexec, $abre_diario = array(), $campoexcluidos = array(""));
		// EOF Inclusao do registro	   
	} else {
		// Alteção do registro
		//elenir$abre_diario = abre_diario("tautoexec", "chave_autoexec", $chave_autoexec, $campoexcluidos = array(""));
		$strsql = "
		update 
		tautoexec 
		set 
		descr_autoexec = :vdescr_autoexec
		,tabela_autoexec = :vtabela_autoexec
		,codigo_autoexec = :vcodigo_autoexec
		where 
		chave_autoexec = :vchave_autoexec
		";
		$qautoexec = $pdo_empresa->prepare($strsql);
		$qautoexec->bindParam(":vchave_autoexec", $chave_autoexec);
		$qautoexec->bindParam(":vdescr_autoexec", $descr_autoexec);
		$qautoexec->bindParam(":vtabela_autoexec", $tabela_autoexec);
		$qautoexec->bindParam(":vcodigo_autoexec", $codigo_autoexec);
		$qautoexec->execute();
		//elenirfecha_diario("tautoexec", "chave_autoexec", $chave_autoexec, $abre_diario, $campoexcluidos = array(""));
		// EOF Alteração do registro	   
	}
	fecha_empresa();
	header("Location: " . $Home);
	die();
}
fecha_empresa();
//********************
//********************
//**** EOF - Grava registro caso POST chamado contenha diretiva GRAVAR
//********************
//********************

//********************
//********************
//**** Edita registro caso POST chamado contenha diretiva EDITAR
//********************
//********************
$html_btn = "";
if ($acao == "EDITAR") {
	$cripto_autoexec = Cripto(str_pad($chave_autoexec, 6, "0", STR_PAD_LEFT));
	$descr_autoexec = "";
	$tabela_autoexec = "";
	$codigo_autoexec = "";
	//********************
	//********************
	//**** Pega registro se já esitver cadastrado
	//********************
	//********************
	abre_empresa();
	if ($chave_autoexec > 0) {
		$strsql = "
		select 
		tautoexec.*
    from 
		tautoexec
		where 
		tautoexec.chave_autoexec = :vchave_autoexec and
		tautoexec.caixa_autoexec = 'CADASTRADO'
		order by chave_autoexec";
		$qautoexec = $pdo_empresa->prepare($strsql);
		$qautoexec->bindParam(":vchave_autoexec", $chave_autoexec);
		$qautoexec->execute();
		if ($tautoexec = $qautoexec->fetch(PDO::FETCH_ASSOC)) {
			$descr_autoexec = strval($tautoexec["DESCR_AUTOEXEC"]);
			$tabela_autoexec = strval($tautoexec["TABELA_AUTOEXEC"]);
			$codigo_autoexec = strval($tautoexec["CODIGO_AUTOEXEC"]);
		}
	}
	//********************
	//********************
	//**** EOF - Pega registro se já esitver cadastrado
	//********************
	//********************

	//********************
	//********************
	//**** Pega Tabelas
	//********************
	//********************
	$html_tabela = '';
	$html_tabela .= '<option value="0" selected="selected">--- Nenhuma Tabela selecionada ---</option>' . "\n";
	$strsql = "
	select 
	table_name
	from
  reciboexpress.Information_Schema.tables
	order by table_name
	";
	$qquery = $pdo_empresa->prepare($strsql);
	$qquery->execute();
	while ($tquery = $qquery->fetch(PDO::FETCH_ASSOC)) {
		$html_tabela .= '<option value="' . $tquery["table_name"] . '"' . (($tabela_autoexec == $tquery["table_name"]) ? ' selected="selected"' : '') . '>' . $tquery["table_name"] . '</option>' . "\n";
	}
	//********************
	//********************
	//**** EOF Pega Tabelas
	//********************
	//********************

	fecha_empresa();

	//********************
	//********************
	//**** Define título da página (e links relacionados)
	//********************
	//********************
	if ($chave_autoexec <= 0) {
		$pagina_titulo = "Inclusão de Registro";
	} else {
		$pagina_titulo = "Alteração de Registro";
	}
	$html_titulo = "";
	$html_titulo .= '<div class="row mb-1">' . "\n";
	$html_titulo .= '  <div class="col">' . "\n";
	$html_titulo .= '    <h4>' . $pagina_titulo . '</h4>' . "\n";
	$html_titulo .= '  </div>' . "\n";
	$html_titulo .= '</div>' . "\n";
	$html_botoes = "";
	$html_botoes .= '      <a class="btn btn-sm btn-outline-light navButtonForm" href="' . $Home . '"><img src="/include/icons16/chevron-big-3-01.png" class="navButton"></a>' . "\n";
	if ($chave_autoexec > 0) {
		$html_botoes .= '      <a class="dropdown-toggle btn btn-sm btn-outline-light navButtonForm" href="#" data-bs-toggle="dropdown" aria-expanded="false" style="margin-right:20px"><img src="/include/icons16/Content-43.png" class="navButton"></a>' . "\n";
		$html_botoes .= '      <ul class="dropdown-menu">' . "\n";
		$html_botoes .= '        <li><a href="#" class="dropdown-item" id="SHOW_DIARIO_EXC" data-id="' . $chave_autoexec . '" data-campo-id="chave_autoexec" data-tabela-id="tautoexec" data-url-id="' . $Raiz . '" data-caixa-id="caixa_autoexec" data-bs-toggle="modal" data-bs-target="#MODAL_DIARIO_EXC">Excluir</a></li>' . "\n";
		$html_botoes .= '        <li><hr class="dropdown-divider"></li>' . "\n";
		$html_botoes .= '        <li><a href="#" class="dropdown-item" id="SHOW_DIARIO" data-id="' . $chave_autoexec . '" data-campo-id="chave_autoexec" data-tabela-id="tautoexec" data-url-id="' . $Raiz . '" data-bs-toggle="modal" data-bs-target="#MODAL_DIARIO">Diário Registro</a></li>' . "\n";
		$html_botoes .= '      </ul>' . "\n";
	}
	//********************
	//********************
	//**** EOF - Define título da página (e links relacionados)
	//********************
	//********************
	$html_btn = '<input type="button" class="btn btn-sm btn-primary" value="Confirmar" id="BTN_GRAVAR" name="BTN_GRAVAR" onClick="javascript:Submeter();">' . "\n";
}
//********************
//********************
//**** EOF - Edita registro caso POST chamado contenha diretiva EDITAR
//********************
//********************
?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title><?php echo Titulo($pagina_titulo, true); ?></title>
	<?php include($Raiz . "include/html/header.html"); ?>
</head>
<script type="text/javascript">
	function Submeter() {
		var vok = true;
		var vmsg = '';
		var vonfocus;
		if (document.getElementById('tabela_autoexec').value == '' || document.getElementById('tabela_autoexec').value == null) {
			vok = false;
			if (vmsg != '') {
				vmsg = vmsg + '\n';
			}
			vmsg = vmsg + 'Tabela inválida.';
			if (typeof vonfocus === 'undefined') {
				vonfocus = document.getElementById('tabela_autoexec');
			}
		}
		if (vok) {
			document.FAUTOEXEC.submit();
		}
		else {
			alert(vmsg);
			vonfocus.focus();
		}
	};

	$(document).on('keyup', function (event) {
		if (event.key == 'Escape') {
			window.location.href = 'autoexec_SQL_Server.php';
		}
	});

	function Retornar() {
		location.href = 'autoexec_SQL_Server.php';
	}

	function f_tabela() {
		var vok = true;
		var vtabela_autoexec = document.getElementById("tabela_autoexec").value;
		if (vtabela_autoexec != "" && vtabela_autoexec != null) {
			if (vok) {
				var vurl;
				vurl = "retorna_SQL_Server_codigo.php";
				vurl = vurl + "?idpro=" + vtabela_autoexec;
				var vhttp = new XMLHttpRequest();
				vhttp.onload = function () {
					document.getElementById("DIV_CODIGO").innerHTML = this.responseText;
				}
				vhttp.open("GET", vurl);
				vhttp.send();
			}
		}
	}
</script>

<body>
	<?php include($Raiz . "include/php/menu.php"); ?>
	<?php
	Modal_Diario($Raiz);
	Modal_Exclusao($Raiz, $link_main);
	?>
	<div class="container">
		<?php echo $html_titulo ?>
		<div class="card shadow">
			<div class="card-header">
				<?php echo $html_botoes ?>
			</div>
			<div class="card-body small">
				<form method="POST" action="<?php echo $Raiz ?>sistema/autoexec/autoexec_SQL_Server_edita.php" id="FAUTOEXEC"
					name="FAUTOEXEC">
					<div class="row">
						<div class="col-8">
							<div class="card mb-1">
								<div class="card-header">
									<h5 style="margin:0px">DBU SQL Server</h5>
								</div>
								<div class="card-body">
									<div class="row mb-1">
										<input type="hidden" id="acao_autoexec" name="acao_autoexec" value="GRAVAR">
										<label for="chave_autoexec" class="col-sm-2">Chave</label>
										<div class="col-sm-10">
											<input type="text" class="form-control form-control-sm bg-light" id="chave_autoexec"
												name="chave_autoexec" value="<?php echo $chave_autoexec ?>" readonly="readonly">
										</div>
									</div>

									<div class="row mb-1">
										<label for="descr_autoexec" class="col-sm-2">Descrição</label>
										<div class="col-sm-10">
											<input type="text" class="form-control form-control-sm" id="descr_autoexec" name="descr_autoexec"
												value="<?php echo $descr_autoexec ?>">
										</div>
									</div>

									<div class="row mb-1">
										<label for="tabela_autoexec" class="col-sm-2">Tabela</label>
										<div class="col-sm-10">
											<select class="form-select form-select-sm" id="tabela_autoexec" name="tabela_autoexec"
												onchange="javascript:f_tabela();">
												<?php echo $html_tabela ?>
											</select>
										</div>
									</div>


									<div class="row mb-1">
										<label for="codigo_autoexec" class="col-sm-2">Código</label>
										<div class="col-sm-10">
											<div id="DIV_CODIGO">
												<textarea class="form-control form-control-sm" style="font-family: Courier New"
													id="codigo_autoexec" name="codigo_autoexec"
													rows="20"><?php echo $codigo_autoexec ?></textarea>
											</div>
										</div>
									</div>

<!--
								<div class="row mb-1">
									<label class="col-sm-2"></label>
									<div class="col-sm-10">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" value="" id="ativo_autoexec" name="ativo_autoexec"<?php echo ($ativo_autoexec == 1 ? ' checked' : ''); ?>>
											<label class="form-check-label" for="ativo_autoexec">
												Ativo
											</label>
										</div>                        
									</div>
								</div>                                       
-->
								</div>
							</div>
						</div>
						<div class="col-4">
							<div class="card mb-1">
								<div class="card-header">
									<h5 style="margin:0px">Javascript</h5>
								</div>
								<div class="card-body">
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="card-footer text-center text-muted">
				<?php echo $html_btn; ?>
			</div>
		</div>
	</div>
	<?php include($Raiz . "include/php/rodape.php"); ?>
</body>

</html>
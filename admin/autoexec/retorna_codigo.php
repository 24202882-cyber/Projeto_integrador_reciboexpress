<?php
/*
*/
$Raiz = "../../";
include($Raiz . "include/php/header.php");
include($Raiz . "include/php/funcoes.php");
include($Raiz . "conexao/db.php");
$ok = false;
$tabela = "";
if ($_SERVER['REQUEST_METHOD'] == "GET") {
	if (isset($_GET["idpro"])) {
		$tabela = $_GET["idpro"];
	}	
}
abre_db();
//********************
//********************
//**** Pega Estrutura da Tabela
//********************
//********************
$html = '';
$etq = '';
if ($tabela != "") {
	$pk = "";
	$strsql = "
	select 
	column_name
	,data_type 
	from 
	information_schema.columns
	where 
	table_schema = 'reciboexpress' and 
	table_name = '$tabela'
	order by table_name,ordinal_position
	";
	$qquery = $pdo->prepare($strsql);
	$qquery->execute();
	$i = 1;
	while ($tquery = $qquery->fetch(PDO::FETCH_ASSOC)) {
		$datatype = $tquery["DATA_TYPE"];
		if ($datatype == "int") {
			$datatype = "0";
		}
		if ($datatype == "varchar") {
			$datatype = '""';
		}
		if ($datatype == "text") {
			$datatype = '""';
		}
		if ($datatype == "datetime") {
			$datatype = "date('Y-m-d\TH:i')";		
		}
	    $etq .= '$' . strval($tquery["COLUMN_NAME"]) . ' = ' . $datatype . ';' . "\n";
		if ($pk == "") {
			$pk = $tquery["COLUMN_NAME"];
		}
	}
	
	$etq .= "\n";
	$qquery->execute();
	while ($tquery = $qquery->fetch(PDO::FETCH_ASSOC)) {
	    $etq .= '$' . strval($tquery["COLUMN_NAME"]) . ' = $_POST["' . strval($tquery["COLUMN_NAME"]) . '"];' . "\n";
	}
	
	$etq .= "\n";
	$etq .= '$strsql = "' . "\n";
	$etq .= "insert " . "\n";
	$etq .= "into " . "\n";
	$etq .= "$tabela " . "\n";
	$etq .= "(";
	$etq_prefixo = "";
	$qquery->execute();
	while ($tquery = $qquery->fetch(PDO::FETCH_ASSOC)) {
	    $etq .= $etq_prefixo . strval($tquery["COLUMN_NAME"]) . "\n";
		$etq_prefixo = ",";
	}
	$etq_prefixo = "";
	$etq .= ") " . "\n";
	$etq .= "values " . "\n";
	$etq .= "(";
	$qquery->execute();
	while ($tquery = $qquery->fetch(PDO::FETCH_ASSOC)) {
	    $etq .= $etq_prefixo . ':v' . strval($tquery["COLUMN_NAME"]) . "\n";
		$etq_prefixo = ",";
	}
	$etq .= ')";' . "\n";
	$etq .= '$q' . substr($tabela,1) . ' = $pdo->prepare($strsql);' . "\n";
	$qquery->execute();
	while ($tquery = $qquery->fetch(PDO::FETCH_ASSOC)) {
		$etq .= '$q' . substr($tabela,1) . '->bindParam(":v' . strval($tquery["COLUMN_NAME"]) .  '", $' . strval($tquery["COLUMN_NAME"]) . ');' . "\n";
	}
	$etq .= '$q' . substr($tabela,1) . '->execute();' . "\n";
	$etq .= '$' . $pk . ' = $pdo->lastInsertId();' . "\n";
	$etq .= 'fecha_diario("' . $tabela . '", "' . $pk . '", $' . $pk . ', $abre_diario = array(), $campoexcluidos = array(""));' . "\n";

	$etq .= "\n";
	$etq .= '$abre_diario = abre_diario("' . $tabela . '", "' . $pk . '", $' . $pk . ', $campoexcluidos = array(""));' . "\n";
	$etq .= '$strsql = "' . "\n";	
	$etq .= "update " . "\n";
	$etq .= "$tabela " . "\n";
	$etq .= "set " . "\n";
	$etq_prefixo = "";
	$qquery->execute();
	while ($tquery = $qquery->fetch(PDO::FETCH_ASSOC)) {
	    $etq .= $etq_prefixo . strval($tquery["COLUMN_NAME"]) . ' = :v' . strval($tquery["COLUMN_NAME"]) . "\n";
		$etq_prefixo = ",";
	}
	$etq .= 'where ' . "\n";
	$etq .= $pk . ' = :v' . $pk . "\n";
	$etq .= '";' . "\n";
	$etq .= '$q' . substr($tabela,1) . ' = $pdo->prepare($strsql);' . "\n";
	$qquery->execute();
	while ($tquery = $qquery->fetch(PDO::FETCH_ASSOC)) {
		$etq .= '$q' . substr($tabela,1) . '->bindParam(":v' . strval($tquery["COLUMN_NAME"]) .  '", $' . strval($tquery["COLUMN_NAME"]) . ');' . "\n";
	}
	$etq .= '$q' . substr($tabela,1) . '->execute();' . "\n";
	$etq .= 'fecha_diario("' . $tabela . '", "' . $pk . '", $' . $pk . ', $abre_diario, $campoexcluidos = array(""));' . "\n";
	
	
	$etq_prefixo = "";
	$etq .= "\n";
	$etq .= '$strsql = "' . "\n";
	$etq .= "select " . "\n";
	$qquery->execute();
	while ($tquery = $qquery->fetch(PDO::FETCH_ASSOC)) {
	    $etq .= $etq_prefixo . $tabela . '.' . strval($tquery["COLUMN_NAME"]) . "\n";
		$etq_prefixo = ",";
	}
	$etq .= "from " . "\n";
	$etq .= "$tabela " . "\n";
	$etq .= "where " . "\n";
	$etq .= $pk . ' = :v' . $pk . "\n";
	$etq .= '";' . "\n";
	$etq .= '$q' . substr($tabela,1) . ' = $pdo->prepare($strsql);' . "\n";
	$etq .= '$q' . substr($tabela,1) . '->bindParam(":v' . $pk . '", $' . $pk . ');' . "\n";
	$etq .= '$q' . substr($tabela,1) . '->execute();' . "\n";
	
}
//********************
//********************
//**** EOF Pega Estrutura da Tabela
//********************
//********************	
fecha_db();
if ($etq != "") {
	$html .= '<textarea class="form-control form-control-sm" style="font-family: Courier New" id="codigo_autoexec" name="codigo_autoexec" rows="20">' . $etq .  '</textarea>';
}
echo $html;


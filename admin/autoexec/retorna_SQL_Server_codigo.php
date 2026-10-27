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
abre_empresa();
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
	reciboexpress.information_schema.columns
	where 	
	table_name = '$tabela'
	order by table_name,ordinal_position
	";
	$qquery = $pdo_empresa->prepare($strsql);
	$qquery->execute();
	$i = 1;
	//********************
	//********************
	//**** Declaração de variáveis
	//********************
	//********************	
	while ($tquery = $qquery->fetch(PDO::FETCH_ASSOC)) {
		$datatype = $tquery["data_type"];
		if ($datatype == "int") {
			$datatype = "0";
		}
		if ($datatype == "varchar" || $datatype == "nvarchar") {
			$datatype = '""';
		}
		if ($datatype == "text") {
			$datatype = '""';
		}
		if ($datatype == "datetime") {
			$datatype = "date('Y-m-d\TH:i')";		
		}
	  $etq .= '$' . strval(strtolower($tquery["column_name"])) . ' = ' . $datatype . ';' . "\n";
		if ($pk == "") {
			$pk = $tquery["column_name"];
		}
	}	
	//********************
	//********************
	//**** EOF Declaração de variáveis
	//********************
	//********************	

	//********************
	//********************
	//**** Atribuição de campos para variáveis.
	//********************
	//********************		
	$etq .= "\n";
	$qquery->execute();
	while ($tquery = $qquery->fetch(PDO::FETCH_ASSOC)) {
		$etq .= '$' . strval(strtolower($tquery["column_name"])) . ' = $_POST["' . strval($tquery["column_name"]) . '"];' . "\n";
	}	
	//********************
	//********************
	//**** EOF Atribuição de campos para variáveis.
	//********************
	//********************		

	//********************
	//********************
	//**** Insert (Instrução SQL)
	//********************
	//********************		
	$etq .= "\n";
	$etq .= '$strsql = "' . "\n";
	$etq .= "insert " . "\n";
	$etq .= "into " . "\n";
	$etq .= strtolower("$tabela ") . "\n";
	$etq .= "(";
	$etq_prefixo = "";
	$qquery->execute();
	while ($tquery = $qquery->fetch(PDO::FETCH_ASSOC)) {
		$etq .= $etq_prefixo . strval(strtolower($tquery["column_name"])) . "\n";
    $etq_prefixo = ",";
	}
	$etq_prefixo = "";
	$etq .= ") " . "\n";
	$etq .= "values " . "\n";
	$etq .= "(";
	$qquery->execute();
	while ($tquery = $qquery->fetch(PDO::FETCH_ASSOC)) {
	  $etq .= $etq_prefixo . ':V' . strval($tquery["column_name"]) . "\n";
		$etq_prefixo = ",";
	}
	$etq .= ')";' . "\n";
	//********************
	//********************
	//**** EOF Insert (Instrução SQL)
	//********************
	//********************		

	$etq .= '$q' . strtolower(substr($tabela,1)) . ' = $pdo_empresa->prepare($strsql);' . "\n";
	$qquery->execute();
	while ($tquery = $qquery->fetch(PDO::FETCH_ASSOC)) {
		$etq .= '$q' . strtolower(substr($tabela,1)) . '->bindParam(":V' . strval($tquery["column_name"]) .  '", $' . strval(strtolower($tquery["column_name"])) . ');' . "\n";
	}
	$etq .= '$q' . strtolower(substr($tabela,1)) . '->execute();' . "\n";
	$etq .= '$' . strtolower($pk) . ' = $pdo_empresa->lastInsertId();' . "\n";
	$etq .= 'fecha_diario("' . $tabela . '", "' . $pk . '", $' . strtolower($pk) . ', $abre_diario = array(), $campoexcluidos = array(""));' . "\n";

	$etq .= "\n";
	$etq .= '$abre_diario = abre_diario("' . $tabela . '", "' . $pk . '", $' . strtolower($pk) . ', $campoexcluidos = array(""));' . "\n";
	$etq .= '$strsql = "' . "\n";	
	$etq .= "update " . "\n";
	$etq .= strtolower("$tabela ") . "\n";
	$etq .= "set " . "\n";
	$etq_prefixo = "";
	$qquery->execute();
	while ($tquery = $qquery->fetch(PDO::FETCH_ASSOC)) {
    $etq .= $etq_prefixo . strval(strtolower($tquery["column_name"])) . ' = :V' . strval($tquery["column_name"]) . "\n";
		$etq_prefixo = ",";
	}
	$etq .= 'where ' . "\n";
	$etq .= strtolower($pk) . ' = :V' . $pk . "\n";
	$etq .= '";' . "\n";
	$etq .= '$q' . strtolower(substr($tabela,1)) . ' = $pdo_empresa->prepare($strsql);' . "\n";
	$qquery->execute();
	while ($tquery = $qquery->fetch(PDO::FETCH_ASSOC)) {
		$etq .= '$q' . strtolower(substr($tabela,1)) . '->bindParam(":V' . strval($tquery["column_name"]) .  '", $' . strval(strtolower($tquery["column_name"])) . ');' . "\n";
	}
	$etq .= '$q' . strtolower(substr($tabela,1)) . '->execute();' . "\n";
	$etq .= 'fecha_diario("' . $tabela . '", "' . $pk . '", $' . strtolower($pk) . ', $abre_diario, $campoexcluidos = array(""));' . "\n";
	$etq_prefixo = "";
	$etq .= "\n";
	$etq .= '$strsql = "' . "\n";
	$etq .= "select " . "\n";
	$qquery->execute();
	while ($tquery = $qquery->fetch(PDO::FETCH_ASSOC)) {
	  $etq .= $etq_prefixo . strtolower($tabela) . '.' . strval(strtolower($tquery["column_name"])) . "\n";
		$etq_prefixo = ",";
	}
	$etq .= "from " . "\n";
	$etq .= strtolower("$tabela ") . "\n";
	$etq .= "where " . "\n";
	$etq .= strtolower($pk) . ' = :V' . $pk . "\n";
	$etq .= '";' . "\n";
	$etq .= '$q' . strtolower(substr($tabela,1)) . ' = $pdo_empresa->prepare($strsql);' . "\n";
	$etq .= '$q' . strtolower(substr($tabela,1)) . '->bindParam(":V' . $pk . '", $' . strtolower($pk) . ');' . "\n";
	$etq .= '$q' . strtolower(substr($tabela,1)) . '->execute();' . "\n";
	
}
//********************
//********************
//**** EOF Pega Estrutura da Tabela
//********************
//********************	
fecha_empresa();
if ($etq != "") {
	$html .= '<textarea class="form-control form-control-sm" style="font-family: Courier New" id="codigo_autoexec" name="codigo_autoexec" rows="20">' . $etq .  '</textarea>';
}
echo $html;


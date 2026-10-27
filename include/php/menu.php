<?php
/*
**************************************************
**************************************************
***** 
***** APLICAÇÃO
***** Recibo Express
***** Arquivo: menu.php
***** Conteúdo: menu principal
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
$RaizMenu = __DIR__;
$home = "/";
$html_btn_entrar = "";
$html_icone_user = "";
$html_aplicativos = "";
$html_auxiliares = "";
$diretorio = "/publico";
if (isset($_SESSION["CHAVE_USUARIO"])) {
	if ($_SESSION["TIPO_USUARIO"] == "ADMINISTRADOR") {
		$diretorio = "/sistema";
    $home = "/sistema/index.php";
	}
  if ($_SESSION["TIPO_USUARIO"] == "USUARIO") {
    $home = "/publico/usuario/index.php";
  }

}
if (isset($_SESSION["CHAVE_USUARIO"])) {
	if ($_SESSION["CHAVE_USUARIO"] != "") {		    
		if ($_SESSION["TIPO_USUARIO"] == "ADMINISTRADOR") {      
			$html_btn_entrar .= '          <li class="nav-item dropdown" data-bs-theme="light">' . "\n";
			$html_btn_entrar .= '            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">' . "\n";
			$html_btn_entrar .= '              ' . $_SESSION["NOME_USUARIO"] . "\n";
			$html_btn_entrar .= '            </a>' . "\n";
			$html_btn_entrar .= '            <ul class="dropdown-menu">' . "\n";
      $html_btn_entrar .= '              <li><a class="dropdown-item" href="/sistema/index.php">Manu Principal Adm</a></li>' . "\n";			
			$html_btn_entrar .= '              <li><hr class="dropdown-divider"></li>' . "\n";
			$html_btn_entrar .= '              <li><a class="dropdown-item" href="/acesso/logout.php">Sair</a></li>' . "\n";
			$html_btn_entrar .= '            </ul>' . "\n";
			$html_btn_entrar .= '          </li>' . "\n";
			$html_aplicativos .= '
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> Funcionalidades </a>
    			  <ul class="dropdown-menu" data-bs-theme="light">
              <li>
                <a class="dropdown-item" href="/publico/diagnostico/diagnostico_config.php">Diagnóstico</a>
              </li>
            </ul>
          </li>        
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> Aplicativos </a>
    			  <ul class="dropdown-menu" data-bs-theme="light">
              <li>
                <a class="dropdown-item" href="/publico/usuario/index.php">Home Usuário Normal</a>
              </li>
              <li>
                <a class="dropdown-item" href="/sistema/index.php">Home Usuário Administrador</a>
              </li>
              <li>
                <a class="dropdown-item" href="/sistema/usuario/usuario.php">Usuários</a>
              </li>
              <li>
                <a class="dropdown-item" href="/sistema/admin/admin.php">Administradores</a>
              </li>
              <li>
                <a class="dropdown-item" href="/sistema/auxiliares/tabinss/tabinss.php">Tabela de INSS</a>
              </li>
              <li>
                <a class="dropdown-item" href="/sistema/auxiliares/tabirpf/tabirpf.php">Tabela de IRPF</a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item" href="/sistema/cargo/cargo.php">Tabela de Cargos</a>
              </li>
            </ul>
          </li>        
			';
			$html_auxiliares .= '			<li class="nav-item dropdown">' . "\n";
			$html_auxiliares .= '       <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> Auxiliares </a>' . "\n";
			$html_auxiliares .= '       <ul class="dropdown-menu" data-bs-theme="light">' . "\n";
			$html_auxiliares .= '         <li>' . "\n";
			$html_auxiliares .= '           <a class="dropdown-item" href="/sistema/auxiliar/var/var_edita.php">Variáveis do Sistema</a>' . "\n";
			$html_auxiliares .= '         </li>' . "\n";			
			$html_auxiliares .= '       </ul>' . "\n";
			$html_auxiliares .= '     </li>' . "\n";
	  }
	  if ($_SESSION["TIPO_USUARIO"] == "USUARIO") {
      $home = "/publico/usuario/index.php";      
      $html_btn_entrar .= '          <li class="nav-item">' . "\n";
      $html_btn_entrar .= '            <a class="nav-link" href="/publico/usuario/index.php">Diagnóstico</a>' . "\n";
      $html_btn_entrar .= '          </li>' . "\n";
      $html_btn_entrar .= '          <li class="nav-item dropdown" data-bs-theme="light">' . "\n";
      $html_btn_entrar .= '            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">' . "\n";		   
      $html_btn_entrar .= '              ' . $_SESSION["NOME_USUARIO"] . "\n";
      $html_btn_entrar .= '            </a>' . "\n";	
      $html_btn_entrar .= '            <ul class="dropdown-menu">' . "\n";
      $html_btn_entrar .= '              <li><a class="dropdown-item" href="/publico/diagnostico/diagnostico_config.php">Meu Diagnóstico</a></li>' . "\n";
      $html_btn_entrar .= '              <li><a class="dropdown-item" href="/publico/usuario/usuario_config.php">Minha Conta</a></li>' . "\n";      
      $html_btn_entrar .= '              <li><hr class="dropdown-divider"></li>' . "\n";
      $html_btn_entrar .= '              <li><a class="dropdown-item" href="/acesso/logout.php">Sair</a></li>' . "\n";
      $html_btn_entrar .= '            </ul>' . "\n";
      $html_btn_entrar .= '          </li>' . "\n";		   
	  }
  }
}

if ($html_btn_entrar == "") {
	//$html_btn_entrar .= "
  //<li class='nav-item'>\n
  //  <button type='button' class='btn btn-sm btn-light my-2 ml-2 my-sm-0' onclick=\"javascript:location='/publico/usuario/usuario_login.php'\">Entrar</button>\n
  //</li>\n";
	$html_btn_entrar = "<span class='text-end' style='color: #9AA6AE; font-size: 0.75em; font-weight:700'>{$Titulo}</span>\n";
}
/*
if (isset($_SESSION["TIPO_USUARIO"])) {	
	if ($_SESSION["TIPO_USUARIO"] == "USUARIO" || $_SESSION["TIPO_USUARIO"] == "ADMINISTRADOR") {
		if (isset($BaseDados['usuario'])) {
			$pdo = new PDO($StrConexao, $BaseDados['usuario'], $BaseDados['senha']);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
	}
}
*/
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    <a class="navbar-brand" href="/">
      <img src="/identidade/logo-reciboexpress-texto.svg" style="max-width:200px" alt="Recibo Express" class="d-inline-block align-text-top">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      </ul>
      <?php echo $html_aplicativos; ?>
      <?php echo $html_btn_entrar; ?>
      <?php echo ($html_icone_user != "") ? $html_icone_user : ""; ?>
<!--      
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"/>
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
-->      
    </div>
  </div>
</nav>




<div class="container">
  <hr>
</div>
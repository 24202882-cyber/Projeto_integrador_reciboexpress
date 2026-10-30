<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: menu_lateral.php
 ***** Conteúdo: menu principal lateral
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
$html_admin = "";
if (isset($_SESSION["TIPO_USUARIO"])) {
  if ($_SESSION["TIPO_USUARIO"] === "ADMINISTRADOR") {
    $html_admin = "";
    $html_admin .= "<li>";
    $html_admin .= "  <a href='/admin' class='nav-link rounded-0'>";
    $html_admin .= "    <span class='text-nowrap'><i class='ci ci-gear fs-6 me-2'></i> Administração</span>";    
    $html_admin .= "  </a>";
    $html_admin .= "</li>";
  }
}
?>
<!-- BARRA LATERAL (SIDEBAR) -->
<div vw class="enabled">
  <div vw-access-button class="active"></div>
  <div vw-plugin-wrapper>
    <div class="plugin-wrapper"></div>
  </div>
</div>
<script>
  new window.VLibras.Widget('https://vlibras.gov.br/app');
</script> 
<aside class="col-12 col-md-3 col-lg-2 d-flex flex-column ps-0 menu-lateral">
  <div class="pt-4 ps-3 mb-3">
    <a href="#" class="d-flex align-items-center text-white text-decoration-none fw-bold">
      <a class="navbar-brand mb-3" href="/index.php"><img src="/identidade/logo-reciboexpress-bg.svg" class="img-fluid" style="max-width:200px" /></a>
    </a>
  </div>
  <div class="pt-4 ps-3 mb-1">
    <span class="texto-pirulito-menu">PRINCIPAL</span>
  </div>
  <ul class="nav flex-column ps-0">
    <li>
      <a href="/publico/geral" class="nav-link rounded-0" aria-current="page">
        <span class="text-nowrap"><i class="ci ci-menu-visao-geral fs-6 me-2"></i> Visão Geral</span>
      </a>
    </li>
    <li>
      <a href="/publico/orcamento" class="nav-link rounded-0">
        <span class="text-nowrap"><i class="ci ci-menu-orcamentos fs-6 me-2"></i> Orçamentos</span>
      </a>
    </li>
    <li>
      <a href="/publico/recibo" class="nav-link rounded-0">        
        <span class="text-nowrap"><i class="ci ci-menu-recibos fs-6 me-2"></i> Recibos</span>
      </a>
    </li>
    <li>
      <a href="/publico/historico" class="nav-link rounded-0">
        <span class="text-nowrap"><i class="ci ci-menu-historico fs-6 me-2"></i> Histórico</span>
      </a>
    </li>
  </ul>
  <div class="pt-4 ps-3 mb-1">
    <span class="texto-pirulito-menu">CADASTROS</span>
  </div>
  <ul class="nav flex-column ps-0">
    <li>
      <a href="/publico/cliente" class="nav-link rounded-0">
        <span class="text-nowrap"><i class="ci ci-menu-clientes fs-6 me-2"></i> Clientes</span>
      </a>
    </li>
    <li>
      <a href="/publico/servico" class="nav-link rounded-0">
        <span class="text-nowrap"><i class="ci ci-menu-servicos fs-6 me-2"></i> Serviços</span>
      </a>
    </li>
    <li>
      <a href="/publico/perfil" class="nav-link rounded-0">
        <span class="text-nowrap"><i class="ci ci-menu-perfil fs-6 me-2"></i> Meu Perfil</span>
      </a>
    </li>
    <?php echo $html_admin ?>    
  </ul>
  <hr class="text-white-50">
  <div class="ps-3">
    <div class="rounded-3 text-center pt-2" style="max-width:36px; max-height: 36px; min-width:36px; min-height: 36px; background-color: #EAF1F8; float:left">
      <span class="texto-negrito" style="color:#163B5C"><?php echo LetraUserComprac(); ?></span>
    </div>
    <span class="texto-menor texto-negrito ps-2"><?php echo NomeUserComprac(); ?></span><br>
    <span class="texto-menor texto-suave ps-2"><?php echo CPFUserComprac(); ?></span>
  </div>
</aside>
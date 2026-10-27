<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: menu_lateral.php
 ***** Conteúdo: menu lateral painel admin
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
?>
<!-- BARRA LATERAL (SIDEBAR) -->
<aside class="col-12 col-md-3 col-lg-2 d-flex flex-column ps-0 menu-admin-lateral">
  <div class="pt-4 ps-3 mb-3">
    <a href="#" class="d-flex align-items-center text-white text-decoration-none fw-bold">
      <a class="navbar-brand mb-3" href="/index.php"><img src="/identidade/logo-admin.svg" class="img-fluid" style="max-width:200px" /></a>
    </a>
  </div>
  <div class="pt-4 ps-3 mb-1">
    <span class="texto-pirulito-menu">PRINCIPAL</span>
  </div>
  <ul class="nav flex-column ps-0">
    <li>
      <a href="/admin" class="nav-link rounded-0" aria-current="page">
        <span class="text-nowrap"><i class="ci ci-menu-visao-geral fs-6 me-2"></i> Visão Geral</span>
      </a>
    </li>
    <li>
      <a href="/admin/db" class="nav-link rounded-0">
        <span class="text-nowrap"><i class="ci ci-menu-banco-dados fs-6 me-2"></i> Banco de Dados</span>
      </a>
    </li>
    <li>
      <a href="/admin/nuvem" class="nav-link rounded-0">        
        <span class="text-nowrap"><i class="ci ci-menu-armazenamento fs-6 me-2"></i> Armazenamento</span>
      </a>
    </li>
    <li>
      <a href="/admin/testes" class="nav-link rounded-0">
        <span class="text-nowrap"><i class="ci ci-menu-testes fs-6 me-2"></i> Testes</span>
      </a>
    </li>
  </ul>
  <hr class="text-white-50">
  <ul class="nav flex-column ps-0">
    <li>
      <a href="/publico/cliente" class="nav-link rounded-0">
        <span class="text-nowrap"><i class="bi bi-chevron-left fs-6 me-2"></i> Voltar ao app</span>
      </a>
    </li>
  </ul>  
</aside>
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
?>
<cabecalho class="d-flex justify-content-between align-items-center border-bottom main-content" style="--bs-border-color:#DDE4E9">
  <div class="d-flex align-items-center gap-3">
    <button class="btn btn-sm btn-outline-secondary" id="BTN_MENU_SUPERIOR_VOLTAR" name="BTN_MENU_SUPERIOR_VOLTAR" onclick="javascript:location='/';">
      <span class="text-nowrap"><i class="bi bi-chevron-left fs-6"></i> Voltar</span>
    </button>
    <i class="bi bi-search"></i>
    <input type="text" class="form-control form-control-sm" style="min-width: 300px;" id="exampleGhostInput" placeholder="Buscar recibo, cliente ou valor">
  </div>
  <?php
  if (isset($_SESSION["CHAVE_PERFIL"])) {
    if ($_SESSION["CHAVE_PERFIL"] != "") {
      echo "<button type='button' class='btn btn-sm btn-light texto-negrito' id='BTN_LOGOUT' name='BTN_LOGOUT' onclick=\"javascript:location='/entrar/sair'\"><span style='color: #163B5C;'>Sair</span></button>";
    }
  }?>
</cabecalho>
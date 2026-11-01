<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /publico/cliente/cliente_edita.php
 ***** Conteúdo: 05-cliente-edita
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
// Variáveis de controle
$ok = true;
$html = "";
$html_tit = "";
$html_msg = "";
$html_lnk = "";
$html_btn = "";
// EOF Variáveis de controle
$aftergopage = "";
$acho = "EDITA";
$chave_perfil = $_SESSION["CHAVE_PERFIL"];
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $chave_cliente = $_POST["chave_cliente"];
  if (isset($_POST["acao"])) {
    $acao = $_POST["acao"];
  }
  if ($acao == "GRAVAR" && $chave_cliente == "NOVO") {
    $cod_cliente = "";
    $nome_cliente = $_POST["nome_cliente"];
    $end_cliente = $_POST["end_cliente"];
    $num_cliente = $_POST["num_cliente"];
    $compl_cliente = $_POST["compl_cliente"];
    $bairro_cliente = $_POST["bairro_cliente"];
    $cid_cliente = $_POST["cid_cliente"];
    $uf_cliente = $_POST["uf_cliente"];
    $cep_cliente = $_POST["cep_cliente"];
    $codibge_cliente = $_POST["codibge_cliente"];
    $cpf_cliente = $_POST["cpf_cliente"];
    $tel_cliente = $_POST["tel_cliente"];
    $email_cliente = $_POST["email_cliente"];
    //$dtn_cliente = $_POST["dtn_cliente"];
    //$dtc_cliente = $_POST["dtc_cliente"];
    $caixa_cliente = "1";

    abre_db();
    $strsql = "
    insert 
    into 
    tcliente 
    (chave_perfil
    ,cod_cliente
    ,nome_cliente
    ,end_cliente
    ,num_cliente
    ,compl_cliente
    ,bairro_cliente
    ,cid_cliente
    ,uf_cliente
    ,cep_cliente
    ,codibge_cliente
    ,cpf_cliente
    ,tel_cliente
    ,email_cliente
    ,caixa_cliente
    ) 
    values 
    (:vchave_perfil
    ,:vcod_cliente
    ,:vnome_cliente
    ,:vend_cliente
    ,:vnum_cliente
    ,:vcompl_cliente
    ,:vbairro_cliente
    ,:vcid_cliente
    ,:vuf_cliente
    ,:vcep_cliente
    ,:vcodibge_cliente
    ,:vcpf_cliente
    ,:vtel_cliente
    ,:vemail_cliente
    ,:vcaixa_cliente
    )";
    $qcliente = $pdo->prepare($strsql);
    $qcliente->bindParam(":vchave_perfil", $chave_perfil);
    $qcliente->bindParam(":vcod_cliente", $cod_cliente);
    $qcliente->bindParam(":vnome_cliente", $nome_cliente);
    $qcliente->bindParam(":vend_cliente", $end_cliente);
    $qcliente->bindParam(":vnum_cliente", $num_cliente);
    $qcliente->bindParam(":vcompl_cliente", $compl_cliente);
    $qcliente->bindParam(":vbairro_cliente", $bairro_cliente);
    $qcliente->bindParam(":vcid_cliente", $cid_cliente);
    $qcliente->bindParam(":vuf_cliente", $uf_cliente);
    $qcliente->bindParam(":vcep_cliente", $cep_cliente);
    $qcliente->bindParam(":vcodibge_cliente", $codibge_cliente);
    $qcliente->bindParam(":vcpf_cliente", $cpf_cliente);
    $qcliente->bindParam(":vtel_cliente", $tel_cliente);
    $qcliente->bindParam(":vemail_cliente", $email_cliente);
    $qcliente->bindParam(":vcaixa_cliente", $caixa_cliente);
    $qcliente->execute();
    $chave_cliente = $pdo->lastInsertId();
    fecha_diario("tcliente", "chave_cliente", $chave_cliente, $abre_diario = array(), $campoexcluidos = array(""));
    header("Location: /publico/cliente");
    die();
  }
  else {
    //echo "aqui " . $chave_cliente . ' ' . $acao;
    //die();
    if ($chave_cliente != "NOVO" && $chave_cliente != "" && $acao == "GRAVAR") {
      $nome_cliente = $_POST["nome_cliente"];
      $end_cliente = $_POST["end_cliente"];
      $num_cliente = $_POST["num_cliente"];
      $compl_cliente = $_POST["compl_cliente"];
      $bairro_cliente = $_POST["bairro_cliente"];
      $cid_cliente = $_POST["cid_cliente"];
      $uf_cliente = $_POST["uf_cliente"];
      $cep_cliente = $_POST["cep_cliente"];
      $codibge_cliente = $_POST["codibge_cliente"];
      $cpf_cliente = $_POST["cpf_cliente"];
      $tel_cliente = $_POST["tel_cliente"];
      $email_cliente = $_POST["email_cliente"];

      abre_db();
      $abre_diario = abre_diario("tcliente", "chave_cliente", $chave_cliente, $campoexcluidos = array(""));
      $strsql = "
      update 
      tcliente 
      set 
      nome_cliente = :vnome_cliente
      ,end_cliente = :vend_cliente
      ,num_cliente = :vnum_cliente
      ,compl_cliente = :vcompl_cliente
      ,bairro_cliente = :vbairro_cliente
      ,cid_cliente = :vcid_cliente
      ,uf_cliente = :vuf_cliente
      ,cep_cliente = :vcep_cliente
      ,codibge_cliente = :vcodibge_cliente
      ,cpf_cliente = :vcpf_cliente
      ,tel_cliente = :vtel_cliente
      ,email_cliente = :vemail_cliente      
      where 
      chave_cliente = :vchave_cliente and 
      chave_perfil = :vchave_perfil and 
      caixa_cliente = 1
      ";
      $qcliente = $pdo->prepare($strsql);
      $qcliente->bindParam(":vchave_cliente", $chave_cliente);
      $qcliente->bindParam(":vchave_perfil", $chave_perfil);
      $qcliente->bindParam(":vnome_cliente", $nome_cliente);
      $qcliente->bindParam(":vend_cliente", $end_cliente);
      $qcliente->bindParam(":vnum_cliente", $num_cliente);
      $qcliente->bindParam(":vcompl_cliente", $compl_cliente);
      $qcliente->bindParam(":vbairro_cliente", $bairro_cliente);
      $qcliente->bindParam(":vcid_cliente", $cid_cliente);
      $qcliente->bindParam(":vuf_cliente", $uf_cliente);
      $qcliente->bindParam(":vcep_cliente", $cep_cliente);
      $qcliente->bindParam(":vcodibge_cliente", $codibge_cliente);
      $qcliente->bindParam(":vcpf_cliente", $cpf_cliente);
      $qcliente->bindParam(":vtel_cliente", $tel_cliente);
      $qcliente->bindParam(":vemail_cliente", $email_cliente);
      $qcliente->execute();
      fecha_diario("tcliente", "chave_cliente", $chave_cliente, $abre_diario, $campoexcluidos = array(""));    
      header("Location: /publico/cliente");
      die();
    }
  }
}

$cod_cliente = "";
$nome_cliente = "";
$end_cliente = "";
$num_cliente = "";
$compl_cliente = "";
$bairro_cliente = "";
$cid_cliente = "";
$uf_cliente = "";
$cep_cliente = "";
$codibge_cliente = "";
$cpf_cliente = "";
$tel_cliente = "";
$email_cliente = "";
//$dtn_cliente = date;
//$dtc_cliente = timestamp;
$caixa_cliente = 1;
$acao = "GRAVAR";

abre_db();
$strsql = "
select 
tcliente.* 
from 
tcliente
where 
tcliente.chave_cliente = :vchave_cliente and 
tcliente.chave_perfil = :vchave_perfil and 
tcliente.caixa_cliente = 1
";
$qcliente = $pdo->prepare($strsql);
$qcliente->bindParam(":vchave_cliente", $chave_cliente);
$qcliente->bindParam(":vchave_perfil", $chave_perfil);
$qcliente->execute();
if ($tcliente = $qcliente->fetch(PDO::FETCH_ASSOC)) {  
  $nome_cliente = $tcliente["nome_cliente"];
  $end_cliente = $tcliente["end_cliente"];
  $num_cliente = $tcliente["num_cliente"];
  $compl_cliente = $tcliente["compl_cliente"];
  $bairro_cliente = $tcliente["bairro_cliente"];
  $cid_cliente = $tcliente["cid_cliente"];
  $uf_cliente = $tcliente["uf_cliente"];
  $cep_cliente = $tcliente["cep_cliente"];
  $cpf_cliente = $tcliente["cpf_cliente"];
  $tel_cliente = $tcliente["tel_cliente"];
  $email_cliente = $tcliente["email_cliente"];

}
else {  
  $nome_cliente = "";
  $end_cliente = "";
  $num_cliente = "";
  $compl_cliente = "";
  $bairro_cliente = "";
  $cid_cliente = "";
  $uf_cliente = "";
  $cep_cliente = "";
  $cpf_cliente = "";
  $tel_cliente = "";
  $email_cliente = "";
}




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Recibo Express - Cadastro de Clientes</title>
  <?php include($Raiz . "include/html/header.html"); ?>
</head>

<script type="text/javascript">
  function pesquisacep(vcep) {
    var vok = true;
    var vcodibge_cliente = document.getElementById('codibge_cliente').value;
    var vcep_cliente_pesquisado = document.getElementById('cep_cliente_pesquisado').value;
    if (vcep_cliente_pesquisado.length != 9) {
      vcep_cliente_pesquisado = "";
    }
    vcep = vcep.trim();
    vcodibge_cliente = vcodibge_cliente.trim();
    if (vcep == "") {
      vok = false;
    }
    if (vok) {
      vcep = vcep.replace(/\D/g, "");
      if (vcep.length != 8) {
        vok = false;
      }
    }
    if (vok) {
      vcep_cliente_pesquisado = vcep_cliente_pesquisado.replace(/\D/g, "");
      if (vcep == vcep_cliente_pesquisado && vcodibge_cliente.length == 7) {
        vok = false;
      }
    }
    if (vok) {
      var validacep = /^[0-9]{8}$/;
      if (validacep.test(vcep)) {
        //Preenche os campos com "..." enquanto consulta webservice.
        if (document.getElementById('end_cliente').value == "") {
          document.getElementById('end_cliente').value = "...";
        }
        if (document.getElementById('bairro_cliente').value == "") {
          document.getElementById('bairro_cliente').value = "...";
        }
        if (document.getElementById('cid_cliente').value == "") {
          document.getElementById('cid_cliente').value = "...";
        }
        if (document.getElementById('uf_cliente').value == "") {
          document.getElementById('uf_cliente').value = "...";
        }
        if (document.getElementById('codibge_cliente').value == "") {
          document.getElementById('codibge_cliente').value = "...";
        }
        document.getElementById('cep_cliente_pesquisado').value = document.getElementById('cep_cliente').value;
        //Cria um elemento javascript.
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "https://viacep.com.br/ws/" + vcep + "/json/", true);
        xhr.setRequestHeader("Accept", "application/json");
        xhr.onreadystatechange = function () {
          if ((xhr.readyState == 0 || xhr.readyState == 4) && xhr.status == 200) {
            xhr = JSON.parse(xhr.responseText);
            var vcep_formatado = xhr.cep;
            if (vcep_formatado != "") {
              vcep_formatado = vcep_formatado.replace(/(\d{5})(\d)/, '$1-$2');
            }
            document.getElementById('cep_cliente').value = vcep_formatado;
            document.getElementById('end_cliente').value = xhr.logradouro.toUpperCase();
            document.getElementById('bairro_cliente').value = xhr.bairro.toUpperCase();
            document.getElementById('cid_cliente').value = xhr.localidade.toUpperCase();
            document.getElementById('uf_cliente').value = xhr.uf.toUpperCase();
            document.getElementById('codibge_cliente').value = xhr.ibge;
            document.getElementById('cid_cliente').value = xhr.localidade.toUpperCase() + ' / ' + xhr.uf.toUpperCase();
            document.getElementById('end_cliente').value = xhr.logradouro.toUpperCase() + ', ' + xhr.bairro.toUpperCase();
          };
        };
        xhr.send(null);
      }
      else {
        vok = false;
        limpa_formulário_cep();
      }
    }
    if (!vok) {
      const vcep_cliente_feedback = document.getElementById("cep_cliente_feedback");
      vcep_cliente_feedback.textContent = "Conteúdo inválido! Informe um CEP.";
      document.getElementById('cep_cliente').setAttribute('class', 'form-control is-invalid');
      document.getElementById('cep_cliente_feedback').setAttribute('class', 'invalid-feedback');
    }
    else {
      const vcep_cliente_feedback = document.getElementById("cep_cliente_feedback");
      vcep_cliente_feedback.textContent = "";
      document.getElementById('cep_cliente').setAttribute('class', 'form-control');
      document.getElementById('cep_cliente_feedback').setAttribute('class', 'd-none');
    }
    return;
  };

  function limpa_formulário_cep() {
    document.getElementById('end_cliente').value = "";
    document.getElementById('bairro_cliente').value = "";
    document.getElementById('cid_cliente').value = "";
    document.getElementById('uf_cliente').value = "";
    document.getElementById('codibge_cliente').value = "";
  }

  function verificaNOME(ccampo) {
    var vok = true;
    var cnome = ccampo.value;
    cnome = cnome.trim();
    if (cnome == "") {
      const vnome_cliente_feedback = document.getElementById("nome_cliente_feedback");
      vnome_cliente_feedback.textContent = "Conteúdo inválido! Informe um nome.";
      document.getElementById('nome_cliente').setAttribute('class', 'form-control is-invalid');
      document.getElementById('nome_cliente_feedback').setAttribute('class', 'invalid-feedback');
      vok = false;
    }
    if (vok) {
      document.getElementById('nome_cliente').setAttribute('class', 'form-control');
      document.getElementById('nome_cliente_feedback').setAttribute('class', 'd-none');
    }
  }

  function verificaTEL(ccampo) {
    var vok = true;
    var ctel = ccampo.value;
    ctel = ctel.trim();
    if (ctel == "") {
      const vtel_cliente_feedback = document.getElementById("tel_cliente_feedback");
      vtel_cliente_feedback.textContent = "Conteúdo inválido! Informe um Telefone / WhatsApp.";
      document.getElementById('tel_cliente').setAttribute('class', 'form-control is-invalid');
      document.getElementById('tel_cliente_feedback').setAttribute('class', 'invalid-feedback');
      vok = false;
    }
    if (vok) {
      document.getElementById('tel_cliente').setAttribute('class', 'form-control');
      document.getElementById('tel_cliente_feedback').setAttribute('class', 'd-none');
    }
  }

  async function verificaEMAIL(ccampo) {
    let cemail = ccampo.value;
    cemail = cemail.trim();
    if (cemail == "") {
      const vemail_cliente_feedback = document.getElementById("email_cliente_feedback");
      vemail_cliente_feedback.textContent = "Conteúdo inválido! Informe um e-mail.";
      document.getElementById('email_cliente').setAttribute('class', 'form-control is-invalid');
      document.getElementById('email_cliente_feedback').setAttribute('class', 'invalid-feedback');
      return;
    }
    try {
      const cchave_cliente = document.getElementById("chave_cliente").value;
      const response = await fetch('cadastro_ajax.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          chave_cliente: cchave_cliente,
          email_cliente: cemail
        }),
      });
      const data = await response.json();
      console.log(data); // Loga os dados já processados do JSON
      if (data.retorno === "falha") {
        const vemail_cliente_feedback = document.getElementById("email_cliente_feedback");
        vemail_cliente_feedback.textContent = "E-mail inválido ou já cadastrado.";
        document.getElementById('email_cliente').className = 'form-control is-invalid';
        document.getElementById('email_cliente_feedback').className = 'invalid-feedback';
        document.getElementById("BTN_CONFIRMAR").disabled = true;
      } else {
        // Sucesso na validação do servidor
        document.getElementById('email_cliente').className = 'form-control';
        document.getElementById('email_cliente_feedback').className = 'd-none';
        document.getElementById("BTN_CONFIRMAR").disabled = false;
      }
    } catch (error) {
      console.error('Erro na requisição:', error);
    }
  }

  function verificaCEP(ccampo) {
    var vok = true;
    var ccep = ccampo.value;
    ccep = ccep.trim();
    if (ccep == "") {
      const vcep_cliente_feedback = document.getElementById("cep_cliente_feedback");
      vcep_cliente_feedback.textContent = "Conteúdo inválido! Informe um CEP.";
      document.getElementById('cep_cliente').setAttribute('class', 'form-control is-invalid');
      document.getElementById('cep_cliente_feedback').setAttribute('class', 'invalid-feedback');
      vok = false;
    }
    if (vok) {
      ccep = ccep.replace(/\D/g, "");
      if (ccep.length != 8) {
        vok = false;
      }
    }
    if (!vok) {
      const vcep_cliente_feedback = document.getElementById("cep_cliente_feedback");
      vcep_cliente_feedback.textContent = "Conteúdo inválido! Informe um CEP.";
      document.getElementById('cep_cliente').setAttribute('class', 'form-control');
      document.getElementById('cep_cliente_feedback').setAttribute('class', 'd-none');
    }
  }

  function verificaCID(ccampo) {
    var vok = true;
    var ccid = ccampo.value;
    ccid = ccid.trim();
    if (ccid == "") {
      const vcid_cliente_feedback = document.getElementById("cid_cliente_feedback");
      vcid_cliente_feedback.textContent = "Conteúdo inválido! Informe uma cidade e UF.";
      document.getElementById('cid_cliente').setAttribute('class', 'form-control is-invalid');
      document.getElementById('cid_cliente_feedback').setAttribute('class', 'invalid-feedback');
      vok = false;
    }
    if (vok) {
      document.getElementById('cid_cliente').setAttribute('class', 'form-control');
      document.getElementById('cid_cliente_feedback').setAttribute('class', 'd-none');
    }
  }

  function verificaEND(ccampo) {
    var vok = true;
    var cend = ccampo.value;
    cend = cend.trim();
    if (cend == "") {
      const vend_cliente_feedback = document.getElementById("end_cliente_feedback");
      vend_cliente_feedback.textContent = "Conteúdo inválido! Informe uma endade e UF.";
      document.getElementById('end_cliente').setAttribute('class', 'form-control is-invalid');
      document.getElementById('end_cliente_feedback').setAttribute('class', 'invalid-feedback');
      vok = false;
    }
    if (vok) {
      document.getElementById('end_cliente').setAttribute('class', 'form-control');
      document.getElementById('end_cliente_feedback').setAttribute('class', 'd-none');
    }
  }

  //********************
  //********************
  //**** Pesquisa CPF
  //********************
  //********************
  async function verificaCPF(ccampo) {
    let ccpf = ccampo.value;
    // 1. Validação local (Formato)
    if (!isCpf(ccpf)) {
      const vcpf_cliente_feedback = document.getElementById("cpf_cliente_feedback");
      vcpf_cliente_feedback.textContent = "Conteúdo inválido! Informe um número válido de CPF.";
      document.getElementById('cpf_cliente').className = 'form-control is-invalid';
      document.getElementById('cpf_cliente_feedback').className = 'invalid-feedback';
      return; // Interrompe a execução caso seja inválido
    }
    // Formata o CPF no campo
    ccpf = formatCpfCnpj(ccpf, true, isCnpj(ccpf));
    ccampo.value = ccpf;
    // 2. Validação no Servidor (AJAX Assíncrono)
    try {
      const cchave_cliente = document.getElementById("chave_cliente").value;
      const response = await fetch('cadastro_ajax.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          chave_cliente: cchave_cliente,
          cpf_cliente: ccpf
        }),
      });
      const data = await response.json();
      console.log(data); // Loga os dados já processados do JSON
      if (data.retorno === "falha") {
        const vcpf_cliente_feedback = document.getElementById("cpf_cliente_feedback");
        vcpf_cliente_feedback.textContent = "CPF inválido ou já cadastrado.";
        document.getElementById('cpf_cliente').className = 'form-control is-invalid';
        document.getElementById('cpf_cliente_feedback').className = 'invalid-feedback';
        document.getElementById("BTN_CONFIRMAR").disabled = true;
      } else {
        // Sucesso na validação do servidor
        document.getElementById('cpf_cliente').className = 'form-control';
        document.getElementById('cpf_cliente_feedback').className = 'd-none';
        document.getElementById("BTN_CONFIRMAR").disabled = false;
      }
    } catch (error) {
      console.error('Erro na requisição:', error);
    }
  }
  //********************
  //********************
  //**** EOF Pesquisa CPF
  //********************
  //********************


  function mascaraTEL(input) {
    // 1. Remove tudo que não for dígito
    let valor = input.value.replace(/\D/g, '');
    // 2. Limita a no máximo 11 dígitos
    valor = valor.substring(0, 11);
    // 3. Aplica a formatação dinamicamente
    if (valor.length > 10) {
      // 11 dígitos (Celular): (XX) XXXXX-XXXX
      valor = valor.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
    } else if (valor.length > 6) {
      // 7 a 10 dígitos (Fixo ou Celular em digitação): (XX) XXXX-XXXX
      valor = valor.replace(/^(\d{2})(\d{4,5})(\d{4})$/, '($1) $2-$3');
    } else if (valor.length > 2) {
      // 3 a 6 dígitos: (XX) XXXX...
      valor = valor.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
    } else if (valor.length > 0) {
      // 1 a 2 dígitos: (XX...
      valor = valor.replace(/^(\d{0,2})/, '($1');
    }
    input.value = valor;
  }
</script>


<body>
  <div class="container">
    <div class="row">
      <?php include($Raiz . "include/php/menu_lateral.php"); ?>
      <!-- CONTEÚDO PRINCIPAL DA PÁGINA -->
      <main class="col-12 col-md-9 col-lg-10">
        <!-- Cabeçalho da Página -->
        <?php include($Raiz . "include/php/menu_superior.php"); ?>
        <div class="main-content">
          <div class="col-12 mt-3">
            <span class="texto11">CADASTROS . CLIENTES</span>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <span style="color:#1E2933; font-weight:700; font-size: 1.75em;">Cadastro de Clientes</span><br>
              <span class="texto-corpo">Gerencie os dados cadastrais, contato e histórico de serviços dos seus clientes.</span>
            </div>
          </div>
          <form method="post" class="needs-validation" id="FCLIENTE_EDITA" name="FCLIENTE_EDITA" action="cliente_edita.php" novalidate>
            <div class="row mt-3">
              <div class="col-12">
                <div class="card rounded-3 border-1 p-4" style="background-color: #FFF; border-color: #CCC;">
                  <span class="texto12 mb-3">DADOS DO CLIENTE</span>
                  <div class="row mb-3">
                    <div class="col-12">
                      <label for="nome_cliente" class="form-label mb-1 texto-campo">NOME COMPLETO DO CLIENTE</label>
                      <input type="hidden" id="acao" name="acao" value="<?php echo $acao ?>">
                      <input type="hidden" id="chave_cliente" name="chave_cliente" value="<?php echo $chave_cliente ?>">
                      <input type="text" class="form-control" id="nome_cliente" name="nome_cliente" value="<?php echo $nome_cliente ?>" placeholder="Nome completo" onblur="verificaNOME(this)" aria-describedby="nome_cliente_feedback" required>
                      <div id="nome_cliente_feedback" class="d-none">
                        Nome inválido
                      </div>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-6">
                      <label for="cpf_cliente" class="form-label mb-1 texto-campo">CPF</label>
                      <input type="text" class="form-control" id="cpf_cliente" name="cpf_cliente" value="<?php echo $cpf_cliente ?>" placeholder="CPF" onblur="verificaCPF(this)" aria-describedby="cpf_cliente_feedback" required>
                      <div id="cpf_cliente_feedback" class="d-none">
                        CPF inválido.
                      </div>
                    </div>
                    <div class="col-6">
                      <label for="tel_cliente" class="form-label mb-1 texto-campo">TELEFONE / WHATSAPP</label>
                      <input type="text" class="form-control" id="tel_cliente" name="tel_cliente" value="<?php echo $tel_cliente ?>" placeholder="Telefone ou WhatsApp" onblur="mascaraTEL(this); verificaTEL(this)" aria-describedby="tel_cliente_feedback" required>
                      <div id="tel_cliente_feedback" class="d-none">
                        Telefone / WhatsApp inválido.
                      </div>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-12">
                      <label for="email_cliente" class="form-label mb-1 texto-campo">E-MAIL</label>
                      <input type="email" class="form-control" id="email_cliente" name="email_cliente" value="<?php echo $email_cliente ?>" placeholder="E-mail" onblur="verificaEMAIL(this)" aria-describedby="email_cliente_feedback" required>
                      <div id="email_cliente_feedback" class="d-none">
                        E-mail inválido.
                      </div>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-6">
                      <label for="cep_cliente" class="form-label mb-1 texto-campo">CEP</label>
                      <input type="hidden" id="codibge_cliente" name="codibge_cliente" value="">
                      <input type="hidden" id="cep_cliente_pesquisado" name="cep_cliente_pesquisado" value="">
                      <input type="text" class="form-control" id="cep_cliente" name="cep_cliente" value="<?php echo $cep_cliente ?>" placeholder="CEP" value="" onblur="javascript:pesquisacep(this.value);" aria-describedby="cep_cliente_feedback" required>
                      <div id="cep_cliente_feedback" class="d-none">
                        CEP inválido.
                      </div>
                    </div>
                    <div class="col-6">
                      <label for="cid_cliente" class="form-label mb-1 texto-campo">CIDADE / UF</label>
                      <input type="text" class="form-control" id="cid_cliente" name="cid_cliente" value="<?php echo $cid_cliente ?>" placeholder="Cidade - UF" onblur="verificaCID(this)" aria-describedby="cid_cliente_feedback" required>
                      <div id="cid_cliente_feedback" class="d-none">
                        Cidade inválido.
                      </div>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-12">
                      <label for="end_cliente" class="form-label mb-1 texto-campo">ENDEREÇO (RUA, NÚMERO, COMPLEMENTO/BAIRRO)</label>
                      <input type="hidden" id="num_cliente" name="num_cliente" value="">
                      <input type="hidden" id="compl_cliente" name="compl_cliente" value="">
                      <input type="hidden" id="bairro_cliente" name="bairro_cliente" value="">
                      <input type="hidden" id="uf_cliente" name="uf_cliente" value="">
                      <input type="text" class="form-control" id="end_cliente" name="end_cliente" value="<?php echo $end_cliente ?>" placeholder="Endereço (Rua, nº, complemento, bairro)" onblur="verificaEND(this)" aria-describedby="end_cliente_feedback" required>
                      <div id="end_cliente_feedback" class="d-none">
                        Endereço inválido.
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="row mt-3">
                    <div class="col-12">
                      <div class="row">
                        <div class="col-12 mt-2 mb-2">
                          <div class="d-flex align-items-center justify-content-between">
                            <span class="texto-pirulito">HISTÓRICO DE SERVIÇOS</span>
                            <button class="btn btn-outline-secondary ms-auto">
                              <i class="ci ci-acao-novo-cliente"></i> Gerar novo recibo
                            </button>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12 mb-2">
                          <div class="card">
                            <div class="card-body">
                              <div class="d-flex align-items-center gap-2">
                                <div class="p-2 rounded shadow-sm" style="background-color:#E7EEFE">
                                  <i class="ci ci-recibo"></i>
                                </div>
                                <div>
                                  <span class="texto15">Recibo #0142</span>
                                  <span class="texto-regular">15/05/2024</span>
                                  <span class="badge badge-subtle theme-success">Pago</span><br>
                                  <span class="texto12-regular">Instalação Elétrica Residencial e Quadro de Disjuntores</span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12 mb-2">
                          <div class="card">
                            <div class="card-body">
                              <div class="d-flex align-items-center gap-2">
                                <div class="p-2 rounded shadow-sm" style="background-color:#E7EEFE">
                                  <i class="ci ci-recibo"></i>
                                </div>
                                <div>
                                  <span class="texto15">Recibo #0098</span>
                                  <span class="texto-regular">02/03/2024</span>
                                  <span class="badge badge-subtle theme-success">Pago</span><br>
                                  <span class="texto12-regular">Manutenção Preventiva de Ar-Condicionado (2 aparelhos)</span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-12">
                <button type="submit" class="btn btn-custom" id="BTN_CONFIRMAR" name="BTN_CONFIRMAR">Salvar Cliente</button>
                <button type="button" class="btn btn-outline-secondary" id="BTN_CANCELAR" name="BTN_CANCELAR" onclick="javascript:location='/publico/cliente';">Cancelar</button>
                <button type="button" class="btn btn-outline-danger float-end"><i class="ci ci-acao-excluir2"></i> Excluir Cliente</button>

              </div>
            </div>
          </form>
        </div>
        <!-- FIM DA ÁREA DE CONTEÚDO -->
      </main>

    </div>
  </div>
  <!-- Bootstrap 5 JS Bundle (com Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <?php include($Raiz . "include/php/rodape.php"); ?>
  <script type="text/javascript">
    // Exemplo padrão recomendado pela documentação do Bootstrap
    (function () {
      'use strict'
      var forms = document.querySelectorAll('.needs-validation')
      Array.prototype.slice.call(forms)
        .forEach(function (form) {
          form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
              event.preventDefault() // Impede o envio se estiver inválido
              event.stopPropagation() // Para a propagação do evento
            }
            form.classList.add('was-validated') // Aplica as classes visuais do Bootstrap
          }, false)
        })
    })()
  </script>
</body>

</html>
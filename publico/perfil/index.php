<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /publico/perfil/index.php
 ***** Conteúdo: 05-perfil
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
//VerificaAdmin($Raiz);
VerificaSessao();
$ok = true;
$html = "";
$html_tit = "";
$html_msg = "";
$html_lnk = "";
$html_btn = "";
$chave_usuario = $_SESSION["CHAVE_USUARIO"];
$email_usuario = "";
$aftergopage = "";
$msg = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $chave_perfil = "";
  $nome_perfil = $_POST["nome_perfil"];
  $end_perfil = $_POST["end_perfil"];
  $num_perfil = $_POST["num_perfil"];
  $compl_perfil = $_POST["compl_perfil"];
  $bairro_perfil = $_POST["bairro_perfil"];
  $cid_perfil = $_POST["cid_perfil"];
  $uf_perfil = $_POST["uf_perfil"];
  $cep_perfil = $_POST["cep_perfil"];
  $codibge_perfil = $_POST["codibge_perfil"];
  $cpf_perfil = $_POST["cpf_perfil"];
  $tel_perfil = $_POST["tel_perfil"];
  $email_perfil = $_POST["email_perfil"];
  if ($nome_perfil == "") {
    $ok = false;
    $msg = "Nome inválido.";
  }
  if (!cpf($cpf_perfil)) {
    $ok = false;
    $msg = "CPF inválido.";
  }
  if ($tel_perfil == "") {
    $ok = false;
    $msg = "Telefone / WhatsApp inválido.";
  }
  if ($email_perfil == "") {
    $ok = false;
    $msg = "E-mail inválido.";
  }
  if ($cep_perfil == "") {
    $ok = false;
    $msg = "CEP inválido.";
  }
  if ($cid_perfil == "") {
    $ok = false;
    $msg = "Cidade - UF inválido.";
  }
  if ($end_perfil == "") {
    $ok = false;
    $msg = "Endereço inválido.";
  }
  if ($ok) {
    abre_db();
    $strsql = "
    select 
    tusuario.chave_perfil
    from 
    tusuario
    where 
    tusuario.chave_usuario = :vchave_usuario and 
    tusuario.caixa_usuario = 1
    ";
    $qperfil = $pdo->prepare($strsql);
    $qperfil->bindParam(":vchave_usuario", $chave_usuario);
    $qperfil->execute();
    if ($tperfil = $qperfil->fetch(PDO::FETCH_ASSOC)) {
      $chave_perfil = $tperfil["chave_perfil"];
    }
    if ($chave_perfil == "") {
      $ok = false;
      $msg = "Perfil não encontrado.";
    }
  }
  if ($ok) {
    abre_db();
    $abre_diario = abre_diario("tperfil", "chave_perfil", $chave_perfil, $campoexcluidos = array(""));
    $strsql = "
    update 
    tperfil
    set 
    nome_perfil = :vnome_perfil
    ,end_perfil = :vend_perfil
    ,num_perfil = :vnum_perfil
    ,compl_perfil = :vcompl_perfil
    ,bairro_perfil = :vbairro_perfil
    ,cid_perfil = :vcid_perfil
    ,uf_perfil = :vuf_perfil
    ,cep_perfil = :vcep_perfil
    ,codibge_perfil = :vcodibge_perfil
    ,cpf_perfil = :vcpf_perfil
    ,tel_perfil = :vtel_perfil
    ,email_perfil = :vemail_perfil
    where 
    chave_perfil = :vchave_perfil
    ";
    $qperfil = $pdo->prepare($strsql);
    $qperfil->bindParam(":vnome_perfil", $nome_perfil);
    $qperfil->bindParam(":vend_perfil", $end_perfil);
    $qperfil->bindParam(":vnum_perfil", $num_perfil);
    $qperfil->bindParam(":vcompl_perfil", $compl_perfil);
    $qperfil->bindParam(":vbairro_perfil", $bairro_perfil);
    $qperfil->bindParam(":vcid_perfil", $cid_perfil);
    $qperfil->bindParam(":vuf_perfil", $uf_perfil);
    $qperfil->bindParam(":vcep_perfil", $cep_perfil);
    $qperfil->bindParam(":vcodibge_perfil", $codibge_perfil);
    $qperfil->bindParam(":vcpf_perfil", $cpf_perfil);
    $qperfil->bindParam(":vtel_perfil", $tel_perfil);
    $qperfil->bindParam(":vemail_perfil", $email_perfil);
    $qperfil->bindParam(":vchave_perfil", $chave_perfil);
    $qperfil->execute();
    fecha_diario("tperfil", "chave_perfil", $chave_perfil, $abre_diario, $campoexcluidos = array(""));
    $strsql = "
		select     
		tusuario.chave_usuario
    ,tusuario.nome_usuario
    ,tusuario.email_usuario
		from  
		tusuario		
		where  
		tusuario.chave_usuario = :vchave_usuario and 
		tusuario.ativo_usuario = 1 and 
		tusuario.caixa_usuario = 1
		";
    $qusuario = $pdo->prepare($strsql);
    $qusuario->bindParam(":vchave_usuario", $chave_usuario);
    $qusuario->execute();
    if ($tusuario = $qusuario->fetch(PDO::FETCH_ASSOC)) {
      $abre_diario = abre_diario("tusuario", "chave_usuario", $chave_usuario, $campoexcluidos = array(""));
      $strsql = "
      update 
      tusuario 
      set                   
      nome_usuario = :vnome_usuario
      ,email_usuario = :vemail_usuario
      where 
      chave_usuario = :vchave_usuario
      ";
      $qusuario = $pdo->prepare($strsql);
      $qusuario->bindParam(":vchave_usuario", $chave_usuario);
      $qusuario->bindParam(":vnome_usuario", $nome_perfil);
      $qusuario->bindParam(":vemail_usuario", $email_perfil);
      $qusuario->execute();
      fecha_diario("tusuario", "chave_usuario", $chave_usuario, $abre_diario, $campoexcluidos = array(""));

      $_SESSION["NOME_USUARIO"] = $nome_perfil;
      $_SESSION["LOGIN_USUARIO"] = $email_perfil;
      $_SESSION["NOME_USUARIOLOGIN"] = $nome_perfil;
      $_SESSION["LOGIN_USUARIOLOGIN"] = $email_perfil;
      $_SESSION["SESSAO_INICIO"] = time(); // INICIO DA ATIVIDADE DA SESSAO
      $_SESSION["SESSAO_EXPIRA"] = 28800; // SEGUNDOS SEM ATIVIDADE (8 HORAS)    
      header("Location: /publico/geral");
      die();
    }
  }
}
abre_db();
$strsql = "
select 
tperfil.* 
from 
tusuario
left join tperfil on tusuario.chave_perfil = tperfil.chave_perfil 
where 
tusuario.chave_usuario = :vchave_usuario and 
tusuario.caixa_usuario = 1
";
$qperfil = $pdo->prepare($strsql);
$qperfil->bindParam(":vchave_usuario", $chave_usuario);
$qperfil->execute();
if ($tperfil = $qperfil->fetch(PDO::FETCH_ASSOC)) {
  $chave_perfil = $tperfil["chave_perfil"];
  $nome_perfil = $tperfil["nome_perfil"];
  $end_perfil = $tperfil["end_perfil"];
  $num_perfil = $tperfil["num_perfil"];
  $compl_perfil = $tperfil["compl_perfil"];
  $bairro_perfil = $tperfil["bairro_perfil"];
  $cid_perfil = $tperfil["cid_perfil"];
  $uf_perfil = $tperfil["uf_perfil"];
  $cep_perfil = $tperfil["cep_perfil"];
  $cpf_perfil = $tperfil["cpf_perfil"];
  $tel_perfil = $tperfil["tel_perfil"];
  $email_perfil = $tperfil["email_perfil"];

}
else {
  $chave_perfil = "0";
  $nome_perfil = "";
  $end_perfil = "";
  $num_perfil = "";
  $compl_perfil = "";
  $bairro_perfil = "";
  $cid_perfil = "";
  $uf_perfil = "";
  $cep_perfil = "";
  $cpf_perfil = "";
  $tel_perfil = "";
  $email_perfil = "";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Recibo Express - Perfil</title>
  <?php include($Raiz . "include/html/header.html"); ?>
</head>
<script type="text/javascript">
  function pesquisacep(vcep) {
    var vok = true;
    var vcodibge_perfil = document.getElementById('codibge_perfil').value;
    var vcep_perfil_pesquisado = document.getElementById('cep_perfil_pesquisado').value;
    if (vcep_perfil_pesquisado.length != 9) {
      vcep_perfil_pesquisado = "";
    }
    vcep = vcep.trim();
    vcodibge_perfil = vcodibge_perfil.trim();
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
      vcep_perfil_pesquisado = vcep_perfil_pesquisado.replace(/\D/g, "");
      if (vcep == vcep_perfil_pesquisado && vcodibge_perfil.length == 7) {
        vok = false;
      }
    }
    if (vok) {
      var validacep = /^[0-9]{8}$/;
      if (validacep.test(vcep)) {
        //Preenche os campos com "..." enquanto consulta webservice.
        if (document.getElementById('end_perfil').value == "") {
          document.getElementById('end_perfil').value = "...";
        }
        if (document.getElementById('bairro_perfil').value == "") {
          document.getElementById('bairro_perfil').value = "...";
        }
        if (document.getElementById('cid_perfil').value == "") {
          document.getElementById('cid_perfil').value = "...";
        }
        if (document.getElementById('uf_perfil').value == "") {
          document.getElementById('uf_perfil').value = "...";
        }
        if (document.getElementById('codibge_perfil').value == "") {
          document.getElementById('codibge_perfil').value = "...";
        }
        document.getElementById('cep_perfil_pesquisado').value = document.getElementById('cep_perfil').value;
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
            document.getElementById('cep_perfil').value = vcep_formatado;
            document.getElementById('end_perfil').value = xhr.logradouro.toUpperCase();
            document.getElementById('bairro_perfil').value = xhr.bairro.toUpperCase();
            document.getElementById('cid_perfil').value = xhr.localidade.toUpperCase();
            document.getElementById('uf_perfil').value = xhr.uf.toUpperCase();
            document.getElementById('codibge_perfil').value = xhr.ibge;
            document.getElementById('cid_perfil').value = xhr.localidade.toUpperCase() + ' / ' + xhr.uf.toUpperCase();
            document.getElementById('end_perfil').value = xhr.logradouro.toUpperCase() + ', ' + xhr.bairro.toUpperCase();
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
      const vcep_perfil_feedback = document.getElementById("cep_perfil_feedback");
      vcep_perfil_feedback.textContent = "Conteúdo inválido! Informe um CEP.";
      document.getElementById('cep_perfil').setAttribute('class', 'form-control is-invalid');
      document.getElementById('cep_perfil_feedback').setAttribute('class', 'invalid-feedback');
    }
    else {
      const vcep_perfil_feedback = document.getElementById("cep_perfil_feedback");
      vcep_perfil_feedback.textContent = "";
      document.getElementById('cep_perfil').setAttribute('class', 'form-control');
      document.getElementById('cep_perfil_feedback').setAttribute('class', 'd-none');
    }
    return;
  };

  function limpa_formulário_cep() {
    document.getElementById('end_perfil').value = "";
    document.getElementById('bairro_perfil').value = "";
    document.getElementById('cid_perfil').value = "";
    document.getElementById('uf_perfil').value = "";
    document.getElementById('codibge_perfil').value = "";
  }

  function verificaNOME(ccampo) {
    var vok = true;
    var cnome = ccampo.value;
    cnome = cnome.trim();
    if (cnome == "") {
      const vnome_perfil_feedback = document.getElementById("nome_perfil_feedback");
      vnome_perfil_feedback.textContent = "Conteúdo inválido! Informe um nome.";
      document.getElementById('nome_perfil').setAttribute('class', 'form-control is-invalid');
      document.getElementById('nome_perfil_feedback').setAttribute('class', 'invalid-feedback');
      vok = false;
    }
    if (vok) {
      document.getElementById('nome_perfil').setAttribute('class', 'form-control');
      document.getElementById('nome_perfil_feedback').setAttribute('class', 'd-none');
    }
  }

  function verificaTEL(ccampo) {
    var vok = true;
    var ctel = ccampo.value;
    ctel = ctel.trim();
    if (ctel == "") {
      const vtel_perfil_feedback = document.getElementById("tel_perfil_feedback");
      vtel_perfil_feedback.textContent = "Conteúdo inválido! Informe um Telefone / WhatsApp.";
      document.getElementById('tel_perfil').setAttribute('class', 'form-control is-invalid');
      document.getElementById('tel_perfil_feedback').setAttribute('class', 'invalid-feedback');
      vok = false;
    }
    if (vok) {
      document.getElementById('tel_perfil').setAttribute('class', 'form-control');
      document.getElementById('tel_perfil_feedback').setAttribute('class', 'd-none');
    }
  }

  async function verificaEMAIL(ccampo) {
    let cemail = ccampo.value;
    cemail = cemail.trim();
    if (cemail == "") {
      const vemail_perfil_feedback = document.getElementById("email_perfil_feedback");
      vemail_perfil_feedback.textContent = "Conteúdo inválido! Informe um e-mail.";
      document.getElementById('email_perfil').setAttribute('class', 'form-control is-invalid');
      document.getElementById('email_perfil_feedback').setAttribute('class', 'invalid-feedback');
      return;
    }
    try {
      const cchave_perfil = document.getElementById("chave_perfil").value;
      const response = await fetch('cadastro_ajax.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          chave_perfil: cchave_perfil,
          email_perfil: cemail
        }),
      });
      const data = await response.json();
      console.log(data); // Loga os dados já processados do JSON
      if (data.retorno === "falha") {
        const vemail_perfil_feedback = document.getElementById("email_perfil_feedback");
        vemail_perfil_feedback.textContent = "E-mail inválido ou já cadastrado.";
        document.getElementById('email_perfil').className = 'form-control is-invalid';
        document.getElementById('email_perfil_feedback').className = 'invalid-feedback';
        document.getElementById("BTN_CONFIRMAR").disabled = true;
      } else {
        // Sucesso na validação do servidor
        document.getElementById('email_perfil').className = 'form-control';
        document.getElementById('email_perfil_feedback').className = 'd-none';
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
      const vcep_perfil_feedback = document.getElementById("cep_perfil_feedback");
      vcep_perfil_feedback.textContent = "Conteúdo inválido! Informe um CEP.";
      document.getElementById('cep_perfil').setAttribute('class', 'form-control is-invalid');
      document.getElementById('cep_perfil_feedback').setAttribute('class', 'invalid-feedback');
      vok = false;
    }
    if (vok) {
      ccep = ccep.replace(/\D/g, "");
      if (ccep.length != 8) {
        vok = false;
      }
    }
    if (!vok) {
      const vcep_perfil_feedback = document.getElementById("cep_perfil_feedback");
      vcep_perfil_feedback.textContent = "Conteúdo inválido! Informe um CEP.";
      document.getElementById('cep_perfil').setAttribute('class', 'form-control');
      document.getElementById('cep_perfil_feedback').setAttribute('class', 'd-none');
    }
  }

  function verificaCID(ccampo) {
    var vok = true;
    var ccid = ccampo.value;
    ccid = ccid.trim();
    if (ccid == "") {
      const vcid_perfil_feedback = document.getElementById("cid_perfil_feedback");
      vcid_perfil_feedback.textContent = "Conteúdo inválido! Informe uma cidade e UF.";
      document.getElementById('cid_perfil').setAttribute('class', 'form-control is-invalid');
      document.getElementById('cid_perfil_feedback').setAttribute('class', 'invalid-feedback');
      vok = false;
    }
    if (vok) {
      document.getElementById('cid_perfil').setAttribute('class', 'form-control');
      document.getElementById('cid_perfil_feedback').setAttribute('class', 'd-none');
    }
  }

  function verificaEND(ccampo) {
    var vok = true;
    var cend = ccampo.value;
    cend = cend.trim();
    if (cend == "") {
      const vend_perfil_feedback = document.getElementById("end_perfil_feedback");
      vend_perfil_feedback.textContent = "Conteúdo inválido! Informe uma endade e UF.";
      document.getElementById('end_perfil').setAttribute('class', 'form-control is-invalid');
      document.getElementById('end_perfil_feedback').setAttribute('class', 'invalid-feedback');
      vok = false;
    }
    if (vok) {
      document.getElementById('end_perfil').setAttribute('class', 'form-control');
      document.getElementById('end_perfil_feedback').setAttribute('class', 'd-none');
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
      const vcpf_perfil_feedback = document.getElementById("cpf_perfil_feedback");
      vcpf_perfil_feedback.textContent = "Conteúdo inválido! Informe um número válido de CPF.";
      document.getElementById('cpf_perfil').className = 'form-control is-invalid';
      document.getElementById('cpf_perfil_feedback').className = 'invalid-feedback';
      return; // Interrompe a execução caso seja inválido
    }
    // Formata o CPF no campo
    ccpf = formatCpfCnpj(ccpf, true, isCnpj(ccpf));
    ccampo.value = ccpf;
    // 2. Validação no Servidor (AJAX Assíncrono)
    try {
      const cchave_perfil = document.getElementById("chave_perfil").value;
      const response = await fetch('cadastro_ajax.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          chave_perfil: cchave_perfil,
          cpf_perfil: ccpf
        }),
      });
      const data = await response.json();
      console.log(data); // Loga os dados já processados do JSON
      if (data.retorno === "falha") {
        const vcpf_perfil_feedback = document.getElementById("cpf_perfil_feedback");
        vcpf_perfil_feedback.textContent = "CPF inválido ou já cadastrado.";
        document.getElementById('cpf_perfil').className = 'form-control is-invalid';
        document.getElementById('cpf_perfil_feedback').className = 'invalid-feedback';
        document.getElementById("BTN_CONFIRMAR").disabled = true;
      } else {
        // Sucesso na validação do servidor
        document.getElementById('cpf_perfil').className = 'form-control';
        document.getElementById('cpf_perfil_feedback').className = 'd-none';
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
            <span class="texto11">CADASTROS . PERFIL</span>
          </div>
          <form method="post" class="needs-validation" id="FPERFIL_EDITA" name="FPERFIL_EDITA" action="index.php" novalidate>
            <div class="row">
              <div class="col-sm-9">
                <span style="color:#1E2933; font-weight:700; font-size: 1.75em;">Meu Perfil</span><br>
                <span class="texto-corpo">Somente Pessoa física. O sistema não permite MEI ou CNPJ.</span>
              </div>
              <div class="col-sm-3">
                <div class="d-flex flex-column h-100">
                  <button type="submit" class="btn btn-custom float-end mt-auto" id="BTN_CONFIRMAR" name="BTN_CONFIRMAR">
                    Salvar alterações
                  </button>
                </div>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-12">
                <div class="card rounded-3 border-1 p-4" style="background-color: #FFF; border-color: #CCC;">
                  <span class="texto12 mb-3">DADOS DO PRESTADOR</span>
                  <div class="row mb-3">
                    <div class="col-12">
                      <label for="nome_perfil" class="form-label mb-1 texto-campo">NOME COMPLETO</label>
                      <input type="text" class="form-control" id="nome_perfil" name="nome_perfil" value="<?php echo $nome_perfil ?>" placeholder="Nome completo" onblur="verificaNOME(this)" aria-describedby="nome_perfil_feedback" required>
                      <div id="nome_perfil_feedback" class="d-none">
                        Nome inválido
                      </div>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-6">
                      <label for="cpf_perfil" class="form-label mb-1 texto-campo">CPF</label>
                      <input type="text" class="form-control" id="cpf_perfil" name="cpf_perfil" value="<?php echo $cpf_perfil ?>" placeholder="CPF" onblur="verificaCPF(this)" aria-describedby="cpf_perfil_feedback" required>
                      <div id="cpf_perfil_feedback" class="d-none">
                        CPF inválido.
                      </div>
                    </div>
                    <div class="col-6">
                      <label for="tel_perfil" class="form-label mb-1 texto-campo">TELEFONE/WHATSAPP</label>
                      <input type="text" class="form-control" id="tel_perfil" name="tel_perfil" value="<?php echo $tel_perfil ?>" placeholder="Telefone ou WhatsApp" onblur="mascaraTEL(this); verificaTEL(this)" aria-describedby="tel_perfil_feedback" required>
                      <div id="tel_perfil_feedback" class="d-none">
                        Telefone / WhatsApp inválido.
                      </div>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-12">
                      <label for="email_perfil" class="form-label mb-1 texto-campo">E-MAIL</label>
                      <input type="email" class="form-control" id="email_perfil" name="email_perfil" value="<?php echo $email_perfil ?>" placeholder="E-mail" onblur="verificaEMAIL(this)" aria-describedby="email_perfil_feedback" required>
                      <div id="email_perfil_feedback" class="d-none">
                        E-mail inválido.
                      </div>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-6">
                      <label for="cep_perfil" class="form-label mb-1 texto-campo">CEP</label>
                      <input type="hidden" id="codibge_perfil" name="codibge_perfil" value="">
                      <input type="hidden" id="cep_perfil_pesquisado" name="cep_perfil_pesquisado" value="">
                      <input type="text" class="form-control" id="cep_perfil" name="cep_perfil" value="<?php echo $cep_perfil ?>" placeholder="CEP" value="" onblur="javascript:pesquisacep(this.value);" aria-describedby="cep_perfil_feedback" required>
                      <div id="cep_perfil_feedback" class="d-none">
                        CEP inválido.
                      </div>
                    </div>
                    <div class="col-6">
                      <label for="cid_perfil" class="form-label mb-1 texto-campo">CIDADE / UF</label>
                      <input type="text" class="form-control" id="cid_perfil" name="cid_perfil" value="<?php echo $cid_perfil ?>" placeholder="Cidade - UF" onblur="verificaCID(this)" aria-describedby="cid_perfil_feedback" required>
                      <div id="cid_perfil_feedback" class="d-none">
                        Cidade inválido.
                      </div>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-12">
                      <label for="end_perfil" class="form-label mb-1 texto-campo">ENDEREÇO (RUA, NÚMERO, COMPLEMENTO/BAIRRO)</label>
                      <input type="hidden" id="num_perfil" name="num_perfil" value="">
                      <input type="hidden" id="compl_perfil" name="compl_perfil" value="">
                      <input type="hidden" id="bairro_perfil" name="bairro_perfil" value="">
                      <input type="hidden" id="uf_perfil" name="uf_perfil" value="">
                      <input type="text" class="form-control" id="end_perfil" name="end_perfil" value="<?php echo $end_perfil ?>" placeholder="Endereço (Rua, nº, complemento, bairro)" onblur="verificaEND(this)" aria-describedby="end_perfil_feedback" required>
                      <div id="end_perfil_feedback" class="d-none">
                        Endereço inválido.
                      </div>
                    </div>
                  </div>
                </div>
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
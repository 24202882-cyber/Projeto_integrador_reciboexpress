<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /entrar/index.php
 ***** Conteúdo: Acesso ao sistema
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
require_once '../vendor/autoload.php';
session_start();

/*
use League\OAuth2\Client\Provider\Google;

$provider = new Google([
    'clientId'     => '682512042222-4kun1ghgo6t0d7s70k8u7c42k186fseg.apps.googleusercontent.com',
    'clientSecret' => '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',
    'redirectUri'  => 'http://localhost:8081/index.php',
]);

// Gera a URL de autorização e obtém o estado (state) para segurança contra CSRF
$authUrl = $provider->getAuthorizationUrl([
    'scope' => ['email', 'profile']
]);

// Salva o state na sessão para validar no callback
$_SESSION['oauth2state'] = $provider->getState();

// Redireciona o usuário para a página de login do Google
//header('Location: ' . $authUrl);
//exit;

*/
//----------------------- PASSO 4
/*
session_start();
require 'vendor/autoload.php';

use League\OAuth2\Client\Provider\Google;

$provider = new Google([
    'clientId'     => '682512042222-4kun1ghgo6t0d7s70k8u7c42k186fseg.apps.googleusercontent.com',
    'clientSecret' => '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',
    'redirectUri'  => 'http://localhost:8081/entrar/callback.php',
]);

// 1. Validação de segurança contra ataques CSRF
if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    exit('Estado inválido. Requisição bloqueada por segurança.');
}

// 2. Troca o código recebido por um Access Token
try {
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // 3. Obtém os dados do perfil do usuário
    $user = $provider->getResourceOwner($token);

    // Dados do usuário disponíveis:
    $id    = $user->getId();
    $email = $user->getEmail();
    $name  = $user->getName();
    $avatar = $user->getAvatar();

    // 4. Lógica da sua aplicação:
    // - Procure o usuário no banco pelo $email ou $id.
    // - Se não existir, crie um novo registro.
    // - Inicie a sessão do usuário no seu sistema.
    $_SESSION['user_logged_in'] = true;
    $_SESSION['user_email'] = $email;

    echo "Login efetuado com sucesso! Bem-vindo, " . htmlspecialchars($name);

} catch (Exception $e) {
    exit('Erro ao autenticar: ' . $e->getMessage());
}
    */

//----------------------- PASSO 4

$Raiz = "../";
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
$email_usuario = "";
// EOF Variáveis de controle
$aftergopage = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $email_usuario = $_POST["email_usuario"];
  $senha_usuario = $_POST["senha_usuario"];
  if (isset($_POST["aftergopage"])) {
    $aftergopage = $_POST["aftergopage"];
  }   
  $url_redirec = "";
  $ok = true;
  if ($ok == false) {
    $html_tit = "Falha no login";
    $html_msg = 'Clique "não sou um robô" antes de confirmar o login.';
    $html_lnk = 'javascript:location.href=\'' . $Raiz . 'entrar\'';
    $html_btn = 'Tentar novamente';
  }  
  if ($ok) {
    $ok = false;
    abre_db();
    $strsql = "
		select     
		tusuario.chave_usuario    
    ,tusuario.chave_perfil
    ,tusuario.nome_usuario
    ,tusuario.email_usuario
    ,tusuario.tipo_usuario
		from  
		tusuario		
		where  
		tusuario.email_usuario = :vemail_usuario and 
		tusuario.senha_usuario = :vsenha_usuario and 
		tusuario.ativo_usuario = 1 and 
		tusuario.caixa_usuario = 1
		";
    $qusuario = $pdo->prepare($strsql);
    $qusuario->bindParam(":vemail_usuario", $email_usuario);
    $qusuario->bindParam(":vsenha_usuario", $senha_usuario);
    $qusuario->execute();
    if ($tusuario = $qusuario->fetch(PDO::FETCH_ASSOC)) {
      $_SESSION["CHAVE_PERFIL"] = $tusuario['chave_perfil'];
      $_SESSION["CHAVE_USUARIO"] = $tusuario['chave_usuario'];
      $_SESSION["NOME_USUARIO"] = $tusuario['nome_usuario'];
      $_SESSION["LOGIN_USUARIO"] = $tusuario['email_usuario'];
      $_SESSION["TIPO_USUARIO"] = $tusuario['tipo_usuario'];
      $_SESSION["CHAVE_USUARIOLOGIN"] = $tusuario['chave_usuario'];
      $_SESSION["NOME_USUARIOLOGIN"] = $tusuario['nome_usuario'];
      $_SESSION["LOGIN_USUARIOLOGIN"] = $tusuario['email_usuario'];
      $_SESSION["SESSAO_INICIO"] = time(); // INICIO DA ATIVIDADE DA SESSAO
      $_SESSION["SESSAO_EXPIRA"] = 28800; // SEGUNDOS SEM ATIVIDADE (8 HORAS)
      $url_redirec = $Raiz . "publico/geral";
      $ok = true;
    }
  }  
  fecha_db();
  if ($ok) {
    header("Location: " . $Raiz . $url_redirec);
    die();
  }
  else {
    $html_tit = 'Falha no login';
    $html_msg = 'Usuário ou senha inválida.';
    $html_lnk = 'javascript:location.href=\'' . $Raiz . 'acesso/acesso.php\'';
    $html_btn = 'Tentar novamente';
  }
}
if ($ok) {
  $html_tit = 'Recibo Express';
  $html_msg = 'Login';
  $html_lnk = 'javascript:location.href=\'' . $Raiz . 'acesso/acesso.php\'';
  $html_btn = 'Tentar novamente';
}
// Corpo
if ($ok) {
  //	$verifica_recaptcha = true;
  $verifica_recaptcha = false;
  if (isset($_SESSION['AMBIENTE'])) {
    if ($_SESSION['AMBIENTE'] === "DESENVOLVIMENTO") {
      $verifica_recaptcha = false;
    }
  }
}
else {
  $html = '
	<div class="text-center">
	<h1>' . $html_tit . '</h1>			
	<p>' . $html_msg . '</p>
	<br />
      <button type="button" class="btn btn-primary mb-2" onclick="' . $html_lnk . '">' . $html_btn . '</button>
	</div>
	<br />';
}
// EOF Corpo

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Recibo Express - Entrar</title>
  <?php include($Raiz . "include/html/header.html"); ?>
  <script src="https://accounts.google.com/gsi/client" async defer></script>  
</head>

<style>
  /* Estilo para a divisória "ou" */
  .divider {
    display: flex;
    align-items: center;
    text-align: center;
    margin: 20px 0;
    color: #8c9196;
    /* Tom de cinza do texto */
    font-size: 14px;
    font-weight: 500;
    text-transform: uppercase;
  }

  .divider::before,
  .divider::after {
    content: '';
    flex: 1;
    border-bottom: 1px solid #e2e8f0;
    /* Linha horizontal bem fina */
  }

  .divider:not(:empty)::before {
    margin-right: .5em;
  }

  .divider:not(:empty)::after {
    margin-left: .5em;
  }

  /* Estilo do botão Google */
  .google-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    /* Distância entre o ícone e o texto */
    width: 100%;
    padding: 14px;
    /* Ajuste para a altura desejada */
    border: 1.5px solid #e2e8f0;
    /* Borda cinza bem clara */
    border-radius: 8px;
    /* Cantos arredondados */
    background-color: #ffffff;
    color: #1f2937;
    /* Cinza escuro/quase preto */
    font-family: inherit;
    /* Utiliza a mesma fonte da sua página */
    font-size: 16px;
    font-weight: 600;
    /* Texto em negrito */
    cursor: pointer;
    transition: background-color 0.2s ease, border-color 0.2s ease;
  }

  /* Efeito de hover (opcional, para interatividade) */
  .google-btn:hover {
    background-color: #f9fafb;
    border-color: #cbd5e1;
  }

  /* Tamanho do ícone do Google */
  .google-btn img {
    width: 20px;
    height: 20px;
  }
</style>

<script type="text/javascript">
  function submeter() {
    var vok = true;
    if (document.FACESSO_POST.EMAIL_USUARIO.value.trim() == "") {
      vok = false;
      alert("E-mail inválido.");
      document.FACESSO_POST.EMAIL_USUARIO.focus();
    }
    if (vok == true) {
      if (document.FACESSO_POST.SENHA_USUARIO.value.trim() == "") {
        vok = false;
        alert("Senha inválida.");
        document.FACESSO_POST.SENHA_USUARIO.focus();
      }
    }
    if (vok == true) {
      setcookie("ReciboExpressUsr", document.FACESSO_POST.EMAIL_USUARIO.value.trim(), 30);
      setcookie("ReciboExpressPsw", document.FACESSO_POST.SENHA_USUARIO.value.trim(), 30);
      document.FACESSO_POST.submit();
    }
  }

  function avaliatecla() {
    if (window.event && window.event.keyCode == 13) {
      submeter();
    }
  }

  function acesso_tentarnovamente() {
    window.location.href = '/acesso/acesso.php';
  }

  function getcookie() {
    var cNomeCookie = "ReciboExpressUsr" + "=";
    var cDecodedCookie = decodeURIComponent(document.cookie);
    var aCookies = cDecodedCookie.split(';');
    var cCookie = "";
    var i = 0;
    var vUsr = "";
    var vPsw = "";
    for (i = 0; i < aCookies.length; i++) {
      cCookie = aCookies[i];
      while (cCookie.charAt(0) == ' ') {
        cCookie = cCookie.substring(1);
      }
      if (cCookie.indexOf(cNomeCookie) == 0) {
        vUsr = cCookie.substring(cNomeCookie.length, cCookie.length);
        break;
      }
    }
    cNomeCookie = "ReciboExpressPsw" + "=";
    cDecodedCookie = decodeURIComponent(document.cookie);
    aCookies = cDecodedCookie.split(';');
    cCookie = "";
    for (i = 0; i < aCookies.length; i++) {
      cCookie = aCookies[i];
      while (cCookie.charAt(0) == ' ') {
        cCookie = cCookie.substring(1);
      }
      if (cCookie.indexOf(cNomeCookie) == 0) {
        vPsw = cCookie.substring(cNomeCookie.length, cCookie.length);
        break;
      }
    }
    document.FACESSO_POST.EMAIL_USUARIO.value = vUsr;
    document.FACESSO_POST.SENHA_USUARIO.value = vPsw;
    return "";
  }

  function setcookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/acesso/";
  }

function verificaEMAIL(ccampo) {
  var vok = true;
  var cemail = ccampo.value;
  cemail = cemail.trim();
  if (cemail == "") {    
    const vemail_usuario_feedback = document.getElementById("email_usuario_feedback");
    vemail_usuario_feedback.textContent = "Conteúdo inválido! Informe seu e-mail de login.";
    document.getElementById('email_usuario').setAttribute('class', 'form-control is-invalid');
    document.getElementById('email_usuario_feedback').setAttribute('class', 'invalid-feedback');
    vok = false;
  }
  if (vok) {
    document.getElementById('email_usuario').setAttribute('class', 'form-control');
    document.getElementById('email_usuario_feedback').setAttribute('class', 'd-none');
  }
}

function verificaSENHA(ccampo) {
  var vok = true;
  var csenha = ccampo.value;
  csenha = csenha.trim();
  if (csenha == "") {    
    const vsenha_usuario_feedback = document.getElementById("senha_usuario_feedback");
    vsenha_usuario_feedback.textContent = "Conteúdo inválido! Informe a senha de acesso.";
    document.getElementById('senha_usuario').setAttribute('class', 'form-control is-invalid');
    document.getElementById('senha_usuario_feedback').setAttribute('class', 'invalid-feedback');
    vok = false;
  }
  if (vok) {
    document.getElementById('senha_usuario').setAttribute('class', 'form-control');
    document.getElementById('senha_usuario_feedback').setAttribute('class', 'd-none');
  }
}

</script>

<body>
  <div class="container">
    <div class="row">
      <div class="col-7" style="background-color: #163B5C; padding-top:60px;">
        <a class="navbar-brand" href="/index.php"><img src="/identidade/logo-reciboexpress-bg.svg" class="img-fluid" style="max-width:200px;" /></a>
        <div class="row d-flex align-items-center" style="min-height:300px">
          <div class="col-6">
            <p style="font-size:2.13em; font-weight: 800; line-height:1.2; color: #EAF1F8">Documente seu trabalho e receba com tranquilidade.</p>

            <p class="texto-suave-bg" style="font-size: 0.94em">Cadastre serviços, faça orçamentos, emita recibos particulares e acompanhe a confirmação de cada pagamento.</p>
            <!--style="color:#9FBBD4" -->
          </div>
        </div>
      </div>
      <div class="col-5" style="padding:50px; background-color: #fff;">
        <div class="row">
          <div class="col-12">
            <span class="texto-pirulito">BEM-VINDO(A) DE VOLTA</span>
          </div>
          <div class="col-12">
            <span style="color:#1E2933; font-weight:700; font-size: 1.75em;">Entrar na plataforma</span><br>
          </div>
          <div class="col-12 mb-3">
            <span class="texto-regular">Acesse com e-mail e senha, ou pela sua conta Google.</span>
          </div>
          <div class="col-12">
            <form method="post" class="needs-validation" id="FENTRAR_POST" name="FENTRAR_POST" action="index.php" novalidate>
              <input type="hidden" id="AFTERGOPAGE" name="AFTERGOPAGE" value="' . $aftergopage . '">
              <div class="row mb-3">
                <div class="col-12">
                  <label for="email_usuario" class="col-form-label-sm fw-bold mb-0 texto-campo">E-MAIL</label>
                  <input type="text" class="form-control" id="email_usuario" name="email_usuario" onblur="javascript:verificaEMAIL(this);" placeholder="E-mail" maxlength="128" value="" aria-describedby="email_usuario_feedback" required>
                  <div id="email_usuario_feedback" class="d-none">
                    E-mail inválido.
                  </div>
                </div>
              </div>
              <div class="row mb-1">
                <div class="col-12">
                  <label for="senha_usuario" class="col-form-label-sm fw-bold mb-0 texto-campo">SENHA</label>
                  <input type="password" class="form-control" id="senha_usuario" name="senha_usuario" maxlength="20" placeholder="Senha" onKeyPress="javascript:avaliatecla();" aria-describedby="senha_usuario_feedback" required>
                  <div id="senha_usuario_feedback" class="d-none">
                    Senha inválida.
                  </div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-12 text-end">
                  <a class="link-padrao texto-menor" href="' . $Raiz . 'entrar/entrar_lembrar.php">Esqueci minha senha</a>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-12">
                  <!--<button type="button" id="BTN_SUBMETER" name="BTN_SUBMETER" class="btn btn-custom" onclick="javascript:submeter();" style="width:100%"' . ($verifica_recaptcha == false ? "" : " disabled") . '>Entrar</button> -->
                  <button type="submit" id="BTN_SUBMETER" name="BTN_SUBMETER" class="btn btn-custom" onclick="javascript:location='/publico/geral';" style="width:100%"' . ($verifica_recaptcha == false ? "" : " disabled") . '>Entrar</button>
                </div>
              </div>
              <!-- Divisória "OU" -->
              <div class="divider">
                <span>OU</span>
              </div>

              <div id="buttonDiv"></div>

<!--
              <div id="g_id_onload" 
                data-client_id="682512042222-4kun1ghgo6t0d7s70k8u7c42k186fseg.apps.googleusercontent.com"
                data-login_uri="http://localhost:8081/entrar/callback.php"
                data-auto_prompt="false">
              </div>
              <div class="g_id_signin" data-type="standard" data-shape="rectangular" data-theme="outline" data-text="continue_with" data-size="large" data-logo_alignment="left">
              </div>
-->

              <script>
              // SEU CLIENT ID DO GOOGLE CLOUD CONSOLE
              const CLIENT_ID = '682512042222-4kun1ghgo6t0d7s70k8u7c42k186fseg.apps.googleusercontent.com';

              window.onload = function () {
                  google.accounts.id.initialize({
                      client_id: CLIENT_ID,
                      callback: handleCredentialResponse
                  });

                  google.accounts.id.renderButton(
                      document.getElementById("buttonDiv"),
                      { theme: "outline", size: "large", type: "standard" }
                  );

                  // Opcional: exibe o prompt "One Tap"
                  google.accounts.id.prompt();
              };

              // Função de Callback executada após a autenticação do Google
              async function handleCredentialResponse(response) {
                  const credentialToken = response.credential; // ID Token JWT enviado pelo Google

                  try {
                      // Envia o ID Token para validação no backend PHP
                      const res = await fetch('callback.php', {
                          method: 'POST',
                          headers: {
                              'Content-Type': 'application/json'
                          },
                          body: JSON.stringify({ token: credentialToken })
                      });

                      const data = await res.json();

                      if (data.success) {
                          // Oculta o botão e exibe o perfil do usuário
                          document.getElementById('buttonDiv').style.display = 'none';
                          document.getElementById('userProfile').style.display = 'block';

                          document.getElementById('userName').textContent = data.user.nome;
                          document.getElementById('userEmail').textContent = data.user.email;
                          document.getElementById('userImg').src = data.user.foto;
                      } else {
                          alert('Erro na autenticação: ' + data.message);
                      }
                  } catch (error) {
                      console.error('Erro na requisição:', error);
                  }
              }

              function logout() {
                  google.accounts.id.disableAutoSelect();
                  location.reload();
              }
              </script>

              <div class="row mt-3 mb-3">
                <div class="col-12">
                  <span class="texto-suave texto-menor">Não tem conta? </span><a class="link-padrao texto-menor" href="<?php echo $Raiz . "publico/cadastro"; ?>">Criar conta</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col mx-auto" style="max-width:500px">
      </div>
    </div>
    <?php include($Raiz . "include/php/rodape.php"); ?>
  </div>
</body>

</html>
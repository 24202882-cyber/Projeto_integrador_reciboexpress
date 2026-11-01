<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /publico/recibo/recibo_email.php
 ***** Conteúdo: 08-recibo
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
include($Raiz . "include/php/key.php");
include($Raiz . "conexao/db.php");
require $Raiz . "vendor/autoload.php";
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
VerificaSessao();

// Variáveis de controle
$ok = true;
$html = "";
$chave_perfil = $_SESSION["CHAVE_PERFIL"];
$chave_recibo = "";
$chave_cliente = "";
$nome_cliente = "";
$cpf_cliente = "";
$cep_cliente = "";
$email_usuario = "";
$nome_perfil = "";
$cpf_perfil = "";
$chave_orcamento = "";
$pdf = "";
$pdf_style = "font-family: Arial, Helvetica, sans-serif; font-size: 12px;";
// EOF Variáveis de controle
$aftergopage = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST["chave_recibo"])) {
    $chave_recibo = $_POST["chave_recibo"];
  }
  if ($chave_recibo != "") {
    $_SESSION["CHAVE_RECIBO"] = $chave_recibo;
  }
  abre_db();
  $strsql = "
  select
  tusuario.email_usuario
  from
  tusuario  
  where    
  tusuario.chave_usuario = :vchave_usuario and  
  tusuario.chave_perfil = :vchave_perfil and  
  tusuario.caixa_usuario = 1
  ";
  $qusuario = $pdo->prepare($strsql);
  $qusuario->bindParam(":vchave_usuario", $chave_usuario);
  $qusuario->bindParam(":vchave_perfil", $chave_perfil);
  $qusuario->execute();
  if ($tusuario = $qusuario->fetch(PDO::FETCH_ASSOC)) {
    $email_usuario = $tusuario["email_usuario"];
  }

  $strsql = "
  select
  tperfil.nome_perfil
  ,tperfil.email_perfil
  ,tperfil.cpf_perfil
  from
  tperfil
  where    
  tperfil.chave_perfil = :vchave_perfil and    
  tperfil.caixa_perfil = 1
  ";
  $qperfil = $pdo->prepare($strsql);
  $qperfil->bindParam(":vchave_perfil", $chave_perfil);  
  $qperfil->execute();
  if ($tperfil = $qperfil->fetch(PDO::FETCH_ASSOC)) {
    $nome_perfil = $tperfil["nome_perfil"];
    $email_perfil = $tperfil["email_perfil"];
    $cpf_perfil = $tperfil["cpf_perfil"];
  }
  $strsql = "
  select
  trecibo.*
  ,tcliente.nome_cliente
  ,tcliente.cpf_cliente 
  ,tcliente.cep_cliente 
  ,tcliente.email_cliente 
  from
  trecibo
  left join tcliente on trecibo.chave_cliente = tcliente.chave_cliente
  where    
  trecibo.chave_recibo = :vchave_recibo and
  trecibo.chave_perfil = :vchave_perfil and
  trecibo.caixa_recibo = 1
  ";
  $qrecibo = $pdo->prepare($strsql);
  $qrecibo->bindParam(":vchave_recibo", $chave_recibo);
  $qrecibo->bindParam(":vchave_perfil", $chave_perfil);
  $qrecibo->execute();
  if ($trecibo = $qrecibo->fetch(PDO::FETCH_ASSOC)) {
    $chave_cliente = $trecibo["chave_cliente"];
    $chave_orcamento = $trecibo["chave_orcamento"];
    $nome_cliente = $trecibo["nome_cliente"];
    $cpf_cliente = $trecibo["cpf_cliente"];
    $cep_cliente = $trecibo["cep_cliente"];
    $email_cliente = $trecibo["email_cliente"];
    $dtc = FormataData($trecibo["dtc_recibo"], 'Y-m-d');
    $dte_recibo_pdf = date('d/m/Y');
    $html_recibosrv = "";
    $html_recibosrv_compac = "";
    $html_recibosrv_compac2 = "";
    $contador = 0;
    $subtotal = 0;
    $total = 0;
    abre_db();
    $strsql = "
    select
    trecibosrv.*
    ,tservico.descr_servico
    from
    trecibosrv 
    left join trecibo on trecibosrv.chave_recibo = trecibo.chave_recibo
    left join tservico on trecibosrv.chave_servico = tservico.chave_servico
    where 
    trecibosrv.chave_recibo = :vchave_recibo and
    trecibo.chave_perfil = :vchave_perfil and
    trecibosrv.caixa_recibosrv = 1
    ";
    $qrecibosrv = $pdo->prepare($strsql);
    $qrecibosrv->bindParam(":vchave_recibo", $chave_recibo);
    $qrecibosrv->bindParam(":vchave_perfil", $chave_perfil);
    $qrecibosrv->execute();
    while ($trecibosrv = $qrecibosrv->fetch(PDO::FETCH_ASSOC)) {
      $chave_recibosrv = $trecibosrv["chave_recibosrv"];
      $chave_servico = $trecibosrv["chave_servico"];
      $descr_servico = $trecibosrv["descr_servico"];
      $qtd_recibosrv = $trecibosrv["qtd_recibosrv"];
      $vlu_recibosrv = $trecibosrv["vlu_recibosrv"];
      $vlt_recibosrv = $trecibosrv["vlt_recibosrv"];
      $subtotal += (intval($qtd_recibosrv) * floatval($vlu_recibosrv));
      $total += (intval($qtd_recibosrv) * floatval($vlu_recibosrv));
      $contador += 1;
      $html_recibosrv_compac .= "
      <tr>
        <td style='vertical-align: top; text-align: left;'>
          <div style='font-size: 13px;'>{$descr_servico}</div>
        </td>
        <td style='vertical-align: top; text-align: right;'>
          <div style='font-size: 13px;'>{$qtd_recibosrv}</div>
        </td>
        <td style='vertical-align: top; text-align: right;'>
          <div style='font-size: 13px;'>" . Float2Moeda($vlt_recibosrv, 2) . "</div>
        </td>
        <td style='vertical-align: top; text-align: right;'>
          <div style='font-size: 13px;'>" . strtoupper(DiaMesExtAno($dtc)) . "</div>
        </td>
      </tr>
      ";
      $html_recibosrv_compac2 .= "
      <div class='col-6'>
        <span class='texto13-regular'>{$descr_servico}</span>
      </div>
      <div class='col-1 text-end'>
        <span class='texto13-regular'>{$qtd_recibosrv}</span>
      </div>
      <div class='col-2 text-end'>
        <span class='texto13-regular'>" . Float2Moeda($vlt_recibosrv, 2) . "</span>
      </div>
      <div class='col-3 text-end'>
        <span class='texto13-regular'>" . strtoupper(DiaMesExtAno($dtc)) . "</span>
      </div>";

    }
  }
  $chave_recibo_ext = str_pad($chave_recibo, 6, "0", STR_PAD_LEFT);
}

$logotipo = '../../identidade/reciboexpress.png';
$tipoimg = pathinfo($logotipo, PATHINFO_EXTENSION);
$dadosimg = file_get_contents($logotipo);
$base64img = 'data:image/' . $tipoimg . ';base64,' . base64_encode($dadosimg);

$html = "";
$html = "
<body style='font-family: Helvetica, Arial, sans-serif; color: #111111; font-size: 12px; margin: 20px;'>
  <!-- Tabela Principal sem bordas -->
  <table style='width: 100%; border-collapse: collapse; border: 0;' cellpadding='0' cellspacing='0'>
    <!-- Cabeçalho: RECIBO e NÚMERO/DATA -->
    <tr>
      <td colspan='2' style='vertical-align: top; text-align: left; padding-bottom: 25px;'>
        <h1 style='margin: 0; font-size: 32px; font-weight: bold; letter-spacing: 1px;'>RECIBO</h1>
      </td>
      <td colspan='2' style='vertical-align: top; text-align: right; padding-bottom: 25px;'>
        <div style='font-size: 10px; text-transform: uppercase; color: #444444;'>RECIBO Nº</div>
        <div style='font-size: 16px; font-weight: bold; margin-bottom: 4px;'>{$chave_recibo_ext}</div>
        <div style='font-size: 11px; color: #444444;'>Data: {$dte_recibo_pdf}</div>
      </td>
    </tr>
    <!-- Linha do Recebi de e Valor -->
    <tr>
      <td colspan='3' style='vertical-align: top; text-align: left; padding-bottom: 25px;'>
        <div style='font-size: 9px; text-transform: uppercase; color: #555555; font-weight: bold; margin-bottom: 5px;'>RECEBI DE</div>
        <div style='font-size: 16px; font-weight: bold;'>{$nome_cliente}</div>
      </td>
      <td style='vertical-align: top; text-align: right; padding-bottom: 25px;'>
        <div style='font-size: 9px; text-transform: uppercase; color: #555555; font-weight: bold; margin-bottom: 5px;'>A IMPORTÂNCIA DE</div>
        <div style='font-size: 24px; font-weight: bold;'>R$ " . number_format(floatval($total), 2, ',', '.') . "</div>
      </td>
    </tr>
    <!-- Linha do Referente a e Realizada em -->
    <tr>
      <td style='vertical-align: top; text-align: left; padding-bottom: 5px;'>
        <div style='font-size: 9px; text-transform: uppercase; color: #555555; font-weight: bold; margin-bottom: 5px;'>REFERENTE A</div>            
      </td>
      <td style='vertical-align: top; text-align: right; padding-bottom: 5px;'>
        <div style='font-size: 9px; text-transform: uppercase; color: #555555; font-weight: bold; margin-bottom: 5px;'>QTD EM</div>            
      </td>
      <td style='vertical-align: top; text-align: right; padding-bottom: 5px;'>
        <div style='font-size: 9px; text-transform: uppercase; color: #555555; font-weight: bold; margin-bottom: 5px;'>R$ TOTAL</div>            
      </td>
      <td style='vertical-align: top; text-align: right; padding-bottom: 5px;'>
        <div style='font-size: 9px; text-transform: uppercase; color: #555555; font-weight: bold; margin-bottom: 5px;'>REALIZADA EM</div>            
      </td>
    </tr>
    {$html_recibosrv_compac}
    <!-- Linha divisória cinza -->
    <tr>
      <td colspan='4' style='padding-top: 10px;padding-bottom: 30px;'>
        <hr style='border: none; border-top: 1px solid #E0E0E0; margin: 0;' />
      </td>
    </tr>
    <!-- Atenciosamente e Assinatura -->
    <tr>
      <td colspan='2' style='vertical-align: top; text-align: left;'>
        <div style='font-size: 10px; color: #555555; margin-bottom: 40px;'>Atenciosamente,</div>
        <!-- Linha para assinatura -->
        <div style='width: 250px; border-bottom: 1px solid #CCCCCC; margin-bottom: 8px;'></div>
        <div style='font-size: 10px; font-weight: bold; text-transform: uppercase;'>{$nome_perfil}</div>
        <div style='font-size: 10px; color: #555555;'>CPF: {$cpf_perfil}</div>
      </td>
      <td colspan='2' style='vertical-align: bottom; text-align: right;'>
        <img src='{$base64img}'><br />
        <div style='font-size: 8px; color: #999999; text-transform: uppercase; letter-spacing: 0.5px;'>Emitido com reciboexpress.com.br</div>
      </td>
    </tr>
  </table>
</body>
";
//********************
//********************
//**** Gera PDF
//********************
//********************

$arqpdf = criptografar($chave_recibo_ext);
//?chave_perfil&chave_orcamento&situacao_orcamento
//?id1=000001&id2=000001&id3=XXXXXXXXXXXXXXXXX
//$urlaprova_orcamento = "id1={$chave_perfil}&id2={$chave_orcamento}&id3={$situacao_orcamento}";
//$urlaprova_orcamento = criptografar($urlaprova_orcamento);
$dompdf = new Dompdf();
$html_pdf = $html;
$html_pdf = str_replace("@pdf@", $pdf_style, $html_pdf);
$dompdf->loadHtml($html_pdf);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
//$dompdf->stream();
$output = $dompdf->output();
$urlpdf_recibo = "rec_{$arqpdf}.pdf";
file_put_contents("../pdfs/{$urlpdf_recibo}", $output);

//********************
//********************
//**** Envia e-mail caso POST chamado contenha diretiva ENVIAR
//********************
//********************
// Avalia informação para inclusão
// Envia e-mail 

$html = str_replace("<body style='font-family: Helvetica, Arial, sans-serif; color: #111111; font-size: 12px; margin: 20px;'>", "<body style='font-family: Helvetica, Arial, sans-serif; color: #111111; font-size: 12px; margin: 20px; max-width:840px; border:1px solid; padding:10px;'>", $html);

$email_assunto = "Recibo";
$email_corpo = "";
$email_corpo .= "Olá, {$nome_cliente}.<br />";
$email_corpo .= "<br />";
$email_corpo .= "Conforme solicitado, segue recibo.<br />";
$email_corpo .= "<br />";
$email_corpo .= $html . "<br />";
$email_corpo .= "<br />";
$email_corpo .= "Atenciosamente,<br />";
$email_corpo .= "<br />";
$email_corpo .= "<br />";
$email_corpo .= "<br />";
$email_corpo .= "{$nome_perfil}<br />";
$email_corpo .= "<br />";
$email_corpo .= "<br />";
$email_corpo .= "<img src='cid:Assinatura_Email' alt='Recibo Express' /><br />";
//Import PHPMailer class into the global namespace
require $Raiz . 'PHPMailer/src/Exception.php';
require $Raiz . 'PHPMailer/src/PHPMailer.php';
require $Raiz . 'PHPMailer/src/SMTP.php';
$msg = "";
date_default_timezone_set('Etc/UTC');
//Create a new PHPMailer instance
try {
  $mail = new PHPMailer();
  $mail->CharSet = 'UTF-8';
  $mail->Encoding = 'base64';
  $mail->isSMTP();
  //$mail->SMTPDebug = 4;  <- desativado por exibe todos os processos em tela.
  //$mail->Host = "smtp.uni5.net";  
  $mail->Host = "smtpi.uni5.net";
  $mail->Port = 587;
  $mail->SMTPAuth = true;
  $mail->SMTPSecure = 'tls';
  $mail->Username = "reciboexpress@guiadomestico.com.br"; // SMTP username
  $mail->Password = 'q#EcXwV5ikqEcXwV5ik'; // SMTP password  
  $mail->From = "reciboexpress@guiadomestico.com.br";
  $mail->FromName = "Recibo Express"; // Nome de quem envia o email
  $mail->AddAddress($email_cliente, $nome_cliente);  
  $mail->addReplyTo($email_usuario, 'Recibo Express');
  $mail->IsHTML(true); // Enviar como HTML  
  $mail->AddEmbeddedImage($Raiz . "identidade/reciboexpress.png", "Assinatura_Email", "reciboexpress.png");
  $mail->Subject = "Recibo Express";
  $mail->Body = $email_corpo;  
  $enviado = $mail->Send(); 
  $mail->ClearAllRecipients(); 
  if ($enviado) {
    $msg = "E-mail enviado com sucesso!";
  }
  else {
    $msg = "Não foi possível enviar o e-mail.\n";
    $msg .= "Detalhes do erro: " . $mail->ErrorInfo;
  }

  $situacao_recibo = "ENVIADO";
  $dte_recibo = date('Y-m-d H:i:s');  
  $abre_diario = abre_diario("trecibo", "chave_recibo", $chave_recibo, $campoexcluidos = array(""));
  $strsql = "
  update 
  trecibo 
  set 
  urlpdf_recibo = :vurlpdf_recibo 
  ,situacao_recibo = :vsituacao_recibo
  ,dte_recibo = :vdte_recibo
  ,valor_recibo = :vvalor_recibo
  ,dtu_recibo = now() 
  where 
  chave_recibo = :vchave_recibo and 
  chave_perfil = :vchave_perfil and 
  caixa_recibo = 1
  ";
  $qrecibo = $pdo->prepare($strsql);
  $qrecibo->bindParam(":vchave_recibo", $chave_recibo);
  $qrecibo->bindParam(":vchave_perfil", $chave_perfil);
  $qrecibo->bindParam(":vurlpdf_recibo", $urlpdf_recibo);
  $qrecibo->bindParam(":vsituacao_recibo", $situacao_recibo);
  $qrecibo->bindParam(":vdte_recibo", $dte_recibo);
  $qrecibo->bindParam(":vvalor_recibo", $total);
  $qrecibo->execute();
  fecha_diario("trecibo", "chave_recibo", $chave_recibo, $abre_diario, $campoexcluidos = array(""));

  $situacao_orcamento = 'RECIBO';
  $dtrecibo_orcamento = date('Y-m-d H:i:s');  
  $abre_diario = abre_diario("torcamento", "chave_orcamento", $chave_orcamento, $campoexcluidos = array(""));
  $strsql = "
  update 
  torcamento 
  set 
  situacao_orcamento = :vsituacao_orcamento 
  ,dtrecibo_orcamento = :vdtrecibo_orcamento 
  ,dtu_orcamento = now() 
  where 
  chave_orcamento = :vchave_orcamento and 
  chave_perfil = :vchave_perfil and 
  caixa_orcamento = 1
  ";
  $qorcamento = $pdo->prepare($strsql);
  $qorcamento->bindParam(":vchave_orcamento", $chave_orcamento);
  $qorcamento->bindParam(":vchave_perfil", $chave_perfil);
  $qorcamento->bindParam(":vsituacao_orcamento", $situacao_orcamento);
  $qorcamento->bindParam(":vdtrecibo_orcamento", $dtrecibo_orcamento);
  $qorcamento->execute();
  fecha_diario("torcamento", "chave_orcamento", $chave_orcamento, $abre_diario, $campoexcluidos = array(""));
}
  
catch (phpmailerException $e) {
  $msg = $e->errorMessage(); //Pretty error messages from PHPMailer
}
catch (Exception $e) {
  $msg = $e->getMessage(); //Boring error messages from anything else!
}
fecha_db();
// EOF Avalia informação para inclusão	

//********************
//********************
//**** EOF - Grava registro caso POST chamado contenha diretiva GRAVAR
//********************
//********************
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Recibo Express - Envio do Recibo</title>
  <?php include($Raiz . "include/html/header.html"); ?>

  <style>
    /* Estilos base inspirados na imagem */
    :root {
      --sidebar-bg: #173b5d;
      /* Azul escuro da barra lateral */
      --body-bg: #f8f8f6;
      /* Fundo suave da área principal */
    }

    body {
      background-color: var(--body-bg);
      min-height: 100vh;
    }

    /* Sidebar Fixo no Desktop */
    .sidebar {
      background-color: var(--sidebar-bg);
      min-height: 100vh;
      color: #ffffff;
    }

    .sidebar .nav-link {
      color: rgba(255, 255, 255, 0.75);
      padding: 0.75rem 1rem;
      border-radius: 0.375rem;
      margin-bottom: 0.25rem;
      transition: all 0.2s ease;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      color: #ffffff;
      background-color: rgba(255, 255, 255, 0.15);
    }

    .sidebar .nav-link i {
      margin-right: 0.5rem;
      font-size: 1.1rem;
    }

    /* Ajuste do conteúdo principal */
    .main-content {
      padding: 10px;
    }
  </style>
</head>

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
            <span class="texto11">RECIBOS . EMISSÃO</span>
          </div>
          <div class="row">
            <div class="col-12">
              <span style="color:#1E2933; font-weight:700; font-size: 1.75em;">Recibo Emitido</span><br>
              <span class="texto-corpo">Este documento foi enviado ao Pagador.</span>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-12">
              <div class="row">
                <div class="col-12 mb-2">
                  <span class="texto11">IMAGEM DO DOCUMENTO</span>
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-6 mt-2 mb-2">
                          <span class="texto40" style="color:#000">RECIBO</span>
                        </div>
                        <div class="col-6 mt-2 mb-2 text-end">
                          <span class="texto12" style="color: #000">RECIBO Nº</span><br>
                          <span class="texto18" style="color:#000"><?php echo $chave_recibo_ext ?></span><br>
                          <span class="texto14-regular" style="color:#000"><?php echo "Data: " . $dte_recibo_pdf ?></span><br>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <span class="texto12" style="color:#000">RECEBI DE</span>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <span class="texto21"><?php echo $nome_cliente ?></span><br>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12 text-end">
                          <span class="texto12" style="color:#000">A IMPORTÂNCIA DE</span>
                        </div>
                      </div>
                      <div class="row" style="margin-bottom:14px;">
                        <div class="col-12 text-end">
                          <span class="texto19" style="color: #000; vertical-align: super;">R$</span><span class="texto40">&nbsp;<?php echo number_format(floatval($total), 2, ',', '.'); ?></span>
                        </div>
                      </div>
                      <div class="row" style="margin-bottom:6px;">
                        <div class="col-6">
                          <span class="texto12" style="color:#000">REFERENTE A</span>
                        </div>
                        <div class="col-1 text-end">
                          <span class="texto12" style="color: #000">QTD</span>
                        </div>
                        <div class="col-2 text-end">
                          <span class="texto12" style="color: #000">R$ TOTAL</span>
                        </div>
                        <div class="col-3 text-end">
                          <span class="texto12" style="color: #000">REALIZADA EM</span>
                        </div>
                      </div>
                      <div class="row" style="margin-bottom:14px;">
                        <?php echo $html_recibosrv_compac2 ?>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <hr>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <span class="texto12-regular" style="color: #000">Atenciosamente,</span><br><br>
                          <hr>
                          <span class="texto12" style="color: #000"><?php echo $nome_perfil; ?></span><br>
                          <span class="texto12-regular" style="color: #000"><?php echo "CPF: " . $cpf_perfil; ?></span>
                        </div>
                        <div class="col-6 d-flex flex-column justify-content-end text-end">
                          <span class="texto9">RECIBO ENVIADO</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
  <!-- FIM DA ÁREA DE CONTEÚDO -->
  <?php include($Raiz . "include/php/rodape.php"); ?>
</body>

</html>
<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /publico/orcamento/orcamento_email.php
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
include($Raiz . "include/php/header.php");
include($Raiz . "include/php/funcoes.php");
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
$chave_perfil = 14;
$chave_orcamento = 13;
$chave_cliente = "";
$nome_cliente = "";
$cpf_cliente = "";
$cep_cliente = "";
$email_usuario = "";
$nome_perfil = "";
$cpf_perfil = "";
$situacao_orcamento = "";
$pdf = "";
$pdf_style = "font-family: Arial, Helvetica, sans-serif; font-size: 12px;";
// EOF Variáveis de controle
$aftergopage = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST["chave_orcamento"])) {
    $chave_orcamento = $_POST["chave_orcamento"];
  }
  if ($chave_orcamento != "") {
    $_SESSION["CHAVE_ORCAMENTO"] = $chave_orcamento;
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
  torcamento.*
  ,tcliente.nome_cliente
  ,tcliente.cpf_cliente 
  ,tcliente.cep_cliente 
  ,tcliente.email_cliente 
  from
  torcamento
  left join tcliente on torcamento.chave_cliente = tcliente.chave_cliente
  where    
  torcamento.chave_orcamento = :vchave_orcamento and
  torcamento.chave_perfil = :vchave_perfil and
  torcamento.caixa_orcamento = 1
  ";
  $qorcamento = $pdo->prepare($strsql);
  $qorcamento->bindParam(":vchave_orcamento", $chave_orcamento);
  $qorcamento->bindParam(":vchave_perfil", $chave_perfil);
  $qorcamento->execute();
  if ($torcamento = $qorcamento->fetch(PDO::FETCH_ASSOC)) {
    $chave_cliente = $torcamento["chave_cliente"];
    $nome_cliente = $torcamento["nome_cliente"];
    $cpf_cliente = $torcamento["cpf_cliente"];
    $cep_cliente = $torcamento["cep_cliente"];
    $email_cliente = $torcamento["email_cliente"];
    $dtc = FormataData($torcamento["dtc_orcamento"], 'Y-m-d');
    $situacao_orcamento = $torcamento["situacao_orcamento"];
    $dte_orcamento_pdf = date('d/m/Y');
    $html_orcamentosrv = "";
    $html_orcamentosrv_compac = "";
    $html_orcamentosrv_compac2 = "";
    $contador = 0;
    $subtotal = 0;
    $total = 0;
    abre_db();
    $strsql = "
    select
    torcamentosrv.*
    ,tservico.descr_servico
    from
    torcamentosrv 
    left join torcamento on torcamentosrv.chave_orcamento = torcamento.chave_orcamento
    left join tservico on torcamentosrv.chave_servico = tservico.chave_servico
    where 
    torcamentosrv.chave_orcamento = :vchave_orcamento and
    torcamento.chave_perfil = :vchave_perfil and
    torcamentosrv.caixa_orcamentosrv = 1
    ";
    $qorcamentosrv = $pdo->prepare($strsql);
    $qorcamentosrv->bindParam(":vchave_orcamento", $chave_orcamento);
    $qorcamentosrv->bindParam(":vchave_perfil", $chave_perfil);
    $qorcamentosrv->execute();
    while ($torcamentosrv = $qorcamentosrv->fetch(PDO::FETCH_ASSOC)) {
      $chave_orcamentosrv = $torcamentosrv["chave_orcamentosrv"];
      $chave_servico = $torcamentosrv["chave_servico"];
      $descr_servico = $torcamentosrv["descr_servico"];
      $qtd_orcamentosrv = $torcamentosrv["qtd_orcamentosrv"];
      $vlu_orcamentosrv = $torcamentosrv["vlu_orcamentosrv"];
      $vlt_orcamentosrv = $torcamentosrv["vlt_orcamentosrv"];
      $subtotal += (intval($qtd_orcamentosrv) * floatval($vlu_orcamentosrv));
      $total += (intval($qtd_orcamentosrv) * floatval($vlu_orcamentosrv));
      $contador += 1;
      $html_orcamentosrv_compac .= "
      <tr>
        <td style='vertical-align: top; text-align: left;'>
          <div style='font-size: 13px;'>{$descr_servico}</div>
        </td>
        <td style='vertical-align: top; text-align: right;'>
          <div style='font-size: 13px;'>{$qtd_orcamentosrv}</div>
        </td>
        <td style='vertical-align: top; text-align: right;'>
          <div style='font-size: 13px;'>" . Float2Moeda($vlt_orcamentosrv, 2) . "</div>
        </td>
        <td style='vertical-align: top; text-align: right;'>
          <div style='font-size: 13px;'>" . strtoupper(DiaMesExtAno($dtc)) . "</div>
        </td>
      </tr>
      ";
      $html_orcamentosrv_compac2 .= "
      <div class='col-6'>
        <span class='texto13-regular'>{$descr_servico}</span>
      </div>
      <div class='col-1 text-end'>
        <span class='texto13-regular'>{$qtd_orcamentosrv}</span>
      </div>
      <div class='col-2 text-end'>
        <span class='texto13-regular'>" . Float2Moeda($vlt_orcamentosrv, 2) . "</span>
      </div>
      <div class='col-3 text-end'>
        <span class='texto13-regular'>" . strtoupper(DiaMesExtAno($dtc)) . "</span>
      </div>";

    }
  }
  $chave_orcamento_ext = str_pad($chave_orcamento, 6, "0", STR_PAD_LEFT);
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
    <!-- Cabeçalho: ORÇAMENTO e NÚMERO/DATA -->
    <tr>
      <td colspan='2' style='vertical-align: top; text-align: left; padding-bottom: 25px;'>
        <h1 style='margin: 0; font-size: 32px; font-weight: bold; letter-spacing: 1px;'>ORCAMENTO</h1>
      </td>
      <td colspan='2' style='vertical-align: top; text-align: right; padding-bottom: 25px;'>
        <div style='font-size: 10px; text-transform: uppercase; color: #444444;'>ORCAMENTO Nº</div>
        <div style='font-size: 16px; font-weight: bold; margin-bottom: 4px;'>{$chave_orcamento_ext}</div>
        <div style='font-size: 11px; color: #444444;'>Data: {$dte_orcamento_pdf}</div>
      </td>
    </tr>
    <!-- Linha do Aos Cuidados de e Valor -->
    <tr>
      <td colspan='3' style='vertical-align: top; text-align: left; padding-bottom: 25px;'>
        <div style='font-size: 9px; text-transform: uppercase; color: #555555; font-weight: bold; margin-bottom: 5px;'>AOS CUIDADOS DE</div>
        <div style='font-size: 16px; font-weight: bold;'>{$nome_cliente}</div>
      </td>
      <td style='vertical-align: top; text-align: right; padding-bottom: 25px;'>
        <div style='font-size: 9px; text-transform: uppercase; color: #555555; font-weight: bold; margin-bottom: 5px;'>TOTAL DO ORÇAMENTO</div>
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
    {$html_orcamentosrv_compac}
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
        <div style='font-size: 8px; color: #999999; text-transform: uppercase; letter-spacing: 0.5px;'>Emitido com orcamentoexpress.com.br</div>
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
$arqpdf = criptografar($chave_orcamento_ext);
$dompdf = new Dompdf();
$html_pdf = $html;
$html_pdf = str_replace("@pdf@", $pdf_style, $html_pdf);
$dompdf->loadHtml($html_pdf);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
//$dompdf->stream();
$output = $dompdf->output();
$urlpdf_orcamento = "orc_{$arqpdf}.pdf";
file_put_contents("../pdfs/{$urlpdf_orcamento}", $output);

//********************
//********************
//**** Envia e-mail caso POST chamado contenha diretiva ENVIAR
//********************
//********************
// Avalia informação para inclusão
// Envia e-mail 

$situacao_orcamento = "ENVIADO";
//?chave_perfil&chave_orcamento&situacao_orcamento
//?id1=000001&id2=000001&id3=XXXXXXXXXXXXXXXXX
$urlaprova_orcamento = "id1={$chave_perfil}&id2={$chave_orcamento}&id3={$situacao_orcamento}";
$urlaprova_orcamento = criptografar($urlaprova_orcamento);

$html = str_replace("<body style='font-family: Helvetica, Arial, sans-serif; color: #111111; font-size: 12px; margin: 20px;'>", "<body style='font-family: Helvetica, Arial, sans-serif; color: #111111; font-size: 12px; margin: 20px; max-width:840px; border:1px solid; padding:10px;'>", $html);
$email_assunto = "Orcamento";
$email_corpo = "";
$email_corpo .= "Olá, {$nome_cliente}.<br />";
$email_corpo .= "<br />";
$email_corpo .= "Conforme solicitado, segue orcamento.<br />";
$email_corpo .= "<br />";
$email_corpo .= $html;
$email_corpo .= "<br />";
$email_corpo .= "<br />";
$email_corpo .= "<br />";
$email_corpo .= "<a href='https://reciboexpress.guiadomestico.com.br/publico/orcamento/orcamento_status.php?id1={$urlaprova_orcamento}&id2=" . criptografar("APROVADO") . "' style='background-color: #25d366; color: #ffffff; border: none; padding: 12px 28px; border-radius: 25px; font-size: 16px; font-family: Arial, sans-serif; font-weight: 500; cursor: pointer; margin-right: 12px; display: inline-block;'>";
$email_corpo .= "  Aprovar";
$email_corpo .= "</a>";
$email_corpo .= "<a href='https://reciboexpress.guiadomestico.com.br/publico/orcamento/orcamento_status.php?id1={$urlaprova_orcamento}&id2=" . criptografar("RECUSADO") . "' style='background-color: #dc3545; color: #ffffff; border: none; padding: 12px 28px; border-radius: 25px; font-size: 16px; font-family: Arial, sans-serif; font-weight: 500; cursor: pointer; display: inline-block;'>";
$email_corpo .= "  Reprovar";
$email_corpo .= "</a>";
$email_corpo .= "<br />";
$email_corpo .= "<br />";
$email_corpo .= "<br />";
$email_corpo .= "Atenciosamente, ";
$email_corpo .= "<br />";
$email_corpo .= "<br />";
$email_corpo .= "<br />";
$email_corpo .= "____________________________________________<br />";
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
  //$mail->SMTPDebug = 4; 
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

  $dte_orcamento = date('Y-m-d H:i:s');  
  $abre_diario = abre_diario("torcamento", "chave_orcamento", $chave_orcamento, $campoexcluidos = array(""));
  $strsql = "
  update 
  torcamento 
  set 
  urlpdf_orcamento = :vurlpdf_orcamento 
  ,urlaprova_orcamento = :vurlaprova_orcamento 
  ,situacao_orcamento = :vsituacao_orcamento
  ,dte_orcamento = :vdte_orcamento
  ,dtu_orcamento = now() 
  where 
  chave_orcamento = :vchave_orcamento and 
  chave_perfil = :vchave_perfil and 
  caixa_orcamento = 1
  ";
  $qorcamento = $pdo->prepare($strsql);
  $qorcamento->bindParam(":vchave_orcamento", $chave_orcamento);
  $qorcamento->bindParam(":vchave_perfil", $chave_perfil);
  $qorcamento->bindParam(":vurlpdf_orcamento", $urlpdf_orcamento);
  $qorcamento->bindParam(":vurlaprova_orcamento", $urlaprova_orcamento);
  $qorcamento->bindParam(":vsituacao_orcamento", $situacao_orcamento);
  $qorcamento->bindParam(":vdte_orcamento", $dte_orcamento);
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
  <title>Recibo Express - Envio do Orçamento</title>
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
            <span class="texto11">ORÇAMENTOS . EMISSÃO</span>
          </div>
          <div class="row">
            <div class="col-12">
              <span style="color:#1E2933; font-weight:700; font-size: 1.75em;">Orçamento Enviado</span><br>
              <span class="texto-corpo">Este documento foi enviado ao Cliente.</span>
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
                          <span class="texto40" style="color:#000">ORÇAMENTO</span>
                        </div>
                        <div class="col-6 mt-2 mb-2 text-end">
                          <span class="texto12" style="color: #000">ORÇAMENTO Nº</span><br>
                          <span class="texto18" style="color:#000"><?php echo $chave_orcamento_ext ?></span><br>
                          <span class="texto14-regular" style="color:#000"><?php echo "Data: " . $dte_orcamento_pdf ?></span><br>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <span class="texto12" style="color:#000">AOS CUIDADOS DE</span>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <span class="texto21"><?php echo $nome_cliente ?></span><br>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12 text-end">
                          <span class="texto12" style="color:#000">TOTAL DO ORÇAMENTO</span>
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
                        <?php echo $html_orcamentosrv_compac2 ?>
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
                          <span class="texto9">ORÇAMENTO ENVIADO</span>
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
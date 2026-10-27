<?php
/*
 **************************************************
 **************************************************
 ***** 
 ***** APLICAÇÃO
 ***** Recibo Express
 ***** Arquivo: /entrar/callback.php
 ***** Conteúdo: 01-entrar
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
//header('Content-Type: application/json');
header('Content-Type: application/json; charset=utf-8');
session_start();
$Raiz = "../../";
include($Raiz . "include/php/header.php"); // Configurações HEADER
include($Raiz . "include/php/funcoes.php"); // Funcoes de Usuário PHP
include($Raiz . "conexao/db.php");
//VerificaSessao();
// Variáveis de controle
$ok = true;

require_once '../vendor/autoload.php';

// Configuração do Banco de Dados
/*
$host = 'localhost';
$db   = 'seu_banco';
$user = 'seu_usuario';
$pass = 'sua_senha';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão com o banco.']);
    exit;
}
*/
// Ler o corpo da requisição JSON
$input = json_decode(file_get_contents('php://input'), true);
$idToken = $input['token'] ?? null;

if (!$idToken) {
    echo json_encode(['success' => false, 'message' => 'Token não fornecido.']);
    exit;
}

$CLIENT_ID = '682512042222-4kun1ghgo6t0d7s70k8u7c42k186fseg.apps.googleusercontent.com';

// Instancia o validador do Google Client
$client = new Google_Client(['client_id' => $CLIENT_ID]);

try {
    // Valida a assinatura, expiração e a audiesse do ID Token
    $payload = $client->verifyIdToken($idToken);

    if ($payload) {
        $googleId = $payload['sub'];
        $email    = $payload['email'];
        $nome     = $payload['name'];
        $foto     = $payload['picture'] ?? null;
/*
        // Verifica se o usuário já existe no banco
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE google_id = :google_id");
        $stmt->execute(['google_id' => $googleId]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            // Cadastra novo usuário
            $stmtInsert = $pdo->prepare("INSERT INTO usuarios (google_id, nome, email, foto) VALUES (:google_id, :nome, :email, :foto)");
            $stmtInsert->execute([
                'google_id' => $googleId,
                'nome'      => $nome,
                'email'     => $email,
                'foto'      => $foto
            ]);
        } else {
            // Atualiza os dados cadastrais (ex: foto ou nome alterados no Google)
            $stmtUpdate = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, foto = :foto WHERE google_id = :google_id");
            $stmtUpdate->execute([
                'nome'      => $nome,
                'email'     => $email,
                'foto'      => $foto,
                'google_id' => $googleId
            ]);
        }
*/
        // Inicia a sessão PHP
//        session_start();
//        $_SESSION['user_id'] = $googleId;
//        $_SESSION['email']   = $email;

        echo json_encode([
            'success' => true,
            'user' => [
                'nome'  => $nome,
                'email' => $email,
                'foto'  => $foto
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Token do Google inválido.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao processar token: ' . $e->getMessage()]);
}

?>

<?php
// admin/testes/executar_testes.php
header('Content-Type: application/json; charset=utf-8');

// Aponta para a raiz do projeto (ajuste o caminho relativo conforme a localização do arquivo)
$projetoPath = realpath(__DIR__ . '/../../');

// Caso o realpath falhe ou esteja em ambiente local específico do Windows, defina o caminho absoluto:
if (!$projetoPath || !file_exists($projetoPath . '/package.json')) {
    $projetoPath = 'C:\\PUBLICO\\RECIBOEXPRESS';
}

// Comando executado no Windows:
// 1. Alterna para a unidade e diretório do projeto (cd /d)
// 2. Executa o vitest via npx sem modo interativo/watch (--run) e formatado em JSON (--reporter=json)
// 3. Redireciona a saída de erro padrão para a saída padrão (2>&1)
//$comando = "cd /d \"{$projetoPath}\" && npx vitest run --reporter=json 2>&1";
$comando = "cd /d \"{$projetoPath}\" && npm run test:json 2>&1";

$output = shell_exec($comando);

if ($output === null) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Falha ao executar o comando no servidor PHP (shell_exec desabilitado ou sem permissão).'
    ]);
    exit;
}

// Localiza o início e o fim da estrutura JSON ({ ... }) na resposta do terminal
$jsonStart = strpos($output, '{');
$jsonEnd   = strrpos($output, '}');

if ($jsonStart !== false && $jsonEnd !== false && $jsonEnd > $jsonStart) {
    $jsonOutput = substr($output, $jsonStart, ($jsonEnd - $jsonStart + 1));
    $decoded    = json_decode($jsonOutput, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        echo json_encode([
            'status' => 'success',
            'data'   => $decoded
        ]);
        exit;
    }
}

// Caso falhe no parse, retorna o output bruto para depuração no DevTools
http_response_code(500);
echo json_encode([
    'status'     => 'error',
    'message'    => 'A resposta do Vitest não veio em formato JSON válido.',
    'raw_output' => $output
]);
exit;
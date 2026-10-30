<?php
define('SECRET_KEY', '12345678901234567890123456789012'); // Chave de 32 bytes (256 bits)
define('CIPHER_METHOD', 'aes-256-cbc');
/**
 * Criptografa uma string
 */
function criptografar($dados) {
    // 1. Descobre o tamanho do IV
    $ivLength = openssl_cipher_iv_length(CIPHER_METHOD);
    // 2. Gera um IV aleatório seguro
    $iv = random_bytes($ivLength); // Recomendado utilizar random_bytes() no PHP 7+
    // 3. Criptografa retornando dados brutos (OPENSSL_RAW_DATA)
    $encrypted = openssl_encrypt($dados, CIPHER_METHOD, SECRET_KEY, OPENSSL_RAW_DATA, $iv);
    // 4. Concatena IV binário + Texto Criptografado binário e converte em base64
    return bin2hex(base64_encode($iv . $encrypted));
}

/**
 * Descriptografa uma string
 */
function descriptografar($dadosCriptografados) {    
    $dadosCriptografados = hex2bin($dadosCriptografados);
    // 1. Decodifica o base64 para obter a string binária
    $data = base64_decode($dadosCriptografados, true);
    if ($data === false) {
        return false;
    }
    // 2. Descobre o tamanho do IV
    $ivLength = openssl_cipher_iv_length(CIPHER_METHOD);
    // 3. Extrai o IV
    $iv = substr($data, 0, $ivLength);
    // 4. Extrai o texto cifrado bruto
    $encryptedText = substr($data, $ivLength);   
    // 5. Descriptografa informando a flag OPENSSL_RAW_DATA
    return openssl_decrypt($encryptedText, CIPHER_METHOD, SECRET_KEY, OPENSSL_RAW_DATA, $iv);
}

//define('SECRET_KEY', '12345678901234567890123456789012');
//define('CIPHER_METHOD', 'aes-256-cbc');
///**
// * Criptografa uma string
// */
//function criptografar($dados) {
//  // 1. Descobre o tamanho necessário do IV para o algoritmo
//  $ivLength = openssl_cipher_iv_length(CIPHER_METHOD);
//  // 2. Gera um IV aleatório e seguro
//  $iv = openssl_random_pseudo_bytes($ivLength);
//  // 3. Criptografa os dados
//  $encrypted = openssl_encrypt($dados, CIPHER_METHOD, SECRET_KEY, 0, $iv);
//  // 4. Junta o IV com o texto criptografado e codifica em base64 para facilitar o armazenamento/transporte
//  return base64_encode($iv . $encrypted);
//}
//
///**
// * Descriptografa uma string
// */
//function descriptografar($dadosCriptografados) {
//  // 1. Decodifica o base64
//  $data = base64_decode($dadosCriptografados);
//  
//  // 2. Descobre o tamanho do IV
//  $ivLength = openssl_cipher_iv_length(CIPHER_METHOD);
//  
//  // 3. Extrai o IV do início da string
//  $iv = substr($data, 0, $ivLength);
//  
//  // 4. Extrai o texto criptografado (o restante da string)
//  $encryptedText = substr($data, $ivLength);
//  
//  // 5. Descriptografa os dados usando o mesmo IV e chave
//  return openssl_decrypt($encryptedText, CIPHER_METHOD, SECRET_KEY, 0, $iv);
?>
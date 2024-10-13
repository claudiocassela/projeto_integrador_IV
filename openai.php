<?php
$url = "https://api-inference.huggingface.co/models/gpt2";

// Token de autenticação da Hugging Face (você precisará de uma chave API gratuita)
$api_key = "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";

// Dados de entrada para o modelo
$data = array(
    "inputs" => "Qual a estimativa de temperatura e preciptação pluviométrica para a cidade de Sorocaba/Sp para o dia 03/10/2025?"
);

// Configurando o cabeçalho da requisição, incluindo a chave de API
$headers = array(
    "Authorization: Bearer " . $api_key,
    "Content-Type: application/json"
);

// Inicializando a requisição cURL
$ch = curl_init();

// Configurações da requisição
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Executa a requisição e captura a resposta
$response = curl_exec($ch);

// Verifica se há erros na requisição
if (curl_errno($ch)) {
    echo 'Erro na requisição: ' . curl_error($ch);
} else {
    // Exibe a resposta da API
    $result = json_decode($response, true);
    echo "Resultado da IA: " . $result[0]['generated_text'];
}

// Fecha a conexão cURL
curl_close($ch);
?>
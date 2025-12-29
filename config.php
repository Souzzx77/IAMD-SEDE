<?php
define("OPENAI_API_KEY", "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");

<?php
require "config.php";

$dados = json_decode(file_get_contents("php://input"), true);
$mensagem = $dados["mensagem"] ?? "";

$ch = curl_init("https://api.openai.com/v1/chat/completions");

curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_HTTPHEADER => [
    "Content-Type: application/json",
    "Authorization: Bearer " . OPENAI_API_KEY
  ],
  CURLOPT_POSTFIELDS => json_encode([
    "model" => "gpt-4o-mini",
    "messages" => [
      [
        "role" => "system",
        "content" => "Você é uma IA cristã, responde com amor, sabedoria bíblica e respeito."
      ],
      [
        "role" => "user",
        "content" => $mensagem
      ]
    ]
  ])
]);

$resposta = curl_exec($ch);
curl_close($ch);

echo $resposta;


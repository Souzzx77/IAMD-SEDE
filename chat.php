<?php
header("Content-Type: application/json");

// recebe a mensagem do frontend
$dados = json_decode(file_get_contents("php://input"), true);

if (!isset($dados["mensagem"])) {
  echo json_encode(["resposta" => "Nenhuma mensagem recebida."]);
  exit;
}

$mensagem = $dados["mensagem"];

// 🔒 SUA CHAVE (fica só aqui)
$apiKey = "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";

// payload correto (API nova)
$payload = [
  "model" => "gpt-4.1-mini",
  "input" => [
    [
      "role" => "system",
      "content" => [
        [
          "type" => "text",
          "text" => "Você é uma IA cristã. Responda com amor, sabedoria bíblica, linguagem jovem, respeitosa e acolhedora. Não julgue o usuário. Seja empático, sincero e pastoral."
        ]
      ]
    ],
    [
      "role" => "user",
      "content" => [
        [
          "type" => "text",
          "text" => $mensagem
        ]
      ]
    ]
  ]
];

$ch = curl_init("https://api.openai.com/v1/responses");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Content-Type: application/json",
  "Authorization: Bearer $apiKey"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$resposta = curl_exec($ch);

if ($resposta === false) {
  echo json_encode(["resposta" => "Erro ao conectar com a IA."]);
  exit;
}

curl_close($ch);

$resultado = json_decode($resposta, true);

// extrai o texto corretamente
if (!isset($resultado["output"][0]["content"][0]["text"])) {
  echo json_encode(["resposta" => "A IA respondeu, mas sem texto."]);
  exit;
}

echo json_encode([
  "resposta" => $resultado["output"][0]["content"][0]["text"]
]);

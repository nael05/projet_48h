<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$configFile = __DIR__ . '/../api_google/config.php';
if (!file_exists($configFile)) {
    echo json_encode(["error" => "Config file missing"]);
    exit;
}

$config = require $configFile;

$apiKey = $config['GEMINI_API_KEY'] ?? '';
$baseUrl = $config['GEMINI_API_URL'] ?? 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';
$url = $baseUrl . '?key=' . $apiKey;

$input = json_decode(file_get_contents('php://input'), true);
$message = $input['message'] ?? '';

if (empty($apiKey)) {
    echo json_encode(["candidates" => [["content" => ["parts" => [["text" => "Désolé, la clé API Gemini n'est pas configurée dans api_google/config.php."]]]]]]);
    exit;
}

$data = [
    "contents" => [
        [
            "role" => "user",
            "parts" => [
                ["text" => $message]
            ]
        ]
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

$response = curl_exec($ch);

if(curl_errno($ch)){
    echo json_encode(["error" => curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);
echo $response;
?>

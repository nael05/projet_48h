<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$config = require 'config.php';

$apiKey = $config['GEMINI_API_KEY'];
$baseUrl = $config['GEMINI_API_URL'];
$url = $baseUrl . '?key=' . $apiKey;

$input = json_decode(file_get_contents('php://input'), true);
$message = $input['message'] ?? '';

$data = [
    "contents" => [
        [
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
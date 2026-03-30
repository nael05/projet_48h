<?php
$apiKey = 'AIzaSyB4VBszmcw4Cos8e-bV1ZF6yaMXYuGhPYk';
$url = "https://generativelanguage.googleapis.com/v1beta/models?key=" . $apiKey;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

$response = curl_exec($ch);
$data = json_decode($response, true);

echo "<h2>Modèles disponibles pour ta clé :</h2>";
if (isset($data['models'])) {
    foreach ($data['models'] as $model) {
        echo "<li><strong>" . $model['name'] . "</strong> (Supporte : " . implode(', ', $model['supportedGenerationMethods']) . ")</li>";
    }
} else {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}
curl_close($ch);
?>
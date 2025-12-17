<?php
function chatgpt_request($userMessage, $apiKey) {
    $endpoint = "https://api.openai.com/v1/chat/completions";

    $data = [
        "model" => "gpt-3.5-turbo", //or gpt-4
        "messages" => [
            ["role" => "system", "content" => "You are a helpful assistant."],
            ["role" => "user", "content" => $userMessage]
        ],
        "temperature" => 0.7
    ];

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    if(curl_errno($ch)){
        return 'cURL Error: ' . curl_error($ch);
    }
    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result['choices'][0]['message']['content'])) {
        return $result['choices'][0]['message']['content'];
    } else {
        return 'No response from API';
    }
}


$apiKey = "YOUR_OPENAI_API_KEY";
$userMessage = "Здравей, можеш ли да ми кажеш времето в София?";

$response = chatgpt_request($userMessage, $apiKey);
echo "<pre>$response</pre>";
?>

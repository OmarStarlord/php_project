<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Date Extraction</title>
</head>
<body>
    <h1>Date Extraction Result</h1>

    <?php
        require 'vendor/autoload.php';

        // Set your OpenAI API key
        $apiKey = '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';

        function extractDateFromText($inputText, $apiKey) {
            // Set up Guzzle client
            $httpClient = new \GuzzleHttp\Client();

            // OpenAI API endpoint
            $apiEndpoint = 'https://api.openai.com/v1/engines/text-davinci-003/completions';

            // Set the prompt and other parameters
            $data = [
                'prompt' => $inputText,
                'max_tokens' => 150,
                'temperature' => 0.7,
                'top_p' => 1.0,
                'frequency_penalty' => 0.0,
                'presence_penalty' => 0.0,
            ];

            // Send a POST request to the OpenAI API
            $response = $httpClient->post($apiEndpoint, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $apiKey,
                ],
                'json' => $data,
            ]);

            // Decode the JSON response
            $responseData = json_decode($response->getBody(), true);

            // Extract date from the model's response
            $extractedDate = trim($responseData['choices'][0]['text']);

            return $extractedDate;
        }

        // Example usage with a file
        $filePath = 'result.txt';
        $inputText = file_get_contents($filePath);

        $extractedDate = extractDateFromText($inputText, $apiKey);
    ?>

    <p>Extracted date: <?php echo $extractedDate; ?></p>
</body>
</html>

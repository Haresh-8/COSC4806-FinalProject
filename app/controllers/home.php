<?php
require_once __DIR__ . '/../core/config.php';

class Home extends Controller {

    public function index() {
        if (!isset($_SESSION['auth'])) {
            header("Location: /login");
            exit;
        }
        // Guests can view home page, so no redirect here
        // But we can still track if logged in:
        $isLoggedIn = isset($_SESSION['auth']);

        $_SESSION['controller'] = 'home';

        $apiModel = $this->model('Api');
        $topMovies = $apiModel->getTopRatedMovies();

        $geminiResponse = $this->generateGeminiText("List 5 trending movies to watch this week.");

        $data = [
            'topMovies' => $topMovies,
            'gemini_response' => $geminiResponse,
            'gemini_error' => null
        ];

        $this->view('home/index', $data);
    }

    private function generateGeminiText($prompt, $maxRetries = 3, $retryDelay = 3) {
        if (!defined('GEMINI_API_KEY') || empty(GEMINI_API_KEY)) {
            return "Gemini API key is not configured.";
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . GEMINI_API_KEY;

        $payload = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ]
        ];

        $attempt = 0;
        do {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            if (!$response) {
                return "Failed to connect to Gemini API: " . $error;
            }

            $decoded = json_decode($response, true);

            if (isset($decoded['error'])) {
                if (str_contains($decoded['error']['message'], 'overloaded') && $attempt < $maxRetries) {
                    $attempt++;
                    sleep($retryDelay);  // wait before retrying
                    continue;  // retry loop
                }
                return "Gemini API error: " . htmlspecialchars($decoded['error']['message']);
            }

            return $decoded['candidates'][0]['content']['parts'][0]['text'] ?? "No response from Gemini.";

        } while ($attempt < $maxRetries);

        return "Gemini API is currently overloaded. Please try again later.";
    }

}
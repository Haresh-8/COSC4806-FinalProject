<?php

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../core/config.php'; // Load constants

class Api {

    // Fetch top-rated movies
    public function getTopRatedMovies() {
        $db = db_connect();

        $query = "
            SELECT 
                movies.imdb_id, movies.title, movies.year, movies.poster,
                AVG(ratings.rating) AS avg_rating, COUNT(ratings.id) AS rating_count
            FROM movies
            LEFT JOIN ratings ON movies.imdb_id = ratings.imdb_id
            GROUP BY movies.imdb_id, movies.title, movies.year, movies.poster
            ORDER BY avg_rating DESC
            LIMIT 10
        ";

        $stmt = $db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Search movie by title or IMDb ID
    public function searchMovie($query) {
        if (!defined('OMDB_API_KEY') || empty(OMDB_API_KEY)) {
            throw new Exception("OMDB_API_KEY is not configured in config.php.");
        }

        if (empty($query)) {
            throw new Exception("Movie title or IMDb ID cannot be empty.");
        }

        $param = (str_starts_with((string)$query, 'tt')) ? "i" : "t";
        $query = urlencode((string)$query);

        $url = "http://www.omdbapi.com/?apikey=" . OMDB_API_KEY . "&$param=$query";
        $response = @file_get_contents($url);

        if ($response === false) {
            throw new Exception("Failed to connect to OMDB API.");
        }

        $data = json_decode($response, true);

        if (!$data || (isset($data['Response']) && $data['Response'] === "False")) {
            throw new Exception("OMDB: " . ($data['Error'] ?? 'Unknown error'));
        }

        return $data;
    }

    // Save searched movie info to database
    public function saveMovie($movieData) {
        if (!isset($movieData['imdbID']) || empty($movieData['imdbID'])) {
            throw new Exception("Invalid movie data: imdbID missing.");
        }

        $db = db_connect();

        $stmt = $db->prepare("SELECT imdb_id FROM movies WHERE imdb_id = :imdb_id");
        $stmt->execute(['imdb_id' => $movieData['imdbID']]);
        if ($stmt->fetch()) return; // Already saved

        $stmt = $db->prepare("INSERT INTO movies (imdb_id, title, year, poster) 
                              VALUES (:imdb_id, :title, :year, :poster)");

        $stmt->execute([
            'imdb_id' => $movieData['imdbID'],
            'title'   => $movieData['Title'] ?? '',
            'year'    => $movieData['Year'] ?? '',
            'poster'  => $movieData['Poster'] ?? ''
        ]);
    }

    // Save or update user rating
    public function saveRating($user_id, $imdb_id, $rating) {
        $db = db_connect();

        $stmt = $db->prepare("INSERT INTO ratings (user_id, imdb_id, rating) 
                              VALUES (:user_id, :imdb_id, :rating)
                              ON DUPLICATE KEY UPDATE rating = VALUES(rating)");

        $stmt->execute([
            'user_id' => $user_id,
            'imdb_id' => $imdb_id,
            'rating'  => $rating
        ]);
    }

    // Get average rating of a movie
    public function getAverageRating($imdb_id) {
        $db = db_connect();
        $stmt = $db->prepare("SELECT AVG(rating) AS avg_rating FROM ratings WHERE imdb_id = :imdb_id");
        $stmt->execute(['imdb_id' => $imdb_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['avg_rating'] ?? 0;
    }

    // Get logged-in user's rating for a movie
    public function getUserRating($user_id, $imdb_id) {
        $db = db_connect();
        $stmt = $db->prepare("SELECT rating FROM ratings WHERE user_id = :user_id AND imdb_id = :imdb_id");
        $stmt->execute([
            'user_id' => $user_id,
            'imdb_id' => $imdb_id
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['rating'] ?? null; // Return null if not rated yet
    }

    // Gemini AI Review
    public function generateGeminiReview($movieTitle) {
        if (!defined('GEMINI_API_KEY') || empty(GEMINI_API_KEY)) {
            throw new Exception("GEMINI_API_KEY is not configured in config.php.");
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . GEMINI_API_KEY;
        $promptText = "Write a short friendly review for the movie titled: $movieTitle";

        $payload = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $promptText]
                    ]
                ]
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        if ($response === false) {
            return "Gemini API connection failed. Try again later.";
        }
        curl_close($ch);

        $decoded = json_decode($response, true);

        // Graceful error handling
        if (isset($decoded['error'])) {
            $message = $decoded['error']['message'];
            if (str_contains(strtolower($message), 'overloaded')) {
                return "Gemini AI is busy right now. Please try again later.";
            }
            return " Gemini API error: " . htmlspecialchars($message);
        }

        return $decoded['candidates'][0]['content']['parts'][0]['text'] 
            ?? " No review generated at the moment.";
    }

}

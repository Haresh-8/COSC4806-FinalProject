<?php

class Movie extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['auth'])) {
            header("Location: /login");
            exit;
        }

        $_SESSION['controller'] = 'movie';

        // Prefer URL query ?title= over session
        $title = trim($_GET['title'] ?? ($_SESSION['last_search_title'] ?? ''));

        $data = [
            'error'   => $_SESSION['error'] ?? null,
            'success' => $_SESSION['success'] ?? null,
            'title'   => $title,
        ];

        // ✅ Store last searched title in session
        if (!empty($title)) {
            $_SESSION['last_search_title'] = $title;
        }

        unset($_SESSION['error'], $_SESSION['success']);
        $this->view('movie/index', $data);
    }

    public function search()
    {
        if (!isset($_SESSION['auth'])) {
            header("Location: /login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /movie");
            exit;
        }

        $title = trim($_POST['title'] ?? '');
        if (empty($title)) {
            $_SESSION['error'] = "Please enter a movie title.";
            header("Location: /movie");
            exit;
        }

        $apiModel = $this->model('Api');

        try {
            $movieData = $apiModel->searchMovie($title);
            $apiModel->saveMovie($movieData);

            // ✅ Store last searched title and imdbID for back navigation
            $_SESSION['last_search_title'] = $title;
            $_SESSION['last_searched_movie_imdbID'] = $movieData['imdbID'] ?? null;

            $data = [
                'movie'         => $movieData,
                'avg_rating'    => $apiModel->getAverageRating($movieData['imdbID']),
                'user_rating'   => $apiModel->getUserRating($_SESSION['user_id'], $movieData['imdbID']),
                'gemini_review' => null,
                'error'         => null,
            ];

            $this->view('movie/details', $data);

        } catch (Exception $e) {
            $data = [
                'error'   => "❌ Movie not found or unavailable.",
                'success' => null,
                'title'   => $title
            ];
            $this->view('movie/index', $data);
        }
    }
}
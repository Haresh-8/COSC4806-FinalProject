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

        // Store last searched title in session
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

            // Store last searched title and imdbID for back navigation
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

    public function rate()
    {
        if (!isset($_SESSION['auth'])) {
            header("Location: /login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /movie");
            exit;
        }

        $rating     = (int)($_POST['rating'] ?? 0);
        $imdb_id    = $_POST['imdb_id'] ?? '';
        $movieTitle = trim($_POST['movie_title'] ?? ($_SESSION['last_search_title'] ?? ''));

        if ($rating < 1 || $rating > 5) {
            $_SESSION['error'] = "Please provide a valid rating between 1 and 5 stars.";
            $_SESSION['last_search_title'] = $movieTitle;
            header("Location: /movie");
            exit;
        }

        if (empty($imdb_id)) {
            $_SESSION['error'] = "Invalid movie ID.";
            $_SESSION['last_search_title'] = $movieTitle;
            header("Location: /movie");
            exit;
        }

        $apiModel = $this->model('Api');
        $apiModel->saveRating($_SESSION['user_id'], $imdb_id, $rating);

        // Store success + last searched movie details for back navigation
        $_SESSION['success'] = "Your rating has been saved.";
        $_SESSION['last_search_title'] = $movieTitle;
        $_SESSION['last_searched_movie_imdbID'] = $imdb_id;

        // Redirect back to details page instead of search
        header("Location: /movie/details/" . urlencode($imdb_id));
        exit;
    }

    public function review($imdb_id = '')
    {
        if (!isset($_SESSION['auth'])) {
            header("Location: /login");
            exit;
        }

        if (empty($imdb_id)) {
            header("Location: /movie");
            exit;
        }

        $_SESSION['controller'] = 'movie';
        $apiModel = $this->model('Api');

        try {
            $movieData     = $apiModel->searchMovie($imdb_id);
            $avg_rating    = $apiModel->getAverageRating($imdb_id);
            $user_rating   = $apiModel->getUserRating($_SESSION['user_id'], $imdb_id);
            $gemini_review = $apiModel->generateGeminiReview($movieData['Title'] ?? $imdb_id);

            //  Update session so back button returns to details page
            $_SESSION['last_searched_movie_imdbID'] = $imdb_id;
            $_SESSION['last_search_title'] = $movieData['Title'] ?? '';

            $data = [
                'movie'         => $movieData,
                'avg_rating'    => $avg_rating,
                'user_rating'   => $user_rating,
                'gemini_review' => $gemini_review,
            ];

            $this->view('movie/review', $data);
        } catch (Exception $e) {
            $_SESSION['error'] = "❌ Review unavailable.";
            header("Location: /movie?title=" . urlencode($_SESSION['last_search_title'] ?? ''));
            exit;
        }
    }
}

    <?php require_once 'app/views/templates/header.php'; ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa, #e1f5fe);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .welcome-box {
            max-width: 850px;
            margin: auto;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 45px;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }
        .welcome-box h1 {
            color: #007bff;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .movie-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .movie-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }
        .movie-poster {
            max-width: 100px;
            height: auto;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(10px);}
            to {opacity: 1; transform: translateY(0);}
        }
    </style>

    <div class="container my-5">
        <div class="welcome-box">

            <h1>
                <?php if (!empty($_SESSION['username'])): ?>
                    Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!
                <?php else: ?>
                    Welcome, Guest!
                <?php endif; ?>
            </h1>

            <p class="lead">Search for movies and get AI-powered reviews instantly.</p>

            <a href="/movie" class="btn btn-primary mb-4">
                <i class="bi bi-film"></i> Search Movies Now
            </a>

            <hr class="my-4">

            <h4 class="text-center mb-3">Trending Movies</h4>
            <div class="row">
                <?php if (!empty($data['topMovies'])): ?>
                    <?php foreach ($data['topMovies'] as $m): ?>
                        <div class="col-md-4 mb-3">
                            <div class="movie-card">
                                <img src="<?= htmlspecialchars($m['poster'] ?? ''); ?>" alt="Poster" class="movie-poster">
                                <h6><?= htmlspecialchars($m['title'] ?? 'Unknown Title'); ?> (<?= htmlspecialchars($m['year'] ?? 'N/A'); ?>)</h6>
                                <small>Rating: <?= htmlspecialchars(round($m['avg_rating'] ?? 0, 2)); ?>/5</small><br>
                                <a href="/movie/review/<?= htmlspecialchars($m['imdb_id'] ?? ''); ?>" class="btn btn-sm btn-success mt-2">
                                    <i class="bi bi-chat-left-text"></i> Get AI Review
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No trending movies available yet. Start searching!</p>
                <?php endif; ?>
            </div>

            <?php if (!empty($data['gemini_error'])): ?>
                <div class="alert alert-danger mt-4">
                    <?= htmlspecialchars($data['gemini_error']); ?>
                </div>
            <?php elseif (!empty($data['gemini_response'])): ?>
                <div class="alert alert-info mt-4 text-start">
                    <strong>Gemini AI Suggestion:</strong>
                    <p><?= nl2br(htmlspecialchars($data['gemini_response'])); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php require_once 'app/views/templates/footer.php'; ?>


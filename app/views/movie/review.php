<?php require_once __DIR__ . '/../templates/header.php'; ?>

<style>
    .star {
        font-size: 1.5rem;
        color: gold;
        margin-right: 2px;
    }
    .star.empty {
        color: lightgray;
    }
</style>

<div class="container my-5">

<!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/home"><i class="bi bi-house"></i> Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="/movie"><i class="bi bi-search"></i> Search Movies</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <?= htmlspecialchars($data['movie']['Title'] ?? 'Movie Review'); ?>
            </li>
        </ol>
    </nav>

    <!-- Card for Movie Review -->
    <div class="card shadow-sm">
        <div class="card-body text-center">

            <h2 class="card-title text-primary">
                <?= htmlspecialchars($data['movie']['Title'] ?? 'Unknown Movie'); ?> 
                (<?= htmlspecialchars($data['movie']['Year'] ?? ''); ?>)
            </h2>

            <img src="<?= htmlspecialchars($data['movie']['Poster'] ?? ''); ?>" 
                 alt="Poster" class="img-fluid my-3" style="max-height:300px;">

            <p><strong>IMDb Rating:</strong> 
                <?= htmlspecialchars($data['movie']['imdbRating'] ?? 'N/A'); ?>/10
            </p>

            <p><strong>Average User Rating:</strong>
                <?php
                $avg_rating = $data['avg_rating'] ?? 0;
                $fullStars = floor($avg_rating);
                $emptyStars = 5 - $fullStars;

                for ($i = 0; $i < $fullStars; $i++) {
                    echo '<span class="star">&#9733;</span>'; // Full star
                }
                for ($i = 0; $i < $emptyStars; $i++) {
                    echo '<span class="star empty">&#9734;</span>'; // Empty star
                }
                ?>
                (<?= number_format($avg_rating, 1); ?>/5)
            </p>

            <p><strong>Your Rating:</strong>
                <?php if (!empty($data['user_rating'])): ?>
                    <?php
                    $user_rating = (int)$data['user_rating'];
                    for ($i = 0; $i < $user_rating; $i++) {
                        echo '<span class="star">&#9733;</span>';
                    }
                    for ($i = $user_rating; $i < 5; $i++) {
                        echo '<span class="star empty">&#9734;</span>';
                    }
                    ?>
                    (<?= $data['user_rating']; ?>/5)
                <?php else: ?>
                    <span class="text-muted">Not rated yet</span>
                <?php endif; ?>
            </p>

            <hr class="my-4">

            <!-- Gemini AI Review Section -->
            <h4 class="text-secondary">Gemini AI Review:</h4>
            <div class="alert alert-<?= 
                (isset($data['gemini_review']) && str_starts_with($data['gemini_review'], '⚠️')) ? 'warning' : 'info'; ?> text-start">
                <?= nl2br(htmlspecialchars($data['gemini_review'] ?? 'No review available.')); ?>
            </div>

            <?php
                // Build back URL to last movie details page if available
                $backUrl = '/movie';
                if (!empty($_SESSION['last_searched_movie_imdbID'])) {
                    $backUrl = '/movie/details/' . urlencode($_SESSION['last_searched_movie_imdbID']);
                }
            ?>

            <!-- Buttons -->
            <div class="mt-4 d-flex justify-content-center gap-2">
                <!-- Back button -->
                <a href="<?= $backUrl ?>" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>

                <!-- Back to Home -->
                <a href="/home" class="btn btn-outline-success">
                    <i class="bi bi-house-door"></i> Home
                </a>

                <!-- Refresh AI Review -->
                <a href="/movie/review/<?= htmlspecialchars($data['movie']['imdbID'] ?? ''); ?>" 
                   class="btn btn-info">
                    <i class="bi bi-robot"></i> Refresh AI Review
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>

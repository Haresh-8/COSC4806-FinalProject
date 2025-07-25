<?php require_once 'app/views/templates/header.php'; ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .star-rating {
        display: inline-flex;
        direction: rtl; /* reverse to make hover easier */
        font-size: 2.2rem;
        cursor: pointer;
    }
    .star-rating input {
        display: none;
    }
    .star-rating label {
        color: lightgray;
        transition: color 0.2s ease;
        padding: 0 2px;
    }
    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label {
        color: #FFD700; /* gold */
    }
</style>

<div class="container my-5" style="max-width: 700px;">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/home"><i class="bi bi-house"></i> Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="/movie"><i class="bi bi-search"></i> Search Movies</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <?= htmlspecialchars($data['movie']['Title'] ?? 'Movie Details'); ?>
            </li>
        </ol>
    </nav>

    <h2><?= htmlspecialchars($data['movie']['Title'] ?? 'Movie Details'); ?></h2>

    <!-- Show Success Message if Rating Submitted -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-check-circle"></i> <?= htmlspecialchars($_SESSION['success']); ?>
            </div>
            <a href="/movie" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-arrow-left"></i> Back to Search
            </a>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <img src="<?= htmlspecialchars($data['movie']['Poster'] ?? ''); ?>" 
                 class="img-fluid rounded shadow-sm" alt="Poster">
        </div>
        <div class="col-md-8">
            <p><strong>Year:</strong> <?= htmlspecialchars($data['movie']['Year'] ?? 'N/A'); ?></p>
            <p><strong>Genre:</strong> <?= htmlspecialchars($data['movie']['Genre'] ?? 'N/A'); ?></p>
            <p><strong>Plot:</strong> <?= htmlspecialchars($data['movie']['Plot'] ?? 'N/A'); ?></p>

            <!-- Average Rating -->
            <p><strong>Average User Rating:</strong>
                <?php
                $avg_rating = $data['avg_rating'] ?? 0;
                if (is_numeric($avg_rating) && $avg_rating > 0) {
                    $fullStars = floor($avg_rating);
                    $emptyStars = 5 - $fullStars;
                    for ($i = 0; $i < $fullStars; $i++) {
                        echo '<i class="bi bi-star-fill" style="color:gold;"></i>';
                    }
                    for ($i = 0; $i < $emptyStars; $i++) {
                        echo '<i class="bi bi-star" style="color:lightgray;"></i>';
                    }
                    echo " (" . round($avg_rating, 1) . "/5)";
                } else {
                    echo '<span>No ratings yet</span>';
                }
                ?>
            </p>

            <!-- User Rating Form -->
            <form method="post" action="/movie/rate" class="mt-3">
                <input type="hidden" name="imdb_id" 
                       value="<?= htmlspecialchars($data['movie']['imdbID'] ?? ''); ?>" />
                <input type="hidden" name="movie_title" 
                       value="<?= htmlspecialchars($data['movie']['Title'] ?? ''); ?>" />

                <div class="star-rating">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" id="star<?= $i ?>" 
                               name="rating" value="<?= $i ?>"
                               <?= (isset($data['user_rating']) && (int)$data['user_rating'] === $i) ? 'checked' : ''; ?>>
                        <label for="star<?= $i ?>">
                            <i class="bi bi-star-fill"></i>
                        </label>
                    <?php endfor; ?>
                </div>

                <br>
                <button type="submit" class="btn btn-success mt-3">
                    <i class="bi bi-star-fill"></i> Submit Rating
                </button>
            </form>

            <!-- Buttons Section -->
            <div class="mt-4 d-flex gap-2">
                <a href="/movie" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Back to Search
                </a>

                <?php if (!empty($data['movie']['imdbID'])): ?>
                    <a href="https://www.imdb.com/title/<?= htmlspecialchars($data['movie']['imdbID']); ?>/" 
                       class="btn btn-info" target="_blank" rel="noopener">
                        <i class="bi bi-info-circle"></i> More Info
                    </a>

                    <a href="/movie/review/<?= htmlspecialchars($data['movie']['imdbID']); ?>" 
                       class="btn btn-warning">
                        <i class="bi bi-robot"></i> AI Review
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'app/views/templates/footer.php'; ?>

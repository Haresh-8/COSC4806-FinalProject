<?php require_once 'app/views/templates/header.php'; ?>

<style>
    body {
        background: url('/public/images/cinema-bg.jpg') no-repeat center center/cover;
        min-height: 100vh;
    }

    .welcome-card {
        background: rgba(0, 0, 0, 0.7);
        color: #fff;
        border-radius: 15px;
        max-width: 500px;
        margin: 80px auto;
        padding: 40px 30px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
    }

    .welcome-card h1 {
        font-size: 2rem;
        font-weight: bold;
        color: #ffc107;
    }

    .welcome-card p.lead {
        font-size: 1.1rem;
        color: #ddd;
    }

    .btn-custom {
        width: 100%;
        padding: 12px;
        font-size: 1.1rem;
        border-radius: 30px;
    }

    .divider {
        margin: 15px 0;
        color: #aaa;
        font-size: 0.9rem;
    }

    .create-link a {
        color: #ffc107;
        font-weight: bold;
        text-decoration: none;
    }

    .create-link a:hover {
        text-decoration: underline;
        color: #ffdd57;
    }
</style>

<div class="welcome-card text-center">
    <img src="app/views/welcome/Movie Review logo.png" alt="Movie Icon" class="mb-3" style="width:150px;">

    <h1>Welcome to Movies Review App</h1>
    <p class="lead mt-2">Search movies, rate them, and get AI-powered reviews instantly!</p>

    <a href="/login" class="btn btn-primary btn-custom">
        <i class="bi bi-box-arrow-in-right"></i> Login
    </a>

    <div class="divider">OR</div>

    <a href="/login/guest" class="btn btn-success btn-custom">
        👤 Continue as Guest
    </a>

    <div class="create-link mt-3">
        ✨ <a href="/create">Create an account to rate movies</a>
    </div>
</div>

<?php require_once 'app/views/templates/footer.php'; ?>

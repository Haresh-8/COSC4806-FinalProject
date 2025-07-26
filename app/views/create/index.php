<?php require_once 'app/views/templates/header.php'; ?>

<style>
    body {
        background: url(app/views/welcome/Movie Review logo.png) no-repeat center center/cover;
        min-height: 100vh;
    }

    .create-card {
        background: rgba(0, 0, 0, 0.78);
        color: #fff;
        border-radius: 15px;
        max-width: 420px;
        margin: 80px auto;
        padding: 40px 30px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
        text-align: center;
    }

    .create-card h2 {
        color: #ffc107;
        font-weight: bold;
        font-size: 1.8rem;
    }

    .form-label {
        color: #ddd;
        font-weight: bold;
    }

    .btn-custom {
        width: 48%;
        padding: 10px;
        font-size: 1.1rem;
        border-radius: 30px;
    }

    .btn-success {
        background-color: #28a745;
        border: none;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .btn-outline-secondary {
        border-color: #ccc;
        color: #ccc;
    }

    .btn-outline-secondary:hover {
        background-color: #444;
        border-color: #888;
        color: #fff;
    }

    .login-link a {
        color: #ffc107;
        text-decoration: none;
        font-weight: bold;
    }

    .login-link a:hover {
        color: #ffdd57;
        text-decoration: underline;
    }
</style>

<div class="create-card">
    <img src="app/views/welcome/Movie Review logo.png" alt="Movie Icon" class="mb-3" style="width:150px;">

    <h2>✨ Create Account to Movie Review App</h2>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger mt-3">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="/create/store" class="mt-3 text-start">
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" name="username" id="username" required class="form-control" minlength="3" />
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" name="password" id="password" required class="form-control" minlength="6" />
        </div>
        <div class="mb-3">
            <label for="password2" class="form-label">Confirm Password:</label>
            <input type="password" name="password2" id="password2" required class="form-control" minlength="6" />
        </div>

        <div class="d-flex justify-content-between">
            <a href="/login" class="btn btn-outline-secondary btn-custom">
                ⬅ Back
            </a>
            <button type="submit" class="btn btn-success btn-custom">
                 Create
            </button>
        </div>
    </form>

    <div class="login-link mt-3">
        Already have an account? <a href="/login">Login here</a>
    </div>
</div>

<?php require_once 'app/views/templates/footer.php'; ?>

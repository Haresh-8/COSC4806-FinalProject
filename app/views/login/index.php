	<?php require_once 'app/views/templates/header.php'; ?>

	<style>
			body {
					background: url(app/views/welcome/Movie Review logo.png) no-repeat center center/cover;
					min-height: 100vh;
			}

			.login-card {
					background: rgba(0, 0, 0, 0.75);
					color: #fff;
					border-radius: 15px;
					max-width: 400px;
					margin: 80px auto;
					padding: 40px 30px;
					box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
			}

			.login-card h2 {
					color: #ffc107;
					font-weight: bold;
					font-size: 1.8rem;
			}

			.form-label {
					color: #ddd;
					font-weight: bold;
			}

			.btn-custom {
					width: 100%;
					padding: 10px;
					font-size: 1.1rem;
					border-radius: 30px;
			}

			.back-link {
					display: inline-block;
					margin-top: 10px;
					color: #ffc107;
					text-decoration: none;
					font-weight: bold;
			}

			.back-link:hover {
					text-decoration: underline;
					color: #ffdd57;
			}

			.create-link a {
					color: #ffc107;
					text-decoration: none;
					font-weight: bold;
			}

			.create-link a:hover {
					color: #ffdd57;
					text-decoration: underline;
			}
	</style>

	<div class="login-card text-center">
			<img src="app/views/welcome/Movie Review logo.png" alt="Movie Icon" class="mb-3" style="width:150px;">

			<h2>🔐 Login to Movie Review App</h2>

			<?php if (!empty($_SESSION['error'])): ?>
					<div class="alert alert-danger mt-3">
							<?= $_SESSION['error']; unset($_SESSION['error']); ?>
					</div>
			<?php endif; ?>

			<form method="post" action="/login/auth" class="mt-4 text-start">
					<div class="mb-3">
							<label for="username" class="form-label">Username:</label>
							<input type="text" name="username" id="username" required class="form-control" minlength="3" />
					</div>
					<div class="mb-3">
							<label for="password" class="form-label">Password:</label>
							<input type="password" name="password" id="password" required class="form-control" minlength="6" />
					</div>
					<button type="submit" class="btn btn-primary btn-custom">
							<i class="bi bi-box-arrow-in-right"></i> Login
					</button>
			</form>

			<a href="/" class="back-link">⬅ Back to Welcome</a>

			<div class="create-link mt-3">
					✨ <a href="/create">Create an account to rate movies</a>
			</div>
	</div>

	<?php require_once 'app/views/templates/footer.php'; ?>

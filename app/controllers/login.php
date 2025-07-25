<?php
class Login extends Controller {

		public function index() {
				// Show login form
				$this->view('login/index');
		}

		public function auth() {
				if ($_SERVER['REQUEST_METHOD'] === 'POST') {
						$username = trim($_POST['username'] ?? '');
						$password = trim($_POST['password'] ?? '');

						if (strlen($username) < 3 || strlen($password) < 6) {
								$_SESSION['error'] = "Username must be at least 3 chars and password 6 chars.";
								header("Location: /login");
								exit;
						}

						$userModel = $this->model('User');
						$userModel->authenticate($username, $password);
				}
		}
	public function guest() {
			// Create a temporary guest session
			$_SESSION['auth'] = true;
			$_SESSION['user_id'] = null; 
			$_SESSION['username'] = "Guest";

			// Redirect to home page
			header("Location: /");
			exit;
	}
}
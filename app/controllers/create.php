<?php
class Create extends Controller {

    public function index() {
        // Show create account form
        $this->view('create/index');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $password2 = trim($_POST['password2'] ?? '');

            if (strlen($username) < 3 || strlen($password) < 6) {
                $_SESSION['error'] = "Username must be at least 3 chars and password 6 chars.";
                header("Location: /create");
                exit;
            }

            if ($password !== $password2) {
                $_SESSION['error'] = "Passwords do not match.";
                header("Location: /create");
                exit;
            }

            $userModel = $this->model('User');
            $userModel->register($username, $password);
        }
    }

}

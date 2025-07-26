<?php

require_once __DIR__ . '/../database.php';


class User {

    public function authenticate($username, $password) {
        $db = db_connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $_SESSION['error'] = "User not found.";
            header("Location: /login");
            exit;
        }

        // Lockout check
        if (isset($_SESSION['lastFailed'], $_SESSION['failedAuth']) &&
            time() - $_SESSION['lastFailed'] < 60 && $_SESSION['failedAuth'] >= 3) {
            $_SESSION['error'] = "Too many failed attempts. Please try after 60 seconds.";
            header("Location: /login");
            exit;
        }

        if (password_verify($password, $user['password'])) {
            $_SESSION['auth'] = 1;
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'] ?? 'user';

            $_SESSION['failedAuth'] = 0;
            unset($_SESSION['lastFailed']);

            $this->logAttempt($username, "good");
            header("Location: /home");
            exit;
        } else {
            $_SESSION['failedAuth'] = ($_SESSION['failedAuth'] ?? 0) + 1;
            $_SESSION['lastFailed'] = time();
            $_SESSION['error'] = "Invalid login.";
            $this->logAttempt($username, "bad");
            header("Location: /login");
            exit;
        }
    }

    public function register($username, $password) {
        $db = db_connect();

        // Check if username exists
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $_SESSION['error'] = "Username already exists.";
            header("Location: /create");
            exit;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, 'user')");
        $stmt->execute(['username' => $username, 'password' => $hash]);

        $_SESSION['success'] = "Account created. Please login.";
        header("Location: /login");
        exit;
    }

    public function logAttempt($username, $attempt) {
        $db = db_connect();
        $stmt = $db->prepare("INSERT INTO login_log (username, attempt) VALUES (:username, :attempt)");
        $stmt->execute(['username' => $username, 'attempt' => $attempt]);
    }
}

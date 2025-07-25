<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentController = strtolower($_SESSION['controller'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>COSC 4806 - Movie App</title>
    <link rel="icon" href="/favicon.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {background-color: #f8f9fa;}
        .navbar-brand {font-weight: bold; letter-spacing: 0.5px;}
        .nav-link.active {font-weight: bold; color: #ffc107 !important;}
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="/home">
            <i class="bi bi-film"></i> COSC 4806
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if (isset($_SESSION['auth'])): ?>
                <li class="nav-item">
                    <a class="nav-link<?= ($currentController === 'home') ? ' active' : '' ?>" href="/home">
                        <i class="bi bi-house-door"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?= ($currentController === 'movie') ? ' active' : '' ?>" href="/movie">
                        <i class="bi bi-search"></i> Search Movies
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if (!isset($_SESSION['auth'])): ?>
                    <li class="nav-item">
                        <a class="nav-link<?= ($currentController === 'login') ? ' active' : '' ?>" href="/login">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?= ($currentController === 'create') ? ' active' : '' ?>" href="/create">
                            <i class="bi bi-person-plus"></i> Create Account
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="/logout">
                            <i class="bi bi-box-arrow-right"></i> Logout (<?= htmlspecialchars($_SESSION['username']); ?>)
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

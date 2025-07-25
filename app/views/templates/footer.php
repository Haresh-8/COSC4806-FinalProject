<?php
// Determine current page/controller name
// Adjust this as per your app setup; example using session controller or PHP_SELF
$currentPage = $_SESSION['controller'] ?? basename($_SERVER['PHP_SELF'], '.php');

?>

<?php if (in_array($currentPage, ['welcome', 'login', 'create'])): ?>
    <!-- Customized footer for welcome, login, and create pages -->
    <footer class="bg-light text-center text-muted mt-5 p-3 border-top">
        <div class="container">
            <p class="mb-0">Welcome to Movie Search App! Please <a href="/login" class="text-decoration-none">login</a> or <a href="/create" class="text-decoration-none">create an account</a> to start rating movies.</p>
            <small>&copy; <?= date('Y') ?> Movie Search App - Powered by OMDB & Gemini AI</small>
        </div>
    </footer>

<?php else: ?>
    <!-- Standard footer for all other pages -->
    <footer class="bg-dark text-light text-center text-md-start mt-4 p-4">
        <div class="container">
            <div class="row">
                <!-- Left: App Info -->
                <div class="col-md-4 mb-3">
                    <h5 class="text-warning">Movie Search App</h5>
                    <p class="small mb-0">
                        Powered by <strong>OMDB</strong> & <strong>Gemini AI</strong><br>
                        &copy; <?= date('Y') ?> All rights reserved.
                    </p>
                </div>

                <!-- Middle: Page Directory -->
                <div class="col-md-4 mb-3">
                    <h6 class="text-warning">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="/home" class="text-light text-decoration-none">Home</a></li>
                        <li><a href="/movie" class="text-light text-decoration-none">Search Movies</a></li>
                        <li><a href="/contact" class="text-light text-decoration-none">Contact Us</a></li>
                        <li><a href="/logout" class="text-light text-decoration-none">Logout</a></li>
                    </ul>
                </div>

                <!-- Right: Contact Info -->
                <div class="col-md-4 mb-3">
                    <h6 class="text-warning">Contact</h6>
                    <p class="small mb-1">
                        📧 <a href="mailto:harepatel@algomau.ca" class="text-light text-decoration-none">
                            harepatel@algomau.ca
                        </a>
                    </p>
                    <p class="small mb-0">Made by Hareshkumar Patel</p>
                </div>
            </div>
        </div>
    </footer>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php require_once 'app/views/templates/header.php'; ?>

<div class="container my-5" style="max-width: 600px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="bi bi-house"></i> Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Search Movie</li>
        </ol>
    </nav>

    <h2 class="mb-4 text-primary">Search Movie</h2>

    <!-- Alerts -->
    <?php if (!empty($data['error'])): ?>
        <div id="alertBox" class="alert alert-danger">
            <?= htmlspecialchars($data['error']); ?>
        </div>
    <?php elseif (!empty($data['success'])): ?>
        <div id="alertBox" class="alert alert-success">
            <?= htmlspecialchars($data['success']); ?>
        </div>
    <?php endif; ?>

    <!-- Search Form -->
    <form method="post" action="/movie/search" class="card shadow-sm p-4">
        <div class="mb-3 d-flex align-items-center">
            <label for="title" class="form-label flex-shrink-0 me-2 mb-0 fw-bold fs-5">
                Movie Title
            </label>
            <input 
                type="text" 
                id="title" 
                name="title" 
                class="form-control me-2"
                placeholder="Enter movie title here..."
                value="<?= htmlspecialchars($data['title'] ?? ''); ?>" 
                required
                style="flex-grow: 1;"
            />
            <button type="button" class="btn btn-danger btn-sm" id="clearBtn" title="Clear Search">
                <i class="bi bi-x-lg"></i> Clear
            </button>
        </div>
        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-search"></i> Search
        </button>
    </form>

    <!-- Extra Actions -->
    <div class="mt-3 d-flex justify-content-between">
        <a href="/home" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Home
        </a>

        <?php if (!empty($_SESSION['last_search_title'])): ?>
            <a href="/movie/search?title=<?= urlencode($_SESSION['last_search_title']); ?>" 
               class="btn btn-outline-info">
                <i class="bi bi-clock-history"></i> View Last Searched: 
                <strong><?= htmlspecialchars($_SESSION['last_search_title']); ?></strong>
            </a>
        <?php endif; ?>
    </div>
</div>

<script>
    const titleInput = document.getElementById('title');
    const clearBtn = document.getElementById('clearBtn');

    function removeAlert() {
        const alertBox = document.getElementById('alertBox');
        if (alertBox) {
            alertBox.style.transition = "opacity 0.3s ease-out";
            alertBox.style.opacity = "0";
            setTimeout(() => alertBox.remove(), 300);
        }
    }

    // Clear button click
    clearBtn.addEventListener('click', function () {
        titleInput.value = '';
        titleInput.focus();
        removeAlert();
    });

    // Fade alert as soon as user types or changes input
    titleInput.addEventListener('input', removeAlert);
</script>

<?php require_once 'app/views/templates/footer.php'; ?>

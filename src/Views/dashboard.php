<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweetwater Comments Dashboard</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header class="app-header">
        <div class="brand">
            <div class="logo">SW</div>
            <div class="title-group">
                <h1>Sweetwater Comments</h1>
                <p class="subtitle">Customer comments and expected ship dates</p>
            </div>
        </div>
        <div class="header-actions">
            <?php $appEnv = $_ENV['APP_ENV'] ?? getenv('APP_ENV') ?: null; ?>
            <?php if (!empty($appEnv)): ?>
                <span class="env-badge">ENV: <?= htmlspecialchars($appEnv) ?></span>
            <?php endif; ?>
            <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">Toggle theme</button>
        </div>
    </header>

    <main class="container">
        <section class="cards">
            <div class="card">
                <div class="card-title">Total Comments</div>
                <div class="card-metric"><?= (int)($commentStats['total'] ?? 0) ?></div>
                <div class="card-foot">All categories combined</div>
            </div>
            <div class="card">
                <div class="card-title">With Ship Date</div>
                <div class="card-metric success-text"><?= (int)($shipDateStats['with_ship_date'] ?? 0) ?></div>
                <div class="card-foot">Extracted from comments</div>
            </div>
            <div class="card">
                <div class="card-title">Without Ship Date</div>
                <div class="card-metric warn-text"><?= (int)($shipDateStats['without_ship_date'] ?? 0) ?></div>
                <div class="card-foot">Needs follow-up</div>
            </div>
            <div class="card">
                <div class="card-title">Valid Dates</div>
                <div class="card-metric"><?= (int)($shipDateStats['valid_dates'] ?? 0) ?></div>
                <div class="card-foot">YYYY-MM-DD</div>
            </div>
        </section>

        <section class="grid">
            <div class="panel">
                <h2>Candy</h2>
                <div class="comment-list">
                    <ul>
                        <?php foreach ($comments['candy'] as $comment): ?>
                            <li><?= htmlspecialchars($comment) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="panel">
                <h2>Call me / Don’t call me</h2>
                <div class="comment-list">
                    <ul>
                        <?php foreach ($comments['call_me'] as $comment): ?>
                            <li><?= htmlspecialchars($comment) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="panel">
                <h2>Who referred me</h2>
                <div class="comment-list">
                    <ul>
                        <?php foreach ($comments['referred'] as $comment): ?>
                            <li><?= htmlspecialchars($comment) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="panel">
                <h2>Signature requirements</h2>
                <div class="comment-list">
                    <ul>
                        <?php foreach ($comments['signature'] as $comment): ?>
                            <li><?= htmlspecialchars($comment) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="panel span-2">
                <h2>Miscellaneous</h2>
                <div class="comment-list">
                    <ul>
                        <?php foreach ($comments['misc'] as $comment): ?>
                            <li><?= htmlspecialchars($comment) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </section>
    </main>
</body>
<script>
    (function() {
        const STORAGE_KEY = 'sw-theme';
        const root = document.documentElement;
        const toggle = document.getElementById('themeToggle');

        function applyTheme(theme) {
            if (theme) {
                root.setAttribute('data-theme', theme);
            } else {
                root.removeAttribute('data-theme');
            }
        }

        function currentTheme() {
            return root.getAttribute('data-theme');
        }

        // Initialize from localStorage
        try {
            const saved = localStorage.getItem(STORAGE_KEY);
            if (saved === 'light' || saved === 'dark') {
                applyTheme(saved);
            }
        } catch (_) {}

        toggle?.addEventListener('click', function() {
            const cur = currentTheme();
            const next = cur === 'light' ? 'dark' : cur === 'dark' ? '' : 'light';
            applyTheme(next);
            try {
                if (next) localStorage.setItem(STORAGE_KEY, next);
                else localStorage.removeItem(STORAGE_KEY);
            } catch (_) {}
        });
    })();
    </script>
</html>

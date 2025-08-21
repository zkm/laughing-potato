<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweetwater Comments Dashboard</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .wrapper {
            width: 95%;
            margin: 0 auto;
        }
        .category-section {
            margin-bottom: 2rem;
        }
        .comment-list {
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
    <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-4 mb-4">Sweetwater Comments Dashboard</h1>
                    
                    <div class="category-section">
                        <h2>Comments about candy</h2>
                        <div class="comment-list">
                            <ul class="list-group">
                                <?php foreach ($comments['candy'] as $comment): ?>
                                    <li class="list-group-item"><?= htmlspecialchars($comment) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="category-section">
                        <h2>Comments about call me / don't call me</h2>
                        <div class="comment-list">
                            <ul class="list-group">
                                <?php foreach ($comments['call_me'] as $comment): ?>
                                    <li class="list-group-item"><?= htmlspecialchars($comment) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="category-section">
                        <h2>Comments about who referred me</h2>
                        <div class="comment-list">
                            <ul class="list-group">
                                <?php foreach ($comments['referred'] as $comment): ?>
                                    <li class="list-group-item"><?= htmlspecialchars($comment) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="category-section">
                        <h2>Comments about signature requirements upon delivery</h2>
                        <div class="comment-list">
                            <ul class="list-group">
                                <?php foreach ($comments['signature'] as $comment): ?>
                                    <li class="list-group-item"><?= htmlspecialchars($comment) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="category-section">
                        <h2>Miscellaneous comments</h2>
                        <div class="comment-list">
                            <ul class="list-group">
                                <?php foreach ($comments['misc'] as $comment): ?>
                                    <li class="list-group-item"><?= htmlspecialchars($comment) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

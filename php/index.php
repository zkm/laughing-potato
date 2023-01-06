<?php

// Connect to the database
require_once "config.php";

// Include the comments and shipdate functions
require_once 'comments.php';
require_once "shipdate.php";

// Use the getComments function
$comments = getComments();

// Use the updateShipDate function
updateShipDate();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    .wrapper {
        width: 95%;
        margin: 0 auto;
    }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php 
                        // Display the comments for each category
                        echo '<h2>Comments about candy</h2>';
                        echo '<ul>';
                        foreach ($comments['candy'] as $comment) {
                            echo '<li>' . $comment . '</li>';
                        }
                        echo '</ul>';
                        echo '<h2>Comments about call me / don\'t call me</h2>';
                        echo '<ul>';
                        foreach ($comments['call me'] as $comment) {
                            echo '<li>' . $comment . '</li>';
                        }
                        echo '</ul>';
                        echo '<h2>Comments about who referred me</h2>';
                        echo '<ul>';
                        foreach ($comments['referred'] as $comment) {
                            echo '<li>' . $comment . '</li>';
                        }
                        echo '</ul>';
                        echo '<h2>Comments about signature requirements upon delivery</h2>';
                        echo '<ul>';
                        foreach ($comments['signature'] as $comment) {
                            echo '<li>' . $comment . '</li>';
                        }
                        echo '</ul>';
                        echo '<h2>Miscellaneous comments</h2>';
                        echo '<ul>';
                        foreach ($comments['misc'] as $comment) {
                            echo '<li>' . $comment . '</li>';
                        }
                        echo '</ul>';
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

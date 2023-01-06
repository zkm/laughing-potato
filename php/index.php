<?php

// Connect to the database
require_once "config.php";

// Include the comments and shipdate functions
require_once 'comments.php';
require_once "shipdate.php";

// Use the getComments function
$comments = getComments();

// Display the comments for each category
echo '<h2>Comments about candy</h2>';
foreach ($comments['candy'] as $comment) {
    echo $comment . '<br>';
}

echo '<h2>Comments about call me / don\'t call me</h2>';
foreach ($comments['call me'] as $comment) {
    echo $comment . '<br>';
}

echo '<h2>Comments about who referred me</h2>';
foreach ($comments['referred'] as $comment) {
    echo $comment . '<br>';
}

echo '<h2>Comments about signature requirements upon delivery</h2>';
foreach ($comments['signature'] as $comment) {
    echo $comment . '<br>';
}

echo '<h2>Miscellaneous comments</h2>';
foreach ($comments['misc'] as $comment) {
    echo $comment . '<br>';
}

// Use the updateShipDate function
updateShipDate();

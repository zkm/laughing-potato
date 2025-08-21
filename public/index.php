<?php

// Modern entry point for the application
require_once __DIR__ . '/../vendor/autoload.php';

// Force UTF-8 output
header('Content-Type: text/html; charset=UTF-8');

// Load environment variables (safe for environments without .env)
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

use Sweetwater\Services\CommentService;
use Sweetwater\Services\ShipDateService;

try {
    // Initialize services
    $commentService = new CommentService();
    $shipDateService = new ShipDateService();
    
    // Process ship dates (Task 2)
    $shipDateService->processShipDates();
    
    // Get categorized comments (Task 1)
    $comments = $commentService->categorizeComments();
    $commentStats = $commentService->getCommentStats();
    $shipDateStats = $shipDateService->getShipDateStats();
    
    // Include the view
    include __DIR__ . '/../src/Views/dashboard.php';
    
} catch (Exception $e) {
    http_response_code(500);
    echo "Application Error: " . $e->getMessage();
}

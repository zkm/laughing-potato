<?php
/**
 * PHPUnit Bootstrap File
 * Sets up test environment and database connection
 */

// Autoload Composer dependencies
require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables from .env if present
if (class_exists(\Dotenv\Dotenv::class)) {
	$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
	$dotenv->safeLoad();
}

// Ensure DB env defaults for tests
$_ENV['DB_HOST'] = $_ENV['DB_HOST'] ?? 'db';
$_ENV['DB_USER'] = $_ENV['DB_USER'] ?? 'sweetwater_user';
$_ENV['DB_PASS'] = $_ENV['DB_PASS'] ?? 'sweetwater_pass';
$_ENV['DB_NAME'] = $_ENV['DB_NAME'] ?? 'sweetwater_db';

// Legacy includes removed: the project now uses Composer autoload and classes under src/

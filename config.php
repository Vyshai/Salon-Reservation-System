<?php
// config.php - Session configuration for InfinityFree hosting
// Include this file at the top of every file that uses sessions

// Set custom session save path for InfinityFree
$session_path = __DIR__ . '/sessions';

// Create sessions directory if it doesn't exist
if (!is_dir($session_path)) {
    mkdir($session_path, 0700, true);
}

// Set the session save path
ini_set('session.save_path', $session_path);

// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
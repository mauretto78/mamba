<?php

require_once __DIR__.'/../vendor/autoload.php';

// Setup Application
$app = new App\Application('prod');

// Init the app
$app->start();

// Run the app in cached mode
$app['http_cache']->run();

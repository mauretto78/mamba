<?php

require_once __DIR__.'/../vendor/autoload.php';

// Setup Application
$app = new App\Application('dev');

// Init the app
$app->start();

// Run the app
$app->run();

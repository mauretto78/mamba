<?php

require_once __DIR__.'/../vendor/autoload.php';

// Setup Application
$app = new App\Application('dev');

// Init the app
$app->init();

// Run the app
$app->run();

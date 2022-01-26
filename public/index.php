<?php

// During development, uncomment to show  errors. 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);





  
// Autoload Classes 
spl_autoload_register(function($class_name){
    $file = __DIR__.'/../classes/'.$class_name . '.php';
    include $file;
});

// Start Session
session_start();

// Configure the root URL for the site. 
// Can be customized to point to a subfolder.
App::set('root', 'https://'.$_SERVER['HTTP_HOST']);

// Store some info about our app name 
// to be retrieved by the view
App::set('SiteName', 'MeowBase');

// Start Routing User Requests
Router::start();

?>

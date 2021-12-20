<?php

// During development, show all errors. 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

  
// Autoload Classes 
spl_autoload_register(function($class_name){
    include '../classes/'.$class_name . '.php';
});

// Start Session
session_start();

// Cnofigure a Subfolder on the URL to use for deployment
// comment this out if you want to use top level as root.
App::set('root', '/MeowBase');

// Store some info about our app name 
// to be retrieved by the view
App::set('SiteName', 'MeowBase');

// Start Routing User Requests
Router::start();

?>

<?php

// logout the user and then show the login form 
App::logout();
App::set("PageTitle", "Login");
include '../views/loginForm.php';

?>
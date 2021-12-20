<?php

// if the user is trying to login, 
// there should be some data coming in from the form
// if so it will be foundin the $_POST array 
// see also: https://www.php.net/manual/en/reserved.variables.post.php

if ( $_POST['Login'] ) {
  // fetch a user from the database whose  email matches the provided one
  if ( $user = User::fromColumn('Email', $_POST['Email'])){
    // check whether the user's password matched the submitted password. 
    if ( $user->checkPassword( $_POST['Password']) ){
      // set the user as logged in by adding their ID to the SESSION 
      // see also https://www.php.net/manual/en/reserved.variables.session.php
      $_SESSION['user_ID'] = $user->ID;
      // redirect to the home page after a successful login. 
      Router::redirect( App::root() );
    } 
    App::notice("Check your password.");
  } 
  // generate a notice that the login was not successful. 
  App::notice("Login Failed");
}

// load the login form view.
App::set("PageTitle", "Login");
include '../views/loginForm.php';

?>

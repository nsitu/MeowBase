<?php

// if the user is trying to register, 
// there should be some data coming in from the form
// if so it will be foundin the $_POST array 
// see also: https://www.php.net/manual/en/reserved.variables.post.php

if ($_POST['Register']){

  
 
  // instantiate a new user object for the registrant 
  // (i.e. the person who is attempting to register)
  $registrant = new User(); 
  // set the new user's name based on th provided form data. 
  $registrant->FullName = $_POST['FullName'];
  // set the new user's email address based on th provided form data. 
  $registrant->Email = $_POST['Email'];

  // The presence of PolicyApproved among our POST data indicates that it has been checked off. 
  // The absence of PolicyApproved would mean that it was not checked. 
  // Thus we can use the isset function to set an appropriate boolean (one or zero) 
  $registrant->PolicyApproved = ( isset($_POST['PolicyApproved']) ) ? 1 : 0;

  // set a default profile picture. 
  $registrant->ProfilePicture = '/images/rocky.jpg';
  // set a default Bio for a new user
  $registrant->Bio = 'Intrepid Cat';
  // encrypt the password 
  $registrant->setPassword($_POST['Password']);
  
  // add the new user to DB
  $status = $registrant->insert(); 

  // if the new user was successfully created, 
  // generate a notice and show the login form
  if ($registrant->ID){
    App::notice('Registration successful.');
    App::set("PageTitle", "Login");
    include '../views/loginForm.php';
  } 
  // if there's a duplicate entry show an error notice
  elseif (str_contains($status, 'Duplicate entry') ) {
    App::notice($_POST['Email']." is already registered.", "alert-danger");
  } 
  // if there is some other SQL error display it.
  else{
    App::notice($status, "alert-danger");
  }
}
 
// Show a registration form.
App::set("PageTitle", "Register");
 
include '../views/registrationForm.php';

?>
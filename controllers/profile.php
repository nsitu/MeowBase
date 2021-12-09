<?php

// This is the profile controller.

// if JSON data was submitted, import json data for current user
// save it to the database and report back to the front end
if ( $json = Request::json() ){
  // get the logged in user and import the submitted JSON data
  App::User()->json_import($json); 
  $status = App::User()->update();
  
  
  // attempt to update the user's record in the database
  if ( is_numeric( $status )  ){
    echo App::User()->json_export();
    // if the update succeeded, send the updated user's record back to the frontend.
   // 
  }
  elseif (str_contains($status, "SQLSTATE")){
    echo 'Sorry, that did not work.';
  }
  // no need to render anything else since we are finished sending JSON 
  exit;
}
else{

    
    // Show the user's profile view
    App::set("PageTitle", "Profile"); 
    include '../views/profile.php';
 

  
}



?>
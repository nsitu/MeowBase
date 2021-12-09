<?php
 

// if a comment is being deleted. 
if ( Request::method() == "DELETE" ){
  if( $ID = Request::ID() ){
    $paw = Paw::fromID($ID);
    if ($paw->User_ID == App::User()->ID){
      echo $paw->delete();
      exit;
    }
    else{
      echo 'Check your permissions.';
      exit;
    }
  }
  else{
    echo 'ID not found in '.Request::url();
    exit;
  }
}
// if a new paw was submitted, import json data for the paw.
elseif ( Request::method() == "POST"   ){
  // get the logged in user and import the submitted JSON data 
  if ($json = Request::json()){ 
    $paw = new Paw();
    $paw->json_import($json); 
    $paw->User_ID = App::User()->ID;
    $status = $paw->insert(); 
    if ( is_numeric( $status )  ){
      echo $paw->json_export();
    }
    elseif (str_contains($status, "SQLSTATE")){
      echo $status;
    }
    else{
      echo 'Something went wrong.';
    }
    exit;
  }
} 
 


?>
<?php
 
// The Cats controller!

if ( Request::method() == "GET" ){ 

  // if this request is for data only, then return JSON 
  // this happens when the front end is making the request. 
  if (Request::url_contains('json') ){
    $users = User::findMany(['limit' => 'LIMIT 25']);
    echo json_encode($users);
    exit;
  }
  // if the user landed on the cats URL then return a page view. 
  else{
    // if a particular cat ID is given, get the singular page view.
    if ($userID = Request::ID()){
      $user = User::fromID($userID);
      App::log('Loaded User '.$userID); 
      App::set("PageTitle", "Cats"); 
      include '../views/cat.php';
   }
   // if no particular cat ID is given, get the cat grid page view.
   else{
    App::set("PageTitle", "Cats"); 
    include '../views/cats.php';
   }
    
    exit;
  }

}

?>
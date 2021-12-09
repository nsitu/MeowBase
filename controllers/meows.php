<?php

// This is the meows controller.

// if a meow is being deleted. 
if ( Request::method() == "DELETE" ){
  if( $ID = Request::ID() ){
    $meow = Meow::fromID($ID);
    if ($meow->User_ID == App::User()->ID){
      echo $meow->delete();
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
// if a new meow was submitted, import json data for the meow.
elseif ( Request::method() == "POST" && $json = Request::json() ){
  // get the logged in user and import the submitted JSON data

  $meow = new Meow();
  $meow->json_import($json); 
  $meow->User_ID = App::User()->ID;
  $meow->PublishDateTime = date('Y-m-d H:i:s');
  $status = $meow->insert();
  
  if ( is_numeric( $status )  ){
    echo $meow->json_export();
  }
  elseif (str_contains($status, "SQLSTATE")){
    echo $status;
  }
  else{
    echo 'Something went wrong.';
  }
  exit;
}
elseif ( Request::method() == "GET" ){ 

  if ($author_ID = Request::ID() ){
    $meows = Meow::findMany([
      'limit' => 'LIMIT 25',
      'order' => 'ORDER BY `Meow`.`PublishDateTime` DESC',
      'where' => "WHERE `Meow`.`User_ID` = '$author_ID'"
    ]);
  }
  else{
    $meows = Meow::findMany([
      'limit' => 'LIMIT 25',
      'order' => 'ORDER BY `Meow`.`PublishDateTime` DESC'
    ]);
  } 

  echo json_encode($meows);
  exit;

}
 


?>
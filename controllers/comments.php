<?php
 
// if a comment is being deleted, delete it. (DELETE is one of many request methods)
// See also: https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods
if ( Request::method() == "DELETE" ){
  if( $ID = Request::ID() ){
    $comment = Comment::fromID($ID);
    // users may only delete comments that they themselves created.
    if ($comment->User_ID == App::User()->ID){
      echo $comment->delete();
      exit;
    }
    else{
      // thwart attempt to delete other people's comments. 
      echo 'Check your permissions.';
      exit;
    }
  }
  else{
    echo 'ID not found in '.Request::url();
    exit;
  }
}
// if a new comment was submitted, import json data for the comment.
elseif ( Request::method() == "POST"   ){
  // get the logged in user and import the submitted JSON data 
  if ($json = Request::json()){ 
    $comment = new Comment();
    $comment->json_import($json); 
    $comment->User_ID = App::User()->ID;
    $comment->PublishDateTime = date('Y-m-d H:i:s');
    $status = $comment->insert(); 
    if ( is_numeric( $status )  ){
      echo $comment->json_export();
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
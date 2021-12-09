<?php  

// User class for holding our wonderful users!

// Note that it extends the "Model" class
// Therefore all the Model methods are available to Users as well. 
// e.g. User->save();
// see also: https://www.php.net/manual/en/language.oop5.inheritance.php

class User extends Model{


  // A "protected" property cannot be accessed directly
  // but only via an object's own methods()
  protected $Password = ''; 

  function __construct(){ 
    $this->link = "/user/".$this->ID; 
  } 
  
  // given a new password, store a hashed version in $this->Password
  function setPassword($Password){
    $this->Password = password_hash($Password, PASSWORD_DEFAULT);
  }

  // check whether this user's password matchees a given password 
  function checkPassword($Password){
    return password_verify($Password, $this->Password);
  }
 
  
}

?>
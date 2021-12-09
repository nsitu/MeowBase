<?php  

// Meow class

// Note that it extends the "Model" class
// Therefore all the Model methods are available to Meows as well. 
// e.g. Meow->save();
// see also: https://www.php.net/manual/en/language.oop5.inheritance.php

class Meow extends Model{
   
  function __construct(){
    $this->niceDate = $this->niceDate();   
  }
  
  
}

?>
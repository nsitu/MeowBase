<?php
 
 
// Often we want to show a notice to our users. 
// This class allows us to define the markup and appearance of a notice.

class Notice{

  function __construct($text, $cssClass = "alert-success"){
    $this->text = $text; 
    $this->cssClass = $cssClass;
  }
   
  // The appearance of a notice is simple:
  // It's really just some text inside a div   
  function render(){
    return <<<HTML
      <div class="alert $this->cssClass" role="alert">$this->text</div>
    HTML;
  }

}

?>
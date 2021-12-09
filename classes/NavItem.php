<?php

class NavItem{

  // A nav item has a name, url, and icon
  function __construct($name, $href ='/', $icon = ''){
    $this->name = $name;
    $this->href = $href;     
    $this->icon = $icon;  
    $this->style = ($this->isActive()) ? "active" : "";
  }

  function render($mode="normal"){
    if ($mode == "dropdown"){
      return <<<HTML
        <li>
          <a class="dropdown-item $this->style" href="$this->href">
            $this->icon<span class="$this->type">$this->name</span>
          </a>
        </li>
      HTML;
    }
    else{
      return <<<HTML
        <li class="nav-item">
          <a class="nav-link $this->style" href="$this->href">
            $this->icon<span class="$this->type">$this->name</span></a>
        </li>
      HTML;
    }
  }
 

  // If the requested url matches this link, mark it as active. 
  function isActive(){
    if ( Request::url_contains($this->name) ) return true;
    return false;
  }

}

?>
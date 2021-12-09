<?php

// This class builds a dropdown menu 
// It creates HTML wrapper markup to be populated with NavItems 
// is based on a standard responsive Bootstrap navbar dropdown
// https://getbootstrap.com/docs/5.1/components/navbar/

class NavDropdown{
  function __construct($name, $icon = ''){
    $this->name = $name;
    $this->icon = $icon;
    $this->items = []; // an array to hold NavItems
  } 
  function addItem($name){
    // each item in the dropdown is a new instance of the NavDropdownItem class.
    $item = new NavItem($name); 
    $this->items[] = $item->render("dropdown");  // 
  }
  function render(){
    // implode() function collapses an array of items into a single string
    // https://www.php.net/manual/en/function.implode.php
    $items = implode($this->items); 
    return <<<HTML
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="/" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          $this->icon $this->name
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
          $items
        </ul>
      </li>
    HTML;
  }

}
?>
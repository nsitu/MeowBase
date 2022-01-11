<?php  
 

class Router{
  
  // start: this function is called on index.php to get the ball rolling
  public static function start(){
    App::log('Router Starting.'); 
    include __DIR__ . "/../controllers/".self::getRoute();
    
  }

  // getRoute tries to find an appropriate controller
  // based on the user's request(e.g. URL, form data, etc.)
  // see also:  ../classes/Request.php
  static function getRoute(){
    
    // setup route
    if (Request::url_contains('setup')) return "setup.php";  

    // Here we check if the user is logged in. 
    // If they are, a bunch of new  routes are possible.
    if ( App::User() ){
      if ( array_key_exists('file', $_FILES ) return "upload.php";  
      if (Request::url_starts_with('paws')) return "paws.php";
      if (Request::url_starts_with('cats')) return "cats.php";
      if (Request::url_starts_with('comments')) return "comments.php";
      if (Request::url_starts_with('meows')) return "meows.php"; 
      if (Request::url_starts_with('logout')) return "logout.php"; 
      if (Request::url_starts_with('profile')) return "profile.php"; 
      return "dashboard.php";
    }
    
    // If we end up here, there is no user logged in yet
    // so we only respond to the basic onboarding options.
    else{
      if (Request::url_starts_with('login'))  return "login.php"; 
      if (Request::url_starts_with('register'))  return "register.php";  
      return "login.php"; // useful default route. 
    }
  }

  // redirect the user to another url 
  public static function redirect($url){
    // in PHP a redirect is done by modifying the header location:
    header("Location: ".$url);
    debug_print_backtrace();
    exit;
  }
 
}

?>

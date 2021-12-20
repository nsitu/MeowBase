<?php
 
// Request class for checking what the user asked for. 

class Request{
 
  // get the url 
  static function url(){
    $url =  str_replace( App::root(), '',  $_SERVER['REQUEST_URI']);
    return trim($url, '/');
  }

  static function method(){
    return $_SERVER['REQUEST_METHOD'];
  }

  // check if the url contains a given string.
  static function url_contains($string){
    $url = urldecode(self::url());
    if (str_contains($url, $string)) return true;
    return false;
  }

  // check if the url starts with a given string 
  static function url_starts_with($string){
    $url = urldecode(self::url());
    if (str_starts_with($url, $string)) return true;
    return false;
  }

  // return the parts of the url as an array
  static function url_parts(){ 
    return explode('/', self::url());
  }

  static function ID(){
    foreach (self::url_parts() as $url_part){
      if (is_numeric($url_part) ){
        return $url_part;
      }
    }
    return false;
  }

  // check whether the user submitted JSON data
  // if they did, return the raw JSON
  static function json(){
    if ( ! $_SERVER["CONTENT_TYPE"] == "application/json") return false;
    return file_get_contents("php://input");
  }




}
?>

<?php 
 
class App{

  public static $pdo = null ;      // a place to store the database connection
  public static $data = null ;     // a place to store app-wide variables. 
  public static $notices = null;  // a place to store user notices
  public static $log = null;  // a place to store user notices
  public static $User = null;      // a place to store the logged in user here.

 
 public static function root(){
   return self::get('root'); 
 }
 
  // database connection makes use of environment variables (in Replit, use secrets)
  public static function pdo(){
    if ( self::$pdo ) return self::$pdo ; 
    $username = getenv('USERNAME');   
    $password = getenv('PASSWORD');   
    $host = getenv('HOST');           
    $dbname = getenv('DBNAME');
 
    if (!$username || !$password || !$host || !$dbname){
      App::notice('Please double-check your environment variables.');
      App::set("PageTitle", "Login");
      include '../views/loginForm.php';
      exit;
    } 
    $options = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ];  
    self::$pdo = new PDO(
      'mysql:host='.$host.';dbname='.$dbname, 
      $username, 
      $password, 
      $options
    );
    return self::$pdo ;
  }

  // retrieve a value from the data store
  static function get($key){
    return self::$data[$key];
  }

  // set a value in the data store
  static function set($key, $value){
    self::$data[$key] = $value;
  }

  // add a notice to the notice array
 static function notice($text, $class = "alert-success"){
    self::$notices[] = new Notice($text, $class);
  }

// The log is an array of messages along with their timestamp.
// use App::log("message") to add entries to the log as they happen
// use App::logDump() to display the results.
 static function log($text){
    $highResTime = hrtime(); // high resolution time.
    $now = implode($highResTime);
    $startDiff = 0;
    $recentDiff = 0;
    if (self::$log){
      // if there are existing entries
      // calculate the difference in time for each entry.
      $start = self::$log[0][0];
      $recent = self::$log[count(self::$log) - 1][0];
      $startDiff = $now - $start;
      $recentDiff = $now - $recent;
    } 
    self::$log[] = [$now, $startDiff, $recentDiff, $text];
  }

  // logDump displays logged messages in a basic table format. 
  // use App::logDump() to display the results.
  // see also /views/partials/footer.php
  static function logDump(){
    echo '<style>
        table,tr,td{ border: 1px solid black; }
        td{ padding: 5px 10px; }
    </style>';
    echo '<table style=" border-collapse: collapse;">';
    echo '<tr>';
        echo '<td>Event</td>';
        echo '<td>Time Since Start</td>';
        echo '<td>Time Since Previous</td>';
      echo '</tr>';
    foreach(self::$log as $detail ){
      echo '<tr>';
        echo '<td>'.$detail[3].'</td>';
        echo '<td>'.($detail[1]/1e+6).'</td>';
        echo '<td>'.($detail[2]/1e+6).'</td>';
      echo '</tr>';
    }
    echo '</table>';
  }


  // If a user is logged in, fetch their details and store them in App:User
  static function User(){  
    if ($_SESSION['user_ID'] && !self::$User  ){ 
        self::$User = User::fromID($_SESSION['user_ID']);        
    }
    return self::$User;
  }

  // reset session variables to logout the user
  static function logout(){
    $_SESSION = [];
    session_destroy();
    self::$User = null;
  }
 


}

?>

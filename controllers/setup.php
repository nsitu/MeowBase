<?php
  
  // This is a setup controller 
  // it loads the initial database structure as defined in ../migrations/setup.sql
  // be careful with this since it erases any existing data.
  
  // logout existing users before changing database structure
  App::logout();

  // located the SQL file that holds the database structure. 
  $sql = file_get_contents('../migrations/setup.sql');
  // run the migration
  $query = App::pdo()->prepare($sql);
  $query->execute();
  $query->closeCursor();
  App::notice('Table structure has been created.');

  // cache the newly created schema as JSON
  Model::schema(true);
  App::notice('Database schema has been cached for performance.');

  App::notice('Setup complete.');
  
  // show the login form 
  App::set("PageTitle", "Login");
  include '../views/loginForm.php';
  
?>
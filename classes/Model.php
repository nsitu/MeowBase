<?php 

// The Model is the M of MVC.

// There are often several models in an App (e.g. User, Post, Comment etc.)
// These models may all require similar functionality (e.g. fetching and saving data)
// We define such common features centrally here in the Model class
// each individual model can then extend the general model and inherit its features. 
// See also: https://www.php.net/manual/en/language.oop5.inheritance.php

// The Model class can:
// -use basic queries to instantiate new object(s) of the current class.
// -discover the table/column structure for current class. 
// -insert a new row into the database for the current object
// -update a row in the database for the current object
// -keep track of which properties are protected
// -generate a JSON representation of the current object 
// -import JSON data into the current object

// A helpful naming convention can make things simpler:
// Name each model the same as its corresponding database table
// e.g.  ../classes/User.php corresponds to a "User" table

class Model{ 

  // These variables will hold information about the schema (structure and relationships)
  // This information is also cached in /cache/schema.json
  static $structure = null;
  static $relationships = null;  

  // findMany is a
  static function findMany($options = ['limit' => "LIMIT 25"], $level = 1){ 
    
    $className = get_called_class(); 
    // figure out which columns our query will need.
    $columnList = $className::columnList(); 
    $join = ''; 
 
    // build JOINs for all existing manyToOne relationships
    // e.g. Many Meows each have One Author (User)
    foreach ($className::manyToOne() as $relationship){
      $otherTable = $relationship->REFERENCED_TABLE_NAME;
      $pk = $relationship->REFERENCED_COLUMN_NAME;
      $fk = $relationship->COLUMN_NAME;
      $join .= " JOIN `$otherTable` ON `$className`.`$fk` = `$otherTable`.`$pk` ";
      $columnList .= ', '.$otherTable::columnListAliased();
    }
    
    $where = ($options['where'])? $options['where'] : '';
    $limit = $options['limit'];
    $order = ($options['order'])? $options['order'] : '';
    
    // Assemble a full query from the given joins and clauses. 
    $sql = " SELECT $columnList FROM `$className` $join $where $order $limit"; 
    $query = App::pdo()->prepare($sql); 
    $query->execute();  
    $results = $query->fetchAll(PDO::FETCH_CLASS, $className);
 
    // Build a full data structure from the results of the join query 
    // The rows are "flat" but the outcome here is a nested hierarchy (objects within objects)
    // e.g. Meow.User_FullName becomes Meow.User.FullName
    foreach ($results as $key => $result){
      foreach ($className::manyToOne() as $relationship){        
        $otherTable = $relationship->REFERENCED_TABLE_NAME;
        $pk = $relationship->REFERENCED_COLUMN_NAME;
        $results[$key]->$otherTable = new $otherTable();
        foreach( $otherTable::safeColumns() as $col){
          $aliasedProp = $otherTable."_".$col;
          $results[$key]->$otherTable->$col = $results[$key]->$aliasedProp;
          if ($col != $pk ) unset($results[$key]->$aliasedProp);
        }     
      }
    }  
           
    // Fetch related data for all OneToMany relationships
    // e.g. each Meow has many related Comments
    // ()
    if (count($results) > 0 && $level == 1 ){
      foreach ($className::oneToMany() as $relationship){
        $otherTable = $relationship->TABLE_NAME;
        $fk = $relationship->COLUMN_NAME;
        $pk = $relationship->REFERENCED_COLUMN_NAME;  
        $pks = implode(',',array_column($results, $pk));
        // below we are calling findMany() from within findMany()
        // when a funciton calls itself, this is called "recursion"
        $related = $otherTable::findMany([
          'where' => "WHERE `$otherTable`.`$fk` IN ($pks)"
        ], 2);
        foreach ($results as $key => $result){
          $results[$key]->$otherTable = [];
          foreach ($related as $relative){
            if($result->$pk == $relative->$fk){
              $results[$key]->$otherTable[] = $relative;
            }
          }
        }
      }
    }
    return $results;     
  }
 

  // SCHEMA - Discover the structure of the database and cache it in JSON as needed.
  static function schema($reset = false){
    $schemaFile = '../cache/schema.json';
    if (!file_exists($schemaFile)) file_put_contents($schemaFile, '');
    $schema = json_decode(file_get_contents($schemaFile)); 
    // if the json file is empty we will populate it.
    if (! $schema || $reset){ 
      $schema = [];
      $db = getenv('DBNAME');
      $query = App::pdo()->query(" SHOW TABLES FROM `$db` ");
      $tables = $query->fetchAll(PDO::FETCH_COLUMN);
      foreach ($tables as $table){
        // columns
        $query = App::pdo()->query(" SHOW COLUMNS FROM `$table` ");
        $schema['structure'][$table]=$query->fetchAll(PDO::FETCH_OBJ); 
        //relationships
        $query = App::pdo()->query(
          "SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
          FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
          WHERE REFERENCED_TABLE_SCHEMA = '$db' AND (TABLE_NAME = '$table' OR REFERENCED_TABLE_NAME = '$table');"); 
        $schema['relationships'][$table] = $query->fetchAll(PDO::FETCH_OBJ); 
      }
      file_put_contents($schemaFile, json_encode($schema));
    }

    $schema = json_decode(file_get_contents($schemaFile)); 
    Model::$structure = $schema->structure;
    Model::$relationships = $schema->relationships;
    
  }


  // instantiate a new object of the current class based on a known unique column
  static function fromColumn($column, $value){
    $className = get_called_class();
    if( ! $className::hasColumn($column)  ) return false;
    $columnList = '`'.implode('`, `', $className::columns() ).'`';
    $query = App::pdo()->prepare(" SELECT $columnList FROM `$className`  WHERE `$column` = ? "); 
    $query->execute([ $value ]);  
    return $query->fetchObject( $className ); 
  }

  // instantiate a new object of the current class based on a known ID number. 
  static function fromID(int $ID){
    $className = get_called_class();
    $columnList = '`'.implode('`, `', $className::columns() ).'`';
    $query = App::pdo()->prepare(" SELECT $columnList FROM `$className`  WHERE `ID` = ? "); 
    $query->execute([ $ID ]);  
    return $query->fetchObject( $className ); 
  }

  // Describe the database Table corresponding to this class
  // i.e. An array of columns:  (Field, Type, Null, Key, Default, Extra)
  static function structure(){  
    if (! Model::$structure ) Model::schema();
    $className = get_called_class(); 
    return Model::$structure->$className;
  }

  // MySQL tracks Foreign and Primary Key constraints in the "Information Schema"
  // We can query it to find details about reationships
  static function relationships(){
    if (! Model::$relationships ) Model::schema();
    $className = get_called_class();
    return Model::$relationships->$className; 
  }

  static function manyToOne(){
    $className = get_called_class();
    return array_filter( 
      $className::relationships(),  
      fn($relationship) => $relationship->TABLE_NAME == $className  
    ); 
  }

  static function oneToMany(){
    $className = get_called_class();
    return array_filter( 
      $className::relationships(),  
      fn($relationship) => $relationship->REFERENCED_TABLE_NAME == $className  
    ); 
  }


  // Get an array of column names for for the current class
  static function columns(){
    $className = get_called_class();
    $columns = array_map( fn($col) => $col->Field, $className::structure() ); 
    return array_values($columns);
  }

  // Make a list of column names for the current class 
  // e.g. `User`.`FirstName`, `User`.`LastName`
  static function columnList(){
    $className = get_called_class();
    $prefixed = array_map( fn($col) => "`$className`.`$col`", $className::columns() ); 
    return implode(", ", $prefixed );
  }

  // Make an aliased list of column names for the current class 
  // e.g. `User`.`FirstName` as User_FirstName, `User`.`LastName` AS User_LastName
  static function columnListAliased(){
    $className = get_called_class();
    $underscore="_";
    $aliased = array_map( fn($col) => "`$className`.`$col` AS `$className$underscore$col`", $className::safeColumns() ); 
    return implode(", ", $aliased );
  }

  // check if a given column exists for the current class
  static function hasColumn($columnName){
    $className = get_called_class();
    return ( in_array($columnName, $className::columns() ) ) ? true : false;    
  }

  // Get the datatype for a given Column
  static function columnType($columnName){ 
    $className = get_called_class();
    foreach ($className::structure() as $column){
      if ($column->Field == $columnName) return $column->Type;
    }
    return false;
  }

  // Get default value for a given Column
  static function columnDefault($columnName){ 
    $className = get_called_class();
    foreach ($className::structure() as $column){
      if ($column->Field == $columnName) return $column->Default;
    }
    return false;
  }

  // Check whether a column is required. 
  static function columnIsRequired($columnName){
    $className = get_called_class();
    foreach ($className::structure() as $column){
      if ($column->Field == $columnName){
        return ($column->Null == "NO")? true : false;
      }
    }
    return false;
  }

  // Insert a new row into the Database for this object. 
  // Map object properties onto columns (including protected propreties)
  // e.g. INSERT INTO Table (`ColumnA`, `ColumnB`, `ColumnC`) VALUES (?,?,?);
  function insert(){  
    try{
      $className = get_called_class();     
      $columnList = '`'.implode('`, `', $className::columns() ).'`';
      $placeholders = implode(',', array_fill(0, count( $className::columns() ), '?'));
      $sql = " INSERT INTO `$className` ($columnList) VALUES ($placeholders); " ;
      $values = array_map( fn($col) => $this->$col, $className::columns() ); 
      App::pdo()->prepare($sql)->execute($values);
      $this->ID = App::pdo()->lastInsertId();
      return $this->ID ;
    }
    catch (PDOException $e) {
      return $e->getMessage();
    }
  }

  // Update the Database to reflect the properties of the current object 
  // Map properties onto columns (excluding protected properties)
  // e.g. UPDATE `Table` SET `ColumnA`=?, `ColumnB`=?, `ColumnC`=? WHERE `ID` = ?;
  function update(){  
    try{
      $className = get_called_class(); 
      $setList = implode(', ', array_map( fn($col) => '`'.$col.='`=?', $this->safeColumns() ) );
      $values = array_map( fn($col) => $this->$col, $this->safeColumns() ); 
      $values[] = $this->ID;
      $sql = " UPDATE `$className` SET $setList WHERE `ID` = ? ";
      $update = App::pdo()->prepare($sql);
      $update->execute($values);   
      return $update->rowCount();
    }
    catch (PDOException $e) {
      return $e->getMessage();
    }
  }

  function delete(){  
    try{
      $className = get_called_class(); 
      $sql = " DELETE FROM `$className` WHERE `ID` = ? " ;
      $delete = App::pdo()->prepare($sql);
      return $delete->execute([$this->ID]);
    }
    catch (PDOException $e) {
      return $e->getMessage();
    }
  }
  // If this object has a known ID save via update()
  // If this object does not have an ID yet, perform an insert() instead
  function save(){
    return (is_numeric($this->ID)) ?
      $this->update() :
      $this->insert();
  }

  // List the names of protected properties for this class.
  // e.g. ["Password"]
  // function protectedProperties(){
  //   $reflect = new ReflectionObject($this);
  //   return array_map( 
  //     fn($property) => $property->getName(), 
  //     $reflect->getProperties(ReflectionProperty::IS_PROTECTED)
  //   ); 
  // }


  static function protectedProps(){
    $reflect = new ReflectionClass(static::class);
    return array_map( 
      fn($property) => $property->getName(), 
      $reflect->getProperties(ReflectionProperty::IS_PROTECTED)
    ); 
  }


  // function publicProperties(){
  //   $reflect = new ReflectionObject($this);
  //   return array_map( 
  //     fn($property) => $property->getName(), 
  //     $reflect->getProperties(ReflectionProperty::IS_PUBLIC)
  //   ); 
  // }


  // List the names of public properties for this class.
  static function publicProps(){
    $reflect = new ReflectionClass(static::class);
    return array_map( 
      fn($property) => $property->getName(), 
      $reflect->getProperties(ReflectionProperty::IS_PUBLIC)
    ); 
  }

  
  // check whether a given property is protected
  static function isProtectedProp($property){ 
    return (in_array( $property, self::protectedProps() ) )? true : false;
  }


  // check whether a given property is protected
  // function isProtected($property){ 
  //   return (in_array( $property, $this->protectedProperties() ) )? true : false;
  // }
 
   // list column names for this object, excluding protected properties
  static function safeColumns(){
    $className = get_called_class(); 
    $columns = array_filter( $className::columns(), 
      fn($col) => !static::isProtectedProp($col)
    );
    return array_values($columns);
  }


  // list column names for this object, excluding protected properties
  // function safeColumns(){
  //   $className = get_called_class(); 
  //   $columns = array_filter( $className::columns(), 
  //     fn($col) => !$this->isProtected($col)
  //   );
  //   return array_values($columns);
  // }

  /* Make a JSON version of this object's properties,
   but only include the ones that are public. */
  function json_export(){ 
    $jsonCopy = $this;
    $className = get_called_class(); 
    foreach ($className::protectedProps() as $property) {
      unset($jsonCopy->$property);
    }
    return json_encode($jsonCopy);
  }

  /* Import data from a JSON array JSON into this object's properties
    but only include the ones that are public. */
  function json_import($json){  
    $data = json_decode($json, true); 
    if ($this->ID == $data['ID']){ 
      foreach(static::safeColumns() as $column){
        // take a blank string to mean null so the database will accept it
        $this->$column = ($data[$column] == '')? null : $data[$column]; 
      }
      foreach(static::manyToOne() as $relationship){ 
        $otherTable = $relationship->REFERENCED_TABLE_NAME; 
        $pk = $relationship->REFERENCED_COLUMN_NAME; 
        $fk = $relationship->COLUMN_NAME; 
        // if there is a relationship, record it.
        if ($data[$otherTable][$pk]){
          $this->$fk = $data[$otherTable][$pk];
        }
        // If you also wanted to also import all the data you could do this:
        // $this->$otherTable = $data[$otherTable];
      }
    }
  }

 
 
  // Generate a human friendly,  version of the date time relative to the current time.
  function niceDate(){
    $input_time = new DateTime($this->PublishDateTime); //Time of post
    $now = new DateTime(date("Y-m-d H:i:s")); //Current time
    $age = $input_time->diff($now); //Difference between dates
    if($age->y >= 1) {
      return ($age->y == 1)? "Last Year" : $age->m . " years ago";
    }
    elseif($age->m >= 1) {
      return ($age->m == 1)? "Last Month" : $age->m . " months ago";
    }
    elseif($age->d >= 1) {
      return ($age->d == 1)? "Yesterday" : $age->d . " days ago";
    }
    else if($age->h >= 1) {
      return ($age->h == 1)? "1 hr ago" : $age->h . " hrs ago";
    }
    else if($age->i >= 1) {
      return ($age->i == 1)? "1 min ago" : $age->i . " mins ago";
    }
    else {
      return ($age->s < 30)? "Just now" : $age->s . " seconds ago";
    }
  }
  
 


}

?>
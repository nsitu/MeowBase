<?php

// this controller deals with file uploads. 
// it relies on ../classes/File.php to do the heavy lifting. 
// rather than calling a view, it returns json data directly to the frontend.
// javascript / vuejs will do the rest. 

header('Content-Type: application/json; charset=utf-8');
$status = File::upload();
echo json_encode( $status );
exit; //  Vue.js will take it from here.


?>
<?php 

// The File class handles file uploads. 
// we'll use this to add profile pictures, etc. 

class File{

  static function upload(){ 
    // if anything was uploaded PHP finds it in the $_FILES array 
    if(isset($_FILES['file']['name'])) {
      // get the name of the uploaded file
      $image_name = $_FILES['file']['name']; 
      // find out what the uploaded file extension is (e.g. jpg)
      $extension = pathinfo($image_name, PATHINFO_EXTENSION);
      // only allow image files
      if(in_array($extension, ["jpg","jpeg","png"])) {
        // here we assign a unique name to the file based on the current timestamp.
        $file_url = '/images/uploads/' . time() . '.' . $extension;
        // get the current working directory
        $file_path = getcwd() . $file_url;
        // move the uploaded file to its permanent home
        if(move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
          // send back a success message along with the file URL
          return  [
            'status'  => 'Image Uploaded',
            'url'   => $file_url
          ];
        }
        // send back an upload error message
        return  [ 'status'  => 'Upload Error: '.$_FILES['file']['tmp_name'].' > '.$file_path ];
      }
      // send back a format error message if it was not an image
      return  [ 'status'  => 'Format Not Supported' ];
    }
    // send back a file error if the file is missing
    return  [ 'status'  => 'File not found' ];
  }

}

?>
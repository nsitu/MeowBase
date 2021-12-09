<?php 
// Render all the notices inside a bootstrap alert.

if (App::$notices != null ) { ?>
  <div class="container mt-2">
      <?php   
        foreach ( App::$notices as $notice){
          echo $notice->render();
        }
      ?>
  </div>
<?php } ?>
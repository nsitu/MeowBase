<nav class="navbar navbar-expand-sm navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="/"><?= App::get('SiteName'); ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
        <?php 
        
        

        if ( App::User() ){
          $item = new NavItem('Meows', App::root().'/');
          echo $item->render();
          $item = new NavItem('Cats', App::root().'/cats');
          echo $item->render(); 
          $item = new NavItem(App::User()->FullName, App::root().'/profile');
          echo $item->render();  
          $item = new NavItem('Logout', App::root().'/logout');
          echo $item->render();
        }
        else{
          $item = new NavItem('Home', App::root().'/');
          echo $item->render();
        }

        ?>
      </ul>
    </div>
  </div>
</nav>

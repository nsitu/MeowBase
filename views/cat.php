<?php include 'partials/header.php'; ?> 
 
<div id="userBanner">
  <?php include 'partials/navigation.php';    ?>
  
  <h1 class="display-4">
    <?= $user->FullName ?>
  </h1> 
</div>

<?php include 'partials/notices.php'; ?> 

<section class="container p-3" >  
    <div class="row">
      <div class="col-md-3" id="cat" style="margin-top: -20vh;">
        <div class="square">
          <div class="ProfilePicture"  :style="{ backgroundImage: `url(${User.ProfilePicture})` }">
          </div>
        </div> 
        <h2>{{ User.FullName }}</h2>
        </p><span class="material-icons">fingerprint</span>{{ User.Bio }}</p>
        </p><span class="material-icons">pets</span>{{ User.Breed }}</p>
        </p><span class="material-icons">event</span>{{ User.DateOfBirth }}</p>
        </p><span class="material-icons">phone</span>{{ User.Phone }}</p>
        </p><span class="material-icons">restaurant</span>{{ User.Eats }}</p>
      </div>
      <div class="col-md-9 col-lg-7 offset-lg-1">
         
        <?php include 'partials/meows.php';   ?>
      </div>
    </div>

</section> <!--end section -->

<script>

/* https://v3.vuejs.org/ */ 
  
Vue.createApp({ 
 data(){
    return {
      User: <?= json_encode($user) ?> 
    }
 }
}).mount('#cat')

</script>

 
 

<?php  include 'partials/footer.php'; ?>
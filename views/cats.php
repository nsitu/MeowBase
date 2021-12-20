<?php include 'partials/header.php'; ?> 
 
<div id="userBanner">
  <?php include 'partials/navigation.php';    ?>
  <h1 class="display-4">
    Clowder
  </h1> 
  <h2>/ˈkloudər/</h2>
  <p>A group of cats.</p>
</div>

<?php include 'partials/notices.php'; ?>
  

<section class="container gap-2 p-3" id="cats">  

  <div class="text-center my-2 w-100" v-if="showLoading">
  <!-- https://loading.io/  -->
    <div class="lds-dual-ring"></div>
  </div>
 
  <div class="row"> 
    <div v-for="(user, userKey) in users" :key="user.ID" class="cat col-3 p-3">
      <div class="square">
        <div class="ProfilePicture"  :style="{ backgroundImage: `url(${this.root + user.ProfilePicture})` }">
        </div>
      </div>  
      <h2 class="text-center">
        <a :href="profileLink(userKey)">{{ user.FullName }}</a>
      </h2>
    </div>   
  </div> 
  
</section> <!--end section -->

<script>

/* https://v3.vuejs.org/ */ 
  
Vue.createApp({ 
 data(){
    return {
      root: '<?= App::root(); ?>',
      users: {},  
      showLoading:true
    }
 },
 methods:{         
   profileLink : function(userKey){
      return this.root+ '/cats/'+this.users[userKey].ID;
   }
 },  
  mounted () {
    axios.get(this.root+'/cats/json')
      .then(response => {
        this.users = response.data;
        this.showLoading = false;
      })
      .catch(error => console.log(error));
    
  } 
}).mount('#cats')

</script>

 
 

<?php  include 'partials/footer.php'; ?>

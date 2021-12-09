<?php include 'partials/header.php'; ?> 
 
<div id="userBanner">
  <?php include 'partials/navigation.php';    ?>
  <h1 class="display-4">
    <?= App::User()->FullName ?>
  </h1> 
</div>

<?php include 'partials/notices.php';  ?>
 
<main class="container" id="profile">

  <div v-if="showAlert" class="alert alert-dismissible" :class="alertType">
    <a href="#" class="close" aria-label="close" @click="showAlert=false">&times;</a>
    <!-- VueJS lets you put data on the page with {{double}} curly braces. 
  Find out more here: https://v3.vuejs.org/guide/template-syntax.html -->
    {{ alertMessage }}
  </div>  

  <div class="row justify-content-center">
    <div class="col-md-6 py-3">
    
        <!-- VueJS lets us do conditional rendering with the "v-if" directive.
        In this way we can display things only when we need them. 
        red more about this here: https://v3.vuejs.org/guide/introduction.html#conditionals-and-loops  -->
      <div v-if="showProfile">
        <div class="square" style="margin-top: -20vh;">
          <div class="ProfilePicture"  :style="{ backgroundImage: `url(${user.ProfilePicture})` }">
          </div>
        </div> 
        <h2>{{ user.FullName }}</h2>
        </p><span class="material-icons">fingerprint</span>{{ user.Bio }}</p>
        </p><span class="material-icons">pets</span>{{ user.Breed }}</p>
        </p><span class="material-icons">event</span>{{ user.DateOfBirth }}</p>
        </p><span class="material-icons">phone</span>{{ user.Phone }}</p>
        </p><span class="material-icons">restaurant</span>{{ user.Eats }}</p>
        <button class="btn btn-primary btn-lg" role="button" @click="edit">Edit</button>
      </div> 
      
      <div v-if="showForm">

        <!-- VueJS lets to modify the way events behave, 
        e.g. we can override the normal behaviour of a form submission:
        instead of reloading the page, we can run a function instead
        See also: https://v3.vuejs.org/guide/events.html#event-modifiers -->
        <form action="index.php" method="post" class="p-3" @submit.prevent="saveData">
         
          <div class="form-group"> 
          <!-- Javascript can do variable substitution as long as you use backticks to define the boundaries of a string. https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Template_literals#syntax  -->
            <div class="square" style="margin-top: -20vh;">
              <div class="ProfilePicture"
                :style="{ backgroundImage: `url(${user.ProfilePicture})` }" >
              </div>
            </div>

            <label for="fileInput" class="btn btn-primary">
              <span class="material-icons">file_upload</span> Select file
            </label>
            <!-- Note the VueJS shorthands used below:
                :value is shorthand for v-bind:value 
                @change is shorthand for v-on:change
            See also: https://v3.vuejs.org/guide/template-syntax.html#v-bind-shorthand -->
            <input class="d-none" id="fileInput" type="file" @change="uploadFile" />
            <input type="hidden" name="ProfilePicture" :value="user.ProfilePicture">
          </div>
          <div class="form-group w-100 py-3">
            <label for="Bio" class="text-muted">What's your thing?</label>
            <textarea v-model="user.Bio" name="Bio" class="form-control" id="bio_text" rows="4">{{ user.Bio }}</textarea>
          </div>

          <div class="form-group w-100 py-3">
            <label for="DateOfBirth" class="text-muted">First Meow?</label>
            <input type="date" v-model="user.DateOfBirth" name="DateOfBirth" class="form-control" > 
          </div>

           <div class="form-group w-100 py-3">
            <label for="Phone" class="text-muted">Phone</label>
            <input type="text" v-model="user.Phone" name="DateOfBirth" placeholder="123-456-7890" class="form-control" > 
          </div>

          <div class="form-group w-100 py-3">
            <label for="Breed" class="text-muted">Breed</label>
            <input type="text" v-model="user.Breed" name="Breed" placeholder="Domestic Short Hair" class="form-control" > 
          </div>

          <!-- A Select List for 'Eats' (aka. favourite food).
          See also: https://getbootstrap.com/docs/5.1/forms/select/
          -->
          <div class="form-group w-100 py-3">
            <label for="Eats" class="text-muted">Favourite Food</label>
              <!-- v-model  -->
              <select v-model="user.Eats" class="form-select form-select-sm" aria-label=".form-select-sm example">
                <option disabled value="">Please select one</option>
                <option value="Friskies">Friskies</option>
                <option value="Hills">Hills</option>
                <option value="Royal Canin">Royal Canin</option>
                <option value="Purina">Purina</option>
              </select>
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-primary">Save</button>
          </div> 
        </form>
      </div>   
    </div>
  </div>
</main>
<script>

/* https://v3.vuejs.org/ */ 

Vue.createApp({ 
 data(){
    return {
      user: <?= App::User()->json_export(); ?>,
      modified: false,
      file:'',
      showProfile:true,
      showForm:false,
      showAlert:false,
      alertType:'alert-success',  /* alert-danger */
      alertMessage: ''
    }
 },
 methods:{ 
   /* when you click the edit button we show and hide various elements. */
  edit(){
    this.modified = false;
    this.showProfile = false;
    this.showForm = true;
  }, 
  /* if you click save, axios uploads the data to PHP. https://axios-http.com/docs/intro 
    but first we make sure there are actualy some changes to upload. 
  */
  saveData(event){  
    if (this.modified){
      /* here JavaScript sends the form data to PHP 
      with a little help from the axios HTTP library. 
      See also: https://axios-http.com/ */
      axios.post('/profile', this.user)
        .then(response => {
          console.log(response.data);
          if( typeof response.data == "string" ){ 
              this.alertType = 'alert-warning';
              this.alertMessage = response.data ;
          }
          else{
            this.alertType = 'alert-success';
            this.alertMessage= 'Data Saved.';
            this.user = response.data; 
          }
          this.showAlert = true;
          this.showForm = false;
          this.showProfile = true;
        })
    }
    else{ 
      this.showForm = false;
      this.showProfile = true;
    }
  },
  uploadFile(event){ 
    let formData = new FormData();  
    formData.append('file', event.target.files[0]);
     /* here JavaScript uploads our image file to PHP 
      with a little help from the axios HTTP library. 
      See also: https://axios-http.com/ */
    axios.post('/profile', formData, {
      header:{ 'Content-Type' : 'multipart/form-data' }
    }).then(response => { 
      if( ! response.data.url ){ 
        this.alertType = 'alert-warning'; 
      }
      else {
        this.alertType = 'alert-success';  
        this.user.ProfilePicture = response.data.url; 
        event.target.files[0] = ''; 
      }
      this.alertMessage = response.data.status; 
      this.showAlert = true;
      event.target.value = ''; // clear the file upload field.
   })
  }
 },
 watch: {
   'user': {
      handler: function() { this.modified=true; },
      deep: true
    } 
  },
  mounted () {
    this.edit();
  }
}).mount('#profile')

</script>

<?php  include 'partials/footer.php'; ?>
<!-- 
Below you'll find the meow feed 
the UX is built with Bootstrap and VueJS.
 -->

<div id="meows">
  <div v-if="showAlert" class="alert alert-dismissible" :class="alertType">
    <a class="close" aria-label="close" @click="showAlert=false">&times;</a> 
    {{ alertMessage }}
  </div>  

  <!-- A form for adding new meows. -->
  <form v-if="showMeowForm" method="post" class="py-3" @submit.prevent="saveMeow"> 
    <div class="form-group py-3">
      <div v-if="Meow.Picture">
        <img :src="this.root + Meow.Picture" class="MeowPicture my-1" />
      </div> 
      <textarea v-model="Meow.Body" name="Body" class="form-control"  rows="4" placeholder="Meow?">{{ Meow.Body }}</textarea>
    </div> 
    <div class="row">
      <div class="col">
        <div class="form-group">
          <label for="fileInput" class="btn btn-link">
            <span class="material-icons">image</span>
          </label>
          <input class="d-none" id="fileInput" type="file" @change="uploadFile" /> 
        </div>
      </div>
      <div class="col text-end">
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Meow</button>
        </div> 
      </div>
    </div> 
  </form>   <!-- end meow form -->
  
  <div class="text-center my-2" v-if="showLoading">
  <!-- https://loading.io/  -->
    <div class="lds-dual-ring"></div>
  </div>

  
  <div v-if="showMeowDesert" class="text-center text-muted py-5">
    <!-- If there are no meows to show then this is the default message.  -->
    In a meow desert there are no meows. Not even one.
  </div>  

  <!-- 
  Below is the template for  meows  
  It's wrapped in a VueJS transition-group to add some animated spice. 
  See also: https://v3.vuejs.org/guide/transitions-list.html#list-entering-leaving-transitions
  -->
  <transition-group name="meow" tag="p">
    <div class="meow py-3" v-for="(Meow, meowKey) in Meows" :key="Meow.ID">
      <div class="row">
        <div class="col-2">
          <div class="square">
            <div class="MeowUserAvatar"  :style="{ backgroundImage: `url(${this.root + Meow.User.ProfilePicture})` }">
            </div>
          </div>
        </div> 
        <div class="col-10 d-flex align-items-center">
          <p class="me-0"><a :href="profileLink(meowKey)">{{ Meow.User.FullName }}</a> <span class="niceDate">{{ Meow.niceDate }}</span></p> 
        </div>
      </div>
      <div class="row my-1">
        <div class="col">
          <img v-if="Meow.Picture" :src="this.root + Meow.Picture" class="MeowPicture my-1"  />
          <p>{{ Meow.Body }}</p> 
          
          <a :class="pawStyle(meowKey)" class="pawButton btn btn-link" @click="togglePaw(meowKey)">
            <span class="material-icons">pets</span>
            <span class="count" v-if="Meow.Paw.length">{{Meow.Paw.length}}</span>
          </a>

          <a class="commentButton btn btn-link" @click="toggleComments(meowKey)">
            <span class="material-icons">comment</span>
            <span class="count" v-if="Meow.Comment.length">{{Meow.Comment.length}}</span>
          </a>
          <a class="delete btn btn-link" v-if="Meow.User.ID == User.ID" @click="deleteMeow(meowKey)"><span class="material-icons">delete</span></a>
          
          <div v-if="showComment == meowKey" class="comments">
            <form method="post" class="pb-3" @submit.prevent="saveComment(meowKey)">   
              <div class="form-group py-3"> 
                <textarea v-model="Meow.newComment.Body" class="form-control" rows="2" placeholder="Comments?">{{ Meow.newComment.Body }}</textarea>
              </div> 
              <div class="form-group text-end">
                <button type="submit" class="btn btn-primary btn-sm">Comment</button>
              </div> 
            </form> 
            
            <!-- https://v3.vuejs.org/guide/transitions-list.html#list-entering-leaving-transitions -->
            <transition-group name="comment" >
              <div class="comment" v-for="(Comment, commentKey) in Meow.Comment" :key="Comment.ID">
                <a :href="profileLink(meowKey, commentKey)" class="commentUserFullName">{{ Comment.User.FullName }} </a>
                <p>{{ Comment.Body }}<a class="delete btn btn-link" v-if="Comment.User.ID == User.ID" @click="deleteComment(meowKey, commentKey)"><span class="material-icons">delete</span></a></p>
              </div>
            </transition-group>

          </div> <!-- end comment area -->
        </div> <!-- end full width column -->
      </div> <!-- end row -->
    </div> <!-- end  meow -->
  </transition-group>
</div>

<script>

/* https://v3.vuejs.org/ */ 

/* This is a blank template for a new Meow. 
It assigns a few details about currently logged-in user 
Along with "Just Now" as a the time of the Meow. */
 
 
 /**/
Vue.createApp({ 
 data(){
    return {
      root: '<?= App::root(); ?>',
      User: {},
      Meows:{},
      Meow: {},
      profileID: <?= ( Request::ID() )?  Request::ID() : 'false'; ?>,
      modified: false,
      file:'',
      showLoading:true,
      showComment:null,
      showAlert:false,
      showMeowDesert: false,
      showMeowForm: false,
      alertType:'alert-success',  /* alert-danger */
      alertMessage: ''
    }
 },
 methods:{  
 
   currentUser(){
    const currentUser = <?= App::User()->json_export(); ?>;
    return {
      ID: currentUser.ID,
      ProfilePicture: currentUser.ProfilePicture,
      FullName: currentUser.FullName
    }
   },
   newMeow(){
    return {
      User : this.currentUser(),
      Comment : [],
      Paw : [],
      Body : "",
      niceDate : "Just Now"
    }
   }, 
   newComment(key){
    return {
      User : this.currentUser(),
      Meow : {ID:this.Meows[key].ID},
      Body : "",
      niceDate : "Just Now"
    }
   }, 
   newPaw(key){
    return {
      User : this.currentUser(),
      Meow : {ID:this.Meows[key].ID}
    }
   }, 
   resetAlert(){
    setTimeout(() => { 
        this.showAlert = false;
        this.alertMessage = '';
     }, 10000);
   },
   warn(warningText){
    this.alertType = 'alert-warning';
    this.alertMessage = warningText ;
    this.showAlert = true;
    this.resetAlert();
   },
   success(successText){
    this.alertType = 'alert-success';
    this.alertMessage = successText ;
    this.showAlert = true;
    this.resetAlert();
   },
  saveMeow(event){  
    this.showComment = null;
    if (this.modified){ 
      axios.post(this.root + '/meows', this.Meow)
        .then(response => {
          console.log(response.data);
          if( typeof response.data == "string" ){ 
              this.warn(response.data);
          }
          else{
            this.showAlert = false;
            this.Meow.ID = response.data.ID;
            this.Meows.unshift(this.Meow);
            this.Meow = this.newMeow();
          }
        })
    } 
  },
  uploadFile(event){ 
    let formData = new FormData();  
    formData.append('file', event.target.files[0]); 
    axios.post(this.root + '/meows', formData, {
      header:{ 'Content-Type' : 'multipart/form-data' }
    }).then(response => { 
      if( ! response.data.url ){ 
        this.warn(response.data.status); 
      }
      else {
        this.success(response.data.status); 
        this.Meow.Picture = response.data.url; 
        this.showPicture = true;
        event.target.files[0] = ''; 
      }  
      event.target.value = ''; // clear the file upload field.
   })
  }, 
  saveComment(key){   
    axios.post(this.root + '/comments', this.Meows[key].newComment )
      .then(response => {        
        if( typeof response.data == "string" )  this.warn(response.data); 
        else if( ! response.data ) this.warn( "Unable to Add Comment");  
        else { 
          this.Meows[key].newComment.ID = response.data.ID;
          this.Meows[key].Comment.unshift(this.Meows[key].newComment); 
          this.Meows[key].newComment = this.newComment(key);
        }
      })
      .catch(error => console.log(error))  
  }, 
  deleteMeow(meowKey){  
    this.showComment = null;
    axios.delete(this.root + '/meows/'+this.Meows[meowKey].ID)
      .then(response => {  
        if( typeof response.data == "string" )  this.warn(response.data); 
        else if( ! response.data ) this.warn("Unable to Delete Meow"); 
        else  this.Meows.splice(meowKey, 1);  // remove meow from array
      })
      .catch(error => console.log(error))  
  },
  deleteComment(meowKey, commentKey){
    axios.delete(this.root + '/comments/'+this.Meows[meowKey].Comment[commentKey].ID)
      .then(response => {  
        if( typeof response.data == "string" )  this.warn(response.data); 
        else if( ! response.data )  this.warn("Unable to Delete Comment"); 
        else this.Meows[meowKey].Comment.splice(commentKey, 1)  // remove comment from array
      })
      .catch(error => console.log(error))  
  },

  pawStyle(meowKey){
    let pawIndex = this.Meows[meowKey].Paw.findIndex(e => e.User.ID === this.User.ID);
    if (pawIndex > -1) return 'active';
    else return 'inactive';
  },

   togglePaw(meowKey){
    // Look for an existing paw given to this meow by the current User. 
    let pawIndex = this.Meows[meowKey].Paw.findIndex(e => e.User.ID === this.User.ID)
    // If a paw exists remove it. 
    if (pawIndex > -1){
      axios.delete(this.root + '/paws/'+this.Meows[meowKey].Paw[pawIndex].ID)
      .then(response => {  
        if( typeof response.data == "string" ) this.warn(response.data); 
        else if( ! response.data ) this.warn("Unable to Delete Paw"); 
        else this.Meows[meowKey].Paw.splice(pawIndex, 1);
      })
      .catch(error => console.log(error))  
    }
    // if no paw exists, add one. 
    else{
      let newPaw = this.newPaw(meowKey);
      axios.post(this.root + '/paws', newPaw )
      .then(response => {         
        if( typeof response.data == "string" ) this.warn(response.data); 
        else if( ! response.data )  this.warn("Unable to Add Paw"); 
        else { 
          newPaw.ID = response.data.ID;
          this.Meows[meowKey].Paw.unshift(newPaw); 
        }
      })
      .catch(error => console.log(error))  
    } 
  },
  toggleComments(meowKey){
    // if comments are visible for the current meow, hide them.
    if (this.showComment == meowKey) this.showComment = null;
    // if comments are hidden for the current meow, show them. 
    else{
      this.showComment = meowKey;
      // make a new blank comment if we don't already have one.
      if (! this.Meows[meowKey].newComment){
        this.Meows[meowKey].newComment =  this.newComment(meowKey);
      }
      // make an array to hold comments if we don't already have one.
      if (! this.Meows[meowKey].Comment){
        this.Meows[meowKey].Comment = [];
      }
    } 
  },
   profileLink : function(meowKey, commentKey = null){
     // if a comment Key is given, link to the author of the comment
    if (commentKey != null) return this.root + '/cats/'+this.Meows[meowKey].Comment[commentKey].User.ID;  
    // otherwise link to the author of the given meow
    else return this.root + '/cats/'+this.Meows[meowKey].User.ID;
   }
 }, 
  watch: {
   'Meow': {
      handler: function() { this.modified=true; },
      deep: true
    },
    'Meows': {
      handler: function() { 
        this.showMeowDesert = (this.Meows.length)? false : true; 
      },
      deep: true
    }  
  },
  mounted () {
    this.User = this.currentUser();
    this.Meow = this.newMeow();
    let dataURL = this.root + '/meows';  
    if (this.profileID != false) dataURL += '/'+this.profileID; 
    if (this.profileID == false || this.profileID == this.User.ID) this.showMeowForm = true;
    
    axios.get(dataURL)
      .then(response => {
        this.Meows = response.data;
        this.showLoading = false;
      })
      .catch(error => console.log(error)); 
  } 
}).mount('#meows')

</script>

 
